# Plugin EduColab para o Moodle #

O **EduColab** é um plugin do tipo bloco para o Moodle que integra um Sistema de Recomendação Educacional (SRE), automatizando a análise de fóruns de discussão e promovendo a aprendizagem colaborativa. Com ele, professores podem monitorar a participação dos alunos e receber recomendações personalizadas que ajudam a estimular o engajamento nas discussões.

---

## Funcionalidades

### 📥 Cadastro de Fóruns
- Seleção de fóruns existentes no curso.
- Definição do período de monitoramento.
- Solicitação automática de consentimento dos alunos por e-mail.

### 📊 Análise de Fóruns
- Execução manual ou automática de análises das mensagens postadas.
- Geração de índices de colaboração com base em técnicas de análise conversacional.
- Envio automático de recomendações por e-mail a alunos e professores.

### 🔁 Agendamento de Análises Automáticas
- Permite definir a frequência das análises (diária, semanal, quinzenal, etc.).
- Utiliza tarefas programadas no Moodle para executar as análises nas datas agendadas.

### ✏️ Personalização de Recomendações
- Edição do conteúdo dos e-mails de confirmação enviados aos estudantes.
- Uso de variáveis dinâmicas no corpo do e-mail para personalização das mensagens.

### 🔧 Integração Técnica
- Desenvolvido em **PHP** como bloco do Moodle.
- Comunicação com o SRE (implementado em **Python**) por meio de uma **API REST** escrita em **Node.js + Express**.

---

## 📦 Instalação via arquivo ZIP (upload manual)

1. Acesse sua instalação do Moodle como administrador e vá até _Administração do site > Plugins > Instalar plugins_.
2. Envie o arquivo ZIP com o código do plugin. Você só será solicitado a preencher informações adicionais se o tipo de plugin não for detectado automaticamente.
3. Verifique o relatório de validação do plugin e finalize a instalação.

## 🛠️ Instalação manual (diretório do servidor)

O plugin também pode ser instalado manualmente copiando o conteúdo deste diretório para:

    {seu/moodle/dirroot}/blocks/educolab

Depois disso, acesse o Moodle como administrador e vá até _Administração do site > Notificações_ para concluir a instalação.

Alternativamente, você pode executar o seguinte comando no terminal:

    $ php admin/cli/upgrade.php

para concluir a instalação via linha de comando.

## License ##

2024 Gabriel Lima <gabriel.lima6@estudante.ifb.edu.br>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
