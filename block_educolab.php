<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @package   block_educolab
 * @copyright 2024 Gabriel Lima <gabriel.lima6@estudante.ifb.edu.br>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_educolab extends block_base {
    function init() {
        $this->title = get_string('pluginname', 'block_educolab');
    }

    public function applicable_formats() {
        return ['all' => true];
    }

    public function instance_delete() {
        parent::instance_delete();
    }

    function get_content() {
        global $DB, $COURSE, $OUTPUT, $CFG, $PAGE;

        if ($this->content !== NULL) {
            return $this->content;
        }
        
        $this->content = new stdClass;

        if (empty($this->config->forumid)) {
            $this->content->text = "Por favor, selecione o fórum a ser monitorado nas configurações do bloco.";
            return $this->content;
        }

        $forum = $DB->get_record('forum', ['id' => $this->config->forumid]);
        $course_module = get_coursemodule_from_instance('forum', $forum->id, $COURSE->id);
        $forum_url = new moodle_url('mod/forum/view.php', ['id' => $course_module->id]);
        
        $context = context_course::instance($COURSE->id);
        
        $teacherRole = $DB->get_record('role', ['shortname' => 'editingteacher']);
        $teachers = get_role_users($teacherRole->id, $context, false, 'u.id, u.firstname, u.lastname, u.email');
        
        $firstTeacher = reset($teachers);
        
        $studentRole = $DB->get_record('role', ['shortname' => 'student'], 'id');
        $students = get_role_users($studentRole->id, $context);

        $students_data = [];

        foreach ($students as $student) {
            $students_data[] = [
                'firstname' => $student->firstname,
                'lastname' => $student->lastname,
                'email' => $student->email
            ];
        }

        $discussions = $DB->get_records('forum_discussions', ['forum' => $forum->id]);

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

        $forum_dates = $DB->get_record('block_educolab', ['forumid' => $course_module->id]);
        $forum_schedule = $DB->get_record('block_educolab_schedule', ['forumid' => $course_module->id]);
        $forum_recommendations = $DB->get_record('block_educolab_recommendations', ['forumid' => $course_module->id]);

        $confirmation_text = isset($this->config->confirmation_text['text']) ? $this->config->confirmation_text['text'] : '';
        $confirmation_text_format = isset($this->config->confirmation_text_format['format']) ? $this->config->confirmation_text_format['format'] : FORMAT_HTML;

        $rendered_text = format_text($confirmation_text, $confirmation_text_format);

        $plugin_context = [
            'identifica_forum' => $forum->name,
            'nome_professor' => $firstTeacher->firstname . ' ' . $firstTeacher->lastname,
            'email_professor' => $firstTeacher->email,
            'link_forum' => $CFG->wwwroot . '/' . $forum_url,
            'estudantes' => json_encode($students_data),
            'forumID' => $course_module->id,
            'courseID' => $COURSE->id,
            'messages' => json_encode($messages),
            'forum_end_date' => $forum_dates ? date('Y-m-d', $forum_dates->end_date) : "",
            'scheduled_start_date' => $forum_schedule ? date('Y-m-d', $forum_schedule->end_date) : "",
            'confirmation_text' => $rendered_text
        ];
        
        $this->content->text = $OUTPUT->render_from_template('block_educolab/index', $plugin_context);

        return $this->content;
    }
}
