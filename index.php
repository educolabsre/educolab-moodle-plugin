<?php
require_once('../../config.php');

$action = required_param('action', PARAM_ALPHA);

if ($action === 'schedule') {
    $start_date = "2025-01-02";
    $interval = 1;

    $start_timestamp = strtotime($start_date);

    $task = \core\task\manager::get_scheduled_task('block_educolab\task\schedule_analysis');
    if (!$task) {
        throw new \moodle_exception('Task not found.');
    }
    
    $task->set_next_run_time($start_timestamp);
    \core\task\manager::save_scheduled_task($task);

    echo "Task scheduled successfully starting from $start_date every $interval minutes.";
}