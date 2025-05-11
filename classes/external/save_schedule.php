<?php
namespace block_educolab\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

class save_schedule extends external_api {
    public static function save_schedule_parameters() {
        return new external_function_parameters([
            'forumId' => new external_value(PARAM_INT, 'Forum ID'),
            'courseId' => new external_value(PARAM_INT, 'Course ID'),
            'recurrence' => new external_value(PARAM_TEXT, 'Schedule interval'),
            'start_date' => new external_value(PARAM_TEXT, 'Start date (YYYY-MM-DD)'),
        ]);
    }

    public static function save_schedule($forumid, $courseid, $recurrence, $start_date) {
        global $DB;

        $params = self::validate_parameters(self::save_schedule_parameters(), [
            'forumId' => $forumid,
            'courseId' => $courseid,
            'recurrence' => $recurrence,
            'start_date' => $start_date,
        ]);

        $schedule = [
            'forumid' => $params['forumId'],
            'courseid' => $params['courseId'],
            'recurrence' => $params['recurrence'],
            'timemodified' => time(),
            'nextrun' => strtotime($params['start_date']),
        ];

        $existing = $DB->get_record('block_educolab_schedule', ['forumid' => $params['forumId']]);
        if ($existing) {
            $schedule['id'] = $existing->id;
            $DB->update_record('block_educolab_schedule', $schedule);
        } else {
            $DB->insert_record('block_educolab_schedule', $schedule);
        }

        return ['status' => 'success', 'message' => 'Schedule saved successfully.'];
    }

    public static function save_schedule_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'message' => new external_value(PARAM_TEXT, 'Message from the operation'),
        ]);
    }
}