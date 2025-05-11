<?php
defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'block_educolab\task\schedule_analysis',
        'blocking' => 0,
        'minute' => '*',
        'hour' => '*',
        'day' => '*',
        'dayofweek' => '*',
        'month' => '*',
    ],
];