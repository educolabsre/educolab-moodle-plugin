<?php
namespace block_educolab\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

class save_monitoring_dates extends external_api {
    public static function save_monitoring_dates_parameters() {
        return new external_function_parameters([
            'forumid' => new external_value(PARAM_INT, 'Forum ID'),
            'start_date' => new external_value(PARAM_TEXT, 'Start date (YYYY-MM-DD)'),
            'end_date' => new external_value(PARAM_TEXT, 'End date (YYYY-MM-DD)'),
        ]);
    }

    public static function save_monitoring_dates($forumid, $start_date, $end_date) {
        global $DB;

        $params = self::validate_parameters(self::save_monitoring_dates_parameters(), [
            'forumid' => $forumid,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ]);

        $monitoring_parameters = [
            'forumid' => $params['forumid'],
            'start_date' => strtotime($params['start_date']),
            'end_date' => strtotime($params['end_date']),
        ];

        $existing = $DB->get_record('block_educolab', ['forumid' => $params['forumid']]);
        if ($existing) {
            $monitoring_parameters['id'] = $existing->id;
            $DB->update_record('block_educolab', $monitoring_parameters);
        } else {
            $DB->insert_record('block_educolab', $monitoring_parameters);
        }

        return ['status' => 'success', 'message' => 'Monitoring parameters saved successfully.'];
    }

    public static function save_monitoring_dates_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'message' => new external_value(PARAM_TEXT, 'Message from the operation'),
        ]);
    }
}