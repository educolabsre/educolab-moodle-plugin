# Plugin EduColab para o Moodle #

O **EduColab** é um plugin do tipo bloco para o Moodle que integra um Sistema de Recomendação Educacional (SRE), automatizando a análise de fóruns de discussão e promovendo a aprendizagem colaborativa. Com ele, professores podem monitorar a participação dos alunos e receber recomendações personalizadas que ajudam a estimular o engajamento nas discussões.

---

## 📚 Sumário

- [Funcionalidades](#funcionalidades)
  - [📥 Cadastro de Fóruns](#-cadastro-de-fóruns)
  - [📊 Análise de Fóruns](#-análise-de-fóruns)
  - [🔁 Agendamento de Análises Automáticas](#-agendamento-de-análises-automáticas)
  - [✏️ Personalização de Recomendações](#%EF%B8%8F-personalização-de-recomendações)
  - [🔧 Integração Técnica](#-integração-técnica)
- [📦 Instalação via arquivo ZIP (upload manual)](#-instalação-via-arquivo-zip-upload-manual)
- [🛠️ Instalação manual (diretório do servidor)](#️-instalação-manual-diretório-do-servidor)
- [📚 Produções Relacionadas](#-produções-relacionadas)
- [📄 Licença](#-licença)

---

## Funcionalidades

### 📥 Cadastro de Fóruns
- Seleção de fóruns existentes no curso.
- Definição do período de monitoramento.
- Solicitação automática de consentimento dos alunos por e-mail.

![Tela de cadastro do fórum](./assets/images/plugin_3.png)

### 📊 Análise de Fóruns
- Execução manual ou automática de análises das mensagens postadas.
- Geração de índices de colaboração com base em técnicas de análise conversacional.
- Envio automático de recomendações por e-mail a alunos e professores.

![Tela de análise do fórum](./assets/images/plugin_4.png)

### 🔁 Agendamento de Análises Automáticas
- Permite definir a frequência das análises (diária, semanal, quinzenal, etc.).
- Utiliza tarefas programadas no Moodle para executar as análises nas datas agendadas.

![Tela de agendamento de análises](./assets/images/plugin_5.png)

### ✏️ Personalização de Recomendações
- Edição do conteúdo dos e-mails de confirmação enviados aos estudantes.
- Uso de variáveis dinâmicas no corpo do e-mail para personalização das mensagens.

![Tela de personalização de recomendações](./assets/images/plugin_6.png)

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

---

## 📚 Produções Relacionadas

### 📄 Artigos e Trabalhos Acadêmicos

- **[Aprendizado de Máquina em Análise Conversacional para Recomendar a Colaboração em Fóruns de Discussão](https://sol.sbc.org.br/index.php/sbie/article/view/31366)**  
  *Publicado nos Anais do Simpósio Brasileiro de Informática na Educação (SBIE 2024)*  
  Autores: MORAES NETO, Antônio J.; VASCONCELOS, Raimundo C. S.; LIMA, Gabriel J. C.; FERNANDES, Márcia A.; AMIEL, Tel.
  
  [🔗 Acesso direto ao artigo (PDF)](https://sol.sbc.org.br/index.php/sbie/article/view/31366/31169)

- **[EduColab: Sistema de recomendação educacional para promover a colaboração em fóruns de discussão](https://repositorio.ufu.br/handle/123456789/43669)**<br>
  *Tese (Doutorado em Ciência da Computação​) - Universidade Federal de Uberlândia, Uberlândia, 2024.*  
  Autor: MORAES NETO, Antônio Justiniano de.

  [🔗 Acesso direto à tese (PDF)](https://repositorio.ufu.br/bitstream/123456789/43669/3/EduColabSistema.pdf)

## 📄 Licença

© 2025 Antônio Justiniano de Moraes Neto, <antonio.neto@ifb.edu.br>; Gabriel Lima, <gabriel.lima6@estudante.ifb.edu.br>.

Este programa é um software livre: você pode redistribuí-lo e/ou modificá-lo sob os termos da Licença Pública Geral GNU, conforme publicada pela Free Software Foundation, na versão 3 da Licença ou (a seu critério) qualquer versão posterior.

Este programa é distribuído na esperança de que seja útil, mas **SEM NENHUMA GARANTIA**, sem mesmo a garantia implícita de **COMERCIALIZAÇÃO** ou de **ADEQUAÇÃO A UM PROPÓSITO ESPECÍFICO**. Veja a Licença Pública Geral GNU para mais detalhes.

Você deve ter recebido uma cópia da Licença Pública Geral GNU junto com este programa. Caso contrário, consulte: [https://www.gnu.org/licenses/](https://www.gnu.org/licenses/)
