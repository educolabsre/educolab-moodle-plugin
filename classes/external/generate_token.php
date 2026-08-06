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
 * External function to generate token for block_educolab.
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

class generate_token extends external_api {
    public static function generate_token_parameters() {
        return new external_function_parameters([]);
    }

    public static function generate_token() {
        global $USER, $DB, $COURSE;

        // Validate parameters
        self::validate_parameters(self::generate_token_parameters(), []);

        // Require authentication - check that user is logged in
        if (!isloggedin() || isguestuser()) {
            throw new \invalid_parameter_exception('User must be authenticated');
        }

        // Determine user type based on capability (same logic as block interface)
        $context = \context_course::instance($COURSE->id);
        $is_teacher = has_capability('moodle/course:manageactivities', $context);
        $tipoUsuario = $is_teacher ? 'professor' : 'aluno';

        // Generate and save token
        try {
            $token = \block_educolab\external_db::generate_user_token($USER->email, $tipoUsuario);
            return [
                'status' => 'success',
                'token' => $token,
                'username' => fullname($USER),
                'message' => 'Token generated successfully.',
            ];
        } catch (\PDOException $e) {
            error_log('Token generation error for user ' . $USER->email . ': ' . $e->getMessage());
            return [
                'status' => 'error',
                'token' => null,
                'message' => 'Failed to generate token. Please try again.',
            ];
        } catch (\Exception $e) {
            error_log('Unexpected error in token generation for user ' . $USER->email . ': ' . $e->getMessage());
            return [
                'status' => 'error',
                'token' => null,
                'message' => 'An unexpected error occurred.',
            ];
        }
    }

    public static function generate_token_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'token' => new external_value(PARAM_TEXT, 'Generated token', VALUE_OPTIONAL),
            'username' => new external_value(PARAM_TEXT, 'Full name of the user', VALUE_OPTIONAL),
            'message' => new external_value(PARAM_TEXT, 'Message from the operation'),
        ]);
    }
}
