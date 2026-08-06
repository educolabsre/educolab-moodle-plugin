define(['jquery', 'core/ajax', 'core/str'], function ($, Ajax, Str) {
    let initialized = false;
    return {
        init: function () {
            if (initialized) {
                return;
            }
            initialized = true;

            let forumCadastrado = false;

            async function getBlockString(key, fallback) {
                try {
                    return await Str.get_string(key, 'block_educolab');
                } catch (error) {
                    console.warn(error);
                    return fallback || key;
                }
            }

            async function showToast(message, status) {
                const toastEl = document.getElementById("successToast");
                if (!toastEl) return; // Guard against missing element

                const toastHeader = document.querySelector(".toast-header");
                const toastHeaderTitle = document.getElementById("toast-header-title");
                const toastBody = document.querySelector(".toast-body");

                if (!toastHeader || !toastHeaderTitle || !toastBody) return; // Guard against missing elements

                const statusClasses = {
                    success: {
                        name: 'toast-success',
                        header: { name: 'toast-header-success' },
                    },
                    error: {
                        name: 'toast-error',
                        header: { name: 'toast-header-error' },
                    },
                };

                const title = await getBlockString(status === "success" ? "success" : "error", status === "success" ? "Success" : "Error");

                toastEl.classList.remove(status == "success" ? statusClasses.error.name : statusClasses.success.name);
                toastEl.classList.add(statusClasses?.[status].name);

                toastHeader.classList.remove(status == "success" ? statusClasses.error.header.name : statusClasses.success.header.name);
                toastHeader.classList.add(statusClasses?.[status].header.name);

                toastBody.innerHTML = message;
                toastHeaderTitle.innerHTML = title;

                // Show the toast
                toastEl.classList.add('show');
                toastEl.style.display = 'block';

                // Hide after 5 seconds
                setTimeout(() => {
                    toastEl.classList.remove('show');
                    toastEl.style.display = 'none';
                }, 5000);
            }

            $(document).ready(function () {
                const pages = {
                    initialState: document.getElementById('page-initialState'),
                    cadastro: document.getElementById('page-cadastro'),
                    analise: document.getElementById('page-analise'),
                    recorrencia: document.getElementById('page-recorrencia'),
                    personalizar: document.getElementById('page-personalizar'),
                    consentimento: document.getElementById('page-consentimento'),
                };

                function switchPage(pageKey) {
                    Object.values(pages).forEach(page => page.classList.remove('active'));
                    pages[pageKey].classList.add('active');
                }

                const forumInfoElement = document.getElementById('forum-info');
                const apiBaseUrl = forumInfoElement.dataset.apibaseurl;

                const url = apiBaseUrl + '/checar-cadastro';

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        forumID: forumInfoElement.dataset.forumid
                    })
                })
                    .then((response) => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        return response.json();
                    })
                    .then(async (data) => {
                        if (data) {
                            const text = await Str.get_string(
                                'edit_monitoring',
                                'block_educolab'
                            );

                            document.getElementById('monitoramento').innerHTML = text;
                            document.getElementById('button-monitoramento').innerHTML = text;

                            document.querySelectorAll('.list-group-item')
                                .forEach((item) => item.classList.remove('disabled'));

                            document.getElementById('start-date-input')
                                .classList.add('page');

                            forumCadastrado = true;
                        } else {
                            forumCadastrado = false;
                        }
                    })
                    .catch((err) => {
                        console.log(err);
                    });

                $('.close').on('click', function () {
                    const toastEl = document.getElementById("successToast");
                    toastEl.classList.remove('show');
                    toastEl.style.display = 'none';
                });

                $('#cadastro').on('click', function () {
                    switchPage('cadastro');
                    document.getElementById('back-arrow').classList.add('active');
                });

                $('#btn-cadastro').on('click', function () {
                    const button = document.getElementById("btn-cadastro");

                    button.setAttribute('disabled', 'true');
                    button.querySelector('.spinner-border').classList.remove('d-none');

                    const forumInfoElement = document.getElementById('forum-info');

                    const forumId = forumInfoElement.dataset.forumid;

                    const startDate = document.getElementById("start-date").value;
                    const endDate = document.getElementById("end-date").value;

                    const url = forumCadastrado ? apiBaseUrl + '/atualizar-cadastro' : apiBaseUrl + '/cadastro';

                    const students = window.educolab.students;

                    const csv_header = ["Nome", "Sobrenome", "Email"];
                    const csv_rows = students.map(student => [student.id, student.firstname, student.lastname, student.email]);

                    const csv_students = [
                        csv_header.join(','),
                        ...csv_rows.map(row => row.join(','))
                    ].join('\n');

                    const reqBody = forumCadastrado ? JSON.stringify({
                        forumID: forumId,
                        data_final: endDate

                    }) : JSON.stringify({
                        identifica_forum: forumInfoElement.dataset.identificaforum,
                        nome_professor: forumInfoElement.dataset.nomeprofessor,
                        email_professor: forumInfoElement.dataset.emailprofessor,
                        link_forum: forumInfoElement.dataset.linkforum,
                        data_inicio: startDate,
                        data_final: endDate,
                        estudantes: csv_students,
                        confirmacao: forumInfoElement.dataset.confirmacao
                    });

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: reqBody
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Request error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');

                            if (data?.error) {
                                showToast(data?.error, "error");

                            } else if (data?.success) {
                                showToast(data?.success, "success");

                                document.querySelectorAll('.list-group-item').forEach((item) => item.classList.remove('disabled'));
                                document.getElementById('back-arrow').classList.remove('active');

                                Str.get_string('edit_monitoring', 'block_educolab')
                                    .then(function (text) {
                                        document.getElementById('monitoramento').innerHTML = text;
                                        document.getElementById('button-monitoramento').innerHTML = text;
                                    });

                                switchPage('initialState');
                            }
                        })
                        .catch(error => {
                            console.error('Error', error);
                        })
                        .finally(() => {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');
                        });
                });

                $('#analise').on('click', function () {
                    switchPage('analise');
                    document.getElementById('back-arrow').classList.add('active');
                });

                $('#btn-analise').on('click', function () {
                    const button = document.getElementById("btn-analise");

                    button.setAttribute('disabled', 'true');
                    button.querySelector('.spinner-border').classList.remove('d-none');

                    const forumInfoElement = document.getElementById('forum-info');

                    const url = apiBaseUrl + '/analise';

                    const messages = window.educolab.messages;

                    const csv_header = ["id", "discussion", "parent", "userid", "userfullname", "created", "modified", "mailed", "subject", "message", "wordcount"];
                    const csv_rows = messages.map(message => [message.id, message.discussion, message.parent, message.userid, message.userfullname, message.created, message.modified, message.mailed, message.subject, message.message, message.wordcount]);

                    const csv_messages = [
                        csv_header.join(','),
                        ...csv_rows.map(row => row.join(','))
                    ].join('\n');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            forumID: forumInfoElement.dataset.forumid,
                            messages: csv_messages
                        })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Request error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);

                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');

                            if (data?.error) {
                                showToast(data?.error, "error");

                            } else if (data?.success) {
                                showToast(data?.success, "success");

                                document.getElementById('back-arrow').classList.remove('active');

                                switchPage('initialState');
                            }
                        })
                        .catch(error => {
                            console.error('Error', error);
                        })
                        .finally(() => {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');
                        });
                });

                $('#recorrencia').on('click', function () {
                    switchPage('recorrencia');
                    document.getElementById('back-arrow').classList.add('active');
                });

                $('#personalizar').on('click', function () {
                    switchPage('personalizar');
                    document.getElementById('back-arrow').classList.add('active');
                });

                $('#ver-recomendacao').on('click', function () {
                    Ajax.call([{
                        methodname: 'block_educolab_generate_token',
                        args: {},
                        done: async function (result) {
                            if (result.status === 'success' && result.token) {
                                var token = result.token;
                                var username = result.username || '';
                                var streamlitUrl = forumInfoElement.dataset.streamliturl || 'https://educolab.streamlit.app';
                                var url = streamlitUrl + '/?moodle_token=' + encodeURIComponent(token)
                                    + '&username=' + encodeURIComponent(username);
                                window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
                            } else {
                                showToast(await getBlockString('token_generation_error', 'Error generating access token.'), 'error');
                            }
                        },
                        fail: async function (error) {
                            showToast(await getBlockString('token_generation_failed', 'Token generation failed. Please try again later.'), 'error');
                            console.error('Token generation error:', error);
                        }
                    }]);
                });

                // Student: View last recommendation (same behavior as teacher, with forumId)
                $('#ver-recomendacao-aluno').on('click', function () {
                    var forumInfoElement = document.getElementById('forum-info');
                    var forumId = forumInfoElement.dataset.forumid;

                    Ajax.call([{
                        methodname: 'block_educolab_generate_token',
                        args: {},
                        done: async function (result) {
                            if (result.status === 'success' && result.token) {
                                var token = result.token;
                                var username = result.username || '';
                                var streamlitUrl = forumInfoElement.dataset.streamliturl || 'https://educolab.streamlit.app';
                                var url = streamlitUrl + '/?moodle_token=' + encodeURIComponent(token)
                                    + '&forum_id=' + encodeURIComponent(forumId)
                                    + '&username=' + encodeURIComponent(username);
                                window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes');
                            } else {
                                showToast(await getBlockString('token_generation_error', 'Error generating access token.'), 'error');
                            }
                        },
                        fail: async function (error) {
                            showToast(await getBlockString('token_generation_failed', 'Token generation failed. Please try again later.'), 'error');
                            console.error('Token generation error:', error);
                        }
                    }]);
                });

                // Student: Open consent page
                $('#atualizar-consentimento').on('click', function () {
                    switchPage('consentimento');
                    document.getElementById('back-arrow').classList.add('active');
                });

                // Student: Consent button
                $('#btn-consentir').on('click', function () {
                    var button = document.getElementById('btn-consentir');
                    button.setAttribute('disabled', 'true');
                    button.querySelector('.spinner-border').classList.remove('d-none');

                    var forumInfoElement = document.getElementById('forum-info');
                    var forumId = forumInfoElement.dataset.forumid;

                    Ajax.call([{
                        methodname: 'block_educolab_update_consent',
                        args: { forumId: forumId, consent: 1 },
                        done: async function (result) {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');

                            if (result.status === 'success') {
                                showToast(result.message, 'success');
                                updateConsentBadge(true);
                            } else {
                                showToast(result.message, 'error');
                            }
                        },
                        fail: async function (error) {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');
                            showToast(await getBlockString('consent_update_error', 'Error updating consent. Please try again.'), 'error');
                            console.error('Consent update error:', error);
                        }
                    }]);
                });

                // Student: Withdraw consent button
                $('#btn-nao-consentir').on('click', function () {
                    var button = document.getElementById('btn-nao-consentir');
                    button.setAttribute('disabled', 'true');
                    button.querySelector('.spinner-border').classList.remove('d-none');

                    var forumInfoElement = document.getElementById('forum-info');
                    var forumId = forumInfoElement.dataset.forumid;

                    Ajax.call([{
                        methodname: 'block_educolab_update_consent',
                        args: { forumId: forumId, consent: 0 },
                        done: function (result) {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');

                            if (result.status === 'success') {
                                showToast(result.message, 'success');
                                updateConsentBadge(false);
                            } else {
                                showToast(result.message, 'error');
                            }
                        },
                        fail: function (error) {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');
                            showToast('Erro ao atualizar consentimento. Tente novamente.', 'error');
                            console.error('Consent update error:', error);
                        }
                    }]);
                });

                async function updateConsentBadge(consented) {
                    var badge = document.querySelector('#consent-status .badge');
                    if (badge) {
                        const currentStatusLabel = await getBlockString('current_status', 'Current status');
                        const consentedLabel = await getBlockString(consented ? 'consented' : 'not_consented', consented ? 'Consented' : 'Not consented');
                        badge.className = 'badge ' + (consented ? 'badge-success' : 'badge-secondary');
                        badge.textContent = currentStatusLabel + ': ' + consentedLabel;
                    }
                }

                $('#back-arrow').on('click', function () {
                    switchPage('initialState');
                    document.getElementById('back-arrow').classList.remove('active');
                });

                $('#save-schedule-btn').on('click', async function () {
                    const recurrence = $('#interval').val();
                    const startDate = $('#start_date').val();

                    const forumInfoElement = document.getElementById('forum-info');

                    const forumId = forumInfoElement.dataset.forumid;
                    const courseId = forumInfoElement.dataset.courseid;

                    if (!recurrence || !startDate) {
                        showToast(await getBlockString('fillallfields', 'Please fill all fields.'), 'error');
                        return;
                    }

                    const intervalKey = {
                        daily: 'daily',
                        weekly: 'weekly',
                        two_weeks: 'two_weeks',
                        three_weeks: 'three_weeks',
                        monthly: 'monthly'
                    }[recurrence] || 'daily';

                    const intervalLabel = await getBlockString(intervalKey, recurrence);
                    const formattedDate = startDate.split('-').reverse().join('/');
                    const scheduleMessageTemplate = await getBlockString('forum_will_be_analyzed', 'The forum will be analyzed {$a->interval} starting on {$a->date}.');
                    const scheduleMessage = scheduleMessageTemplate
                        .replace('{$a->interval}', intervalLabel)
                        .replace('{$a->date}', formattedDate);

                    Ajax.call([{
                        methodname: 'block_educolab_save_schedule',
                        args: {
                            forumId: forumId,
                            courseId: courseId,
                            recurrence: recurrence,
                            start_date: startDate
                        },
                        done: function () {
                            showToast(scheduleMessage, "success");
                            switchPage('initialState');
                            document.getElementById('back-arrow').classList.remove('active');
                        },
                        fail: async function (error) {
                            showToast(await getBlockString('schedule_failed', 'Could not schedule analyses. Please try again later.'), 'error');
                            console.log(error);
                        }
                    }]);
                });
            });
        }
    };
});