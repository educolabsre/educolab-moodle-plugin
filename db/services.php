<?php
$functions = [
    'block_educolab_save_schedule' => [
        'classname'   => 'block_educolab\external\save_schedule',
        'methodname'  => 'save_schedule',
        'classpath'   => '',
        'description' => 'Save the schedule for the course.',
        'type'        => 'write',
        'ajax'        => true,
    ],
    'block_educolab_save_monitoring_dates' => [
        'classname'   => 'block_educolab\external\save_monitoring_dates',
        'methodname'  => 'save_monitoring_dates',
        'classpath'   => '',
        'description' => 'Save the monitoring dates for the forum',
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
];
