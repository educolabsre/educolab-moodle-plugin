<?php

/** Return the enrolled teachers allowed to manage forum discussions in a course. */
function block_educolab_get_teacher_emails(int $courseid): array {
    $context = context_course::instance($courseid);
    $teachers = get_enrolled_users($context, 'mod/forum:viewhiddentimedposts', 0,
        'u.id, u.email', null, 0, 0, true);
    return array_values(array_unique(array_filter(array_map(function($teacher) {
        return $teacher->email;
    }, $teachers))));
}

/** Shared default for initial invitations and legacy schedules without a configured block. */
function block_educolab_default_confirmation_text(): string {
    return "Olá, ['Nome']!<br><br>Seu professor pediu para enviarmos recomendações a você e seus
                    colegas a fim de promover a aprendizagem por meio do debate entre vocês no fórum ['identifica_forum'].
                    Para enviarmos essas recomendações, precisamos do seu consentimento. Para confirmar sua participação, siga os passos abaixo:<br><br>
                    <ol><li>Clique no link do ['link'] e faça login no Moodle, se necessário.</li>
                    <li>No curso, localize o bloco <strong>EduColab</strong> e clique em <strong>Atualizar consentimento</strong>.</li>
                    <li>Na tela de consentimento, clique em <strong>Consentir</strong> para confirmar sua participação no monitoramento deste fórum.</li></ol>
                    Em caso de recusa,
                    você não será penalizado(a) de forma alguma. Se tiver alguma dúvida ou sugestão, você poderá entrar em contato
                    com os desenvolvedores responsáveis através do e-mail educolabsre@gmail.com.<br><br>Vambora aprender colaborando com a sua turma
                    e ainda participar desta oportunidade científica de aplicação da inteligência artificial na educação?";
}

/** Load the same invitation configured in the block, including for scheduled runs. */
function block_educolab_get_confirmation_text(int $forumid, int $courseid): string {
    global $DB;
    $module = get_coursemodule_from_id('forum', $forumid, $courseid);
    if (!$module) {
        return '';
    }
    $contextids = context_module::instance($module->id)->get_parent_context_ids(true);
    list($insql, $params) = $DB->get_in_or_equal($contextids, SQL_PARAMS_NAMED);
    $params['blockname'] = 'educolab';
    $instances = $DB->get_records_select('block_instances',
        "blockname = :blockname AND parentcontextid $insql", $params, 'id DESC');
    // Prefer the forum block over its course/ancestor blocks; newest instance breaks ties.
    foreach ($contextids as $contextid) {
        foreach ($instances as $instance) {
            if ((int)$instance->parentcontextid !== (int)$contextid || empty($instance->configdata)) {
                continue;
            }
            $config = unserialize(base64_decode($instance->configdata), ['allowed_classes' => ['stdClass']]);
            if (is_object($config) && (int)($config->forumid ?? 0) === (int)$module->instance
                    && !empty($config->confirmation_text['text'])) {
                $format = $config->confirmation_text['format'] ?? FORMAT_HTML;
                return format_text($config->confirmation_text['text'], $format);
            }
        }
    }
    $saved = $DB->get_record('block_educolab_recommendations', ['forumid' => $forumid]);
    $text = !empty($saved->confirmation_text) ? $saved->confirmation_text : block_educolab_default_confirmation_text();
    return format_text($text, FORMAT_HTML);
}

function call_api($endpoint, $data) {
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);

    if ($error) {
        throw new \Exception("Curl error: $error");
    }

    return $response;
}
