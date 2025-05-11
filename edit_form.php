<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/blocks/edit_form.php');

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
            'text' => "Olá, ['Nome']!<br><br>Seu professor pediu para enviarmos recomendações a você e seus 
                    colegas a fim de promover a aprendizagem por meio do debate entre vocês no fórum ['identifica_forum']. 
                    Para enviarmos essas recomendações é necessário que você nos permita fazer isso, clicando neste 
                    ['link'].<br><br>Em caso de recusa, 
                    você não será penalizado(a) de forma alguma. Se tiver alguma dúvida ou sugestão, você poderá entrar em contato 
                    com os desenvolvedores responsáveis através do e-mail educolabsre@gmail.com.<br><br>Vambora aprender colaborando com a sua turma 
                    e ainda participar desta oportunidade científica de aplicação da inteligência artificial na educação?",
            'format' => FORMAT_HTML
        ];
        $mform->setDefault('config_confirmation_text', $defaulttext);
    }
}