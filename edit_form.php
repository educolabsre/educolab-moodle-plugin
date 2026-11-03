<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/edit_form.php');
require_once(__DIR__ . '/lib.php');

class block_educolab_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $DB, $COURSE;

        $forums = $DB->get_records('forum', ['course' => $COURSE->id]);

        $options = [];
        foreach ($forums as $forum) {
            $options[$forum->id] = format_string($forum->name);
        }

        if (empty($options)) {
            $mform->addElement('static', 'no_forums', '', "Não há fóruns nesse curso.");

        } else {
            $mform->addElement('select', 'config_forumid', "Selecione o fórum a ser monitorado", $options);
            $mform->setType('config_forumid', PARAM_INT);
            $mform->addRule('config_forumid', null, 'required', null, 'client');
        }

        $mform->addElement('editor', 'config_confirmation_text', "Email de confirmação", null, null);
        $mform->addRule('config_confirmation_text', null, 'required', null, 'client');

        $defaulttext = [
            'text' => block_educolab_default_confirmation_text(),
            'format' => FORMAT_HTML
        ];
        $mform->setDefault('config_confirmation_text', $defaulttext);
    }
}
