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
