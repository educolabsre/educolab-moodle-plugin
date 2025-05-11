<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Function to execute on plugin installation.
 */
function xmldb_block_educolab_install() {
    global $DB;

    // Ensure tasks are registered during installation.
    require_once(__DIR__ . '/tasks.php');
}