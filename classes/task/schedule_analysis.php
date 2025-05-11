<?php
namespace block_educolab\task;

defined('MOODLE_INTERNAL') || die();

class schedule_analysis extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('schedule_analysis', 'block_educolab');
    }

    public function execute() {
        global $DB;

        $schedules = $DB->get_records_select('block_educolab_schedule', 'nextrun <= :now', ['now' => time()]);

        foreach ($schedules as $schedule) {
            $forums = $DB->get_records('forum', ['course' => $schedule->courseid]);
            $firstForum = reset($forums);
            
            $discussions = $DB->get_records('forum_discussions', ['forum' => $firstForum->id]);
            
            $messages = [];
            
            foreach ($discussions as $discussion) {
                $posts = $DB->get_records('forum_posts', ['discussion' => $discussion->id]);
                
                foreach ($posts as $post) {
                    $user = $DB->get_record('user', ['id' => $post->userid]);
                    
                    $wordcount = str_word_count(strip_tags($post->message));
                    
                    $message = is_string($post->message) ? strip_tags(html_entity_decode($post->message)) : '';
                    
                    $messages[] = [
                        'id' => $post->id,
                        'discussion' => $post->discussion,
                        'parent' => $post->parent,
                        'userid' => $post->userid,
                        'userfullname' => fullname($user),
                        'created' => $post->created,
                        'modified' => $post->modified,
                        'mailed' => $post->mailed,
                        'subject' => $post->subject,
                        'message' => $message,
                        'wordcount' => $wordcount,
                    ];
                }
            }
            
            $csv_header = ['id', 'discussion', 'parent', 'userid', 'userfullname', 'created', 'modified', 'mailed', 'subject', 'message', 'wordcount'];

            $csv_rows = array_map(function ($message) {
                return implode(',', array_map('strval', $message));
            }, $messages);

            $csv_messages = implode("\n", array_merge(
                [implode(',', $csv_header)],
                $csv_rows                    
            ));

            try {
                $api_endpoint = "http://localhost:3000/analise";
                $post_data = [
                    'forumID' => $schedule->forumid,
                    'messages' => $csv_messages,
                ];

                $this->call_api($api_endpoint, $post_data);

                $recurrence = $schedule->recurrence;
                $next_run = $this->calculate_next_run($recurrence);
                $schedule->nextrun = $next_run;
                $DB->update_record('block_educolab_schedule', $schedule);

            } catch (\Exception $e) {
                mtrace("Error processing forum {$forumid}: " . $e->getMessage());
            }
        }
    }

    private function calculate_next_run($interval) {
        switch ($interval) {
            case 'daily':
                return strtotime('+1 day');
            case 'weekly':
                return strtotime('+1 week');
            case 'two_weeks':
                return strtotime('+2 weeks');
            case 'three_weeks':
                return strtotime('+3 weeks');
            case 'monthly':
                return strtotime('+1 month');
            default:
                throw new \moodle_exception('Invalid interval');
        }
    }

    private function call_api($endpoint, $data) {
        echo("Making API call to: $endpoint\n");

        $json_data = json_encode($data);
    
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
    
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ]);
    
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        }
        curl_close($ch);
    
        return $response;
    }
}