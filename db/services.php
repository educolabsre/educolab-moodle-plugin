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
 * External function to save recommendation text for block_educolab.
 *
 * @package   block_educolab
 * @copyright 2024 Gabriel Lima <gabriel.lima6@estudante.ifb.edu.br>
 * @license   https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$functions = [
    'block_educolab_save_schedule' => [
        'classname'   => 'block_educolab\external\save_schedule',
        'methodname'  => 'save_schedule',
        'classpath'   => '',
        'description' => 'Save the schedule for the course.',
        'type'        => 'write',
        'ajax'        => true,
    ],
    'block_educolab_save_recommendation' => [
        'classname'   => 'block_educolab\external\save_recommendation',
        'methodname'  => 'save_recommendation',
        'classpath'   => '',
        'description' => 'Save the recommendation text for the forum',
        'type'        => 'write',
        'ajax'        => true,
    ],
    'block_educolab_generate_token' => [
        'classname'   => 'block_educolab\external\generate_token',
        'methodname'  => 'generate_token',
        'classpath'   => '',
        'description' => 'Generate a token for accessing the recommendation panel',
        'type'        => 'write',
        'ajax'        => true,
    ],
    'block_educolab_update_consent' => [
        'classname'   => 'block_educolab\external\update_consent',
        'methodname'  => 'update_consent',
        'classpath'   => '',
        'description' => 'Update student consent for forum participation',
        'type'        => 'write',
        'ajax'        => true,
    ],
];
