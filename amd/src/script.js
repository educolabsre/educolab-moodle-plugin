define(['jquery', 'core/ajax'], function($, Ajax) {
    return {
        init: function() {
            const pages = {
                initialState: document.getElementById('page-initialState'),
                cadastro: document.getElementById('page-cadastro'),
                analise: document.getElementById('page-analise'),
                recorrencia: document.getElementById('page-recorrencia'),
                personalizar: document.getElementById('page-personalizar'),
            };

            let forumCadastrado = false;

            function switchPage(pageKey) {
                Object.values(pages).forEach(page => page.classList.remove('active'));
                pages[pageKey].classList.add('active');
            }

            const toastEl = document.getElementById("successToast");

            const toast = new bootstrap.Toast(toastEl, { autohide: false });

            function showToast(message, status) {
                const toastEl = document.getElementById("successToast");
                const toastHeader = document.querySelector(".toast-header");
                const toastHeaderTitle = document.getElementById("toast-header-title");
                const toastBody = document.querySelector(".toast-body");

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

                toastEl.classList.remove(status == "success" ? statusClasses.error.name : statusClasses.success.name);
                toastEl.classList.add(statusClasses?.[status].name);

                toastHeader.classList.remove(status == "success" ? statusClasses.error.header.name : statusClasses.success.header.name);
                toastHeader.classList.add(statusClasses?.[status].header.name);
                
                toastBody.innerHTML = message;
                toastHeaderTitle.innerHTML = status == "success" ? "Sucesso" : "Erro";

                toast.show();
            }

            $(document).ready(function () {
                const forumInfoElement = document.getElementById('forum-info');

                const url = 'http://localhost:3000/checar-cadastro';

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
                .then((data) => {
                    if(!!data) {
                        const firstOption = document.getElementById('monitoramento');
                        firstOption.innerHTML = "Editar monitoramento";

                        const buttonText = document.getElementById('button-monitoramento');
                        buttonText.innerHTML = "Editar monitoramento";
    
                        document.querySelectorAll('.list-group-item').forEach((item) => item.classList.remove('disabled'));

                        const firstDateInput = document.getElementById('start-date-input');
                        firstDateInput.classList.add('page');

                        forumCadastrado = true;

                    } else {
                        forumCadastrado = false;
                    }
                })
                .catch((err) => {
                    console.log(err);
                });

                $('.close').on('click', function() {
                    toast.hide();
                });

                $('#cadastro').on('click', function() {
                    switchPage('cadastro');

                    document.getElementById('back-arrow').classList.add('active');

                    $('#back-arrow').on('click', function() {
                        switchPage('initialState');
                        document.getElementById('back-arrow').classList.remove('active');
                    });

                    $('#btn-cadastro').on('click', function() {
                        const button = document.getElementById("btn-cadastro");

                        button.setAttribute('disabled', 'true');
                        button.querySelector('.spinner-border').classList.remove('d-none');

                        const forumInfoElement = document.getElementById('forum-info');

                        const forumId = forumInfoElement.dataset.forumid;

                        const startDate = document.getElementById("start-date").value;
                        const endDate = document.getElementById("end-date").value;

                        Ajax.call([{
                            methodname: 'block_educolab_save_monitoring_dates',
                            args: {
                                forumid: forumId,
                                start_date: startDate,
                                end_date: endDate
                            },
                            done: function() {

                            },
                            fail: function(error) {
                                
                            }
                        }]);

                        const url = forumCadastrado ? 'http://localhost:3000/atualizar-cadastro' : 'http://localhost:3000/cadastro';

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
                            if(!response.ok) {
                                throw new Error('Request error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');

                            if(data?.error) {
                                showToast(data?.error, "error");

                            } else if(data?.success) {
                                showToast(data?.success, "success");

                                document.querySelectorAll('.list-group-item').forEach((item) => item.classList.remove('disabled'));
                                document.getElementById('back-arrow').classList.remove('active');

                                const firstOption = document.getElementById('monitoramento');
                                firstOption.innerHTML = "Editar monitoramento";

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
                });

                $('#analise').on('click', function() {
                    switchPage('analise');

                    document.getElementById('back-arrow').classList.add('active');

                    $('#back-arrow').on('click', function() {
                        switchPage('initialState');
                        document.getElementById('back-arrow').classList.remove('active');
                    });

                    $('#btn-analise').on('click', function() {
                        const button = document.getElementById("btn-analise");

                        button.setAttribute('disabled', 'true');
                        button.querySelector('.spinner-border').classList.remove('d-none');
                        
                        const forumInfoElement = document.getElementById('forum-info');
    
                        const url = 'http://localhost:3000/analise';
    
                        const messages = window.educolab.messages;
    
                        const csv_header = ["id","discussion","parent","userid","userfullname","created","modified","mailed","subject","message","wordcount"];
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
                            if(!response.ok) {
                                throw new Error('Request error');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data);
                            
                            button.querySelector('.spinner-border').classList.add('d-none');
                            button.removeAttribute('disabled');
    
                            if(data?.error) {
                                showToast(data?.error, "error");
    
                            } else if(data?.success) {
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
                });

                $('#recorrencia').on('click', function() {
                    switchPage('recorrencia');

                    document.getElementById('back-arrow').classList.add('active');

                    $('#back-arrow').on('click', function() {
                        switchPage('initialState');
                        document.getElementById('back-arrow').classList.remove('active');
                    });
                });

                $('#personalizar').on('click', function() {
                    switchPage('personalizar');

                    document.getElementById('back-arrow').classList.add('active');

                    $('#back-arrow').on('click', function() {
                        switchPage('initialState');
                        document.getElementById('back-arrow').classList.remove('active');
                    });
                });

                $('#save-schedule-btn').on('click', function() {
                    const recurrence = $('#interval').val();
                    const startDate = $('#start_date').val();
    
                    const forumInfoElement = document.getElementById('forum-info');
    
                    const forumId = forumInfoElement.dataset.forumid;
                    const courseId = forumInfoElement.dataset.courseid;
    
                    if (!recurrence || !startDate) {
                        alert(Str.get_string('fillallfields', 'block_educolab'));
                        return;
                    }

                    const intervalsText = {
                        daily: "diariamente",
                        weekly: "semanalmente",
                        two_weeks: "a cada duas semanas",
                        three_weeks: "a cada três semanas",
                        monthly: "mensalmente"
                    }
    
                    Ajax.call([{
                        methodname: 'block_educolab_save_schedule',
                        args: {
                            forumId: forumId,
                            courseId: courseId,
                            recurrence: recurrence,
                            start_date: startDate
                        },
                        done: function() {
                            showToast(`O fórum será analisado ${intervalsText?.[recurrence]}, a partir de ${startDate.split('-').reverse().join('/')}.`, "success");
                            switchPage('initialState');
                            document.getElementById('back-arrow').classList.remove('active');
                        },
                        fail: function(error) {
                            showToast('Não foi possível agendar as análises, tente novamente mais tarde.')
                            console.log(error);
                        }
                    }]);
                });
            });
        }
    };
});