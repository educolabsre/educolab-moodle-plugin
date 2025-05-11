<?php
namespace block_educolab\external;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/externallib.php");

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

class save_recommendation extends external_api {
    public static function save_recommendation_parameters() {
        return new external_function_parameters([
            'forumid' => new external_value(PARAM_INT, 'Forum ID'),
            'confirmation_text' => new external_value(PARAM_TEXT, 'Recommendation text'),
        ]);
    }

    public static function save_recommendation($forumid, $confirmation_text) {
        global $DB;

        $params = self::validate_parameters(self::save_recommendation_parameters(), [
            'forumid' => $forumid,
            'confirmation_text' => $confirmation_text,
        ]);

        $recommendation_parameters = [
            'forumid' => $params['forumid'],
            'confirmation_text' => $params['confirmation_text'] == "" ? "Olá, ['Nome']!<br><br>Seu professor pediu para enviarmos recomendações a você e seus 
                    colegas a fim de promover a aprendizagem por meio do debate entre vocês no fórum ['identifica_fórum']. 
                    Para enviarmos essas recomendações é necessário que você nos permita fazer isso, clicando neste 
                    ['link'].<br><br>Em caso de recusa, 
                    você não será penalizado(a) de forma alguma. Se tiver alguma dúvida ou sugestão, você poderá entrar em contato 
                    com os desenvolvedores responsáveis através do e-mail educolabsre@gmail.com.<br><br>Vambora aprender colaborando com a sua turma 
                    e ainda participar desta oportunidade científica de aplicação da inteligência artificial na educação?" : $params['confirmation_text']
        ];

        $existing = $DB->get_record('block_educolab_recommendations', ['forumid' => $params['forumid']]);
        if ($existing) {
            $recommendation_parameters['id'] = $existing->id;
            $DB->update_record('block_educolab_recommendations', $recommendation_parameters);
        } else {
            $DB->insert_record('block_educolab_recommendations', $recommendation_parameters);
        }

        return ['status' => 'success', 'message' => 'Monitoring parameters saved successfully.'];
    }

    public static function save_recommendation_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status of the operation'),
            'message' => new external_value(PARAM_TEXT, 'Message from the operation'),
        ]);
    }
}