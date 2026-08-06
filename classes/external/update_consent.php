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
 * External function to update consent for block_educolab.
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

class update_consent extends external_api {
    public static function update_consent_parameters() {
        return new external_function_parameters([
            'forumId' => new external_value(PARAM_TEXT, 'Forum ID'),
            'consent' => new external_value(PARAM_INT, '1 to consent, 0 to withdraw'),
        ]);
    }

    public static function update_consent($forumid, $consent) {
        global $USER;

        $params = self::validate_parameters(self::update_consent_parameters(), [
            'forumId' => $forumid,
            'consent' => $consent,
        ]);

        if (!isloggedin() || isguestuser()) {
            throw new \invalid_parameter_exception('User must be authenticated');
        }

        $consent_value = (int) $params['consent'];
        if ($consent_value !== 0 && $consent_value !== 1) {
            throw new \invalid_parameter_exception('Consent must be 0 or 1');
        }

        try {
            $updated = \block_educolab\external_db::update_student_consent(
                $USER->email,
                $params['forumId'],
                $consent_value
            );

            if ($updated) {
                return [
                    'status' => 'success',
                    'message' => $consent_value
                        ? 'Consentimento registrado com sucesso.'
                        : 'Consentimento removido com sucesso.',
                    'consent' => $consent_value,
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Registro não encontrado. Verifique se você está inscrito neste fórum.',
                    'consent' => -1,
                ];
            }
        } catch (\PDOException $e) {
            error_log('Consent update error for user ' . $USER->email . ': ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Erro ao atualizar consentimento. Tente novamente mais tarde.',
                'consent' => -1,
            ];
        }
    }

    public static function update_consent_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'message' => new external_value(PARAM_TEXT, 'Result message'),
            'consent' => new external_value(PARAM_INT, 'Current consent value'),
        ]);
    }
}
