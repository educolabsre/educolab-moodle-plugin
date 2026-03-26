<?php
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
