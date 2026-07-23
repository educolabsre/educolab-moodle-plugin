<?php
// This file is part of Moodle - https://moodle.org/
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
 * External function to save schedule for block_educolab.
 *
 * @package   block_educolab
 * @copyright 2024 Gabriel Lima <gabriel.lima6@estudante.ifb.edu.br>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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