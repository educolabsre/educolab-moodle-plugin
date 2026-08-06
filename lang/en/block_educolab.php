<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * English Plugin strings are defined here.
 *
 * @package     block_educolab
 * @category    string
 * @copyright   2024 Gabriel Lima <gabriel.lima6@estudante.ifb.edu.br>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'EduColab';
$string['schedule_analysis'] = 'Schedule Analysis Task';
$string['scheduleinterval'] = 'Schedule Interval';
$string['schedulesuccess'] = 'Schedule saved successfully.';
$string['scheduleerror'] = 'Error saving schedule.';
$string['fillallfields'] = 'Please fill all fields.';
$string['configureblock'] = 'Please configure this block to select an option.';
$string['selectedoption'] = 'You selected: {$a}';
$string['selectoption'] = 'Select an option';
$string['option1'] = 'Option 1';
$string['option2'] = 'Option 2';
$string['option3'] = 'Option 3';
$string['settings_api_url'] = 'EduColab API URL';
$string['settings_api_url_desc'] = 'The base URL of the EduColab analysis API (e.g. http://api.example.com:8000).';
$string['settings_streamlit_url'] = 'Streamlit App URL';
$string['settings_streamlit_url_desc'] = 'The base URL of the Streamlit recommendations app (e.g. https://educolab.streamlit.app).';
$string['please_select_forum'] = 'Please select the forum to monitor in the block settings.';
$string['no_forums_in_course'] = 'There are no forums in this course.';
$string['select_forum_to_monitor'] = 'Select the forum to monitor';
$string['confirmation_email'] = 'Confirmation email';
$string['start_monitoring'] = 'Start monitoring';
$string['edit_monitoring'] = 'Edit monitoring';
$string['analyze_forum'] = 'Analyze forum';
$string['request_immediate_analysis'] = 'Request immediate analysis';
$string['schedule_analyses'] = 'Schedule analyses';
$string['configure_automatic_analyses'] = 'Configure automatic analyses';
$string['customize'] = 'Customize';
$string['edit_recommendations'] = 'Edit recommendations';
$string['view_last_recommendation'] = 'View last recommendation';
$string['open_recommendations_panel'] = 'Open the recommendations panel';
$string['update_consent'] = 'Update consent';
$string['manage_monitoring_participation'] = 'Manage monitoring participation';
$string['not_enrolled_monitoring'] = 'You are not enrolled in monitoring for this forum.';
$string['monitoring_start'] = 'Monitoring start';
$string['monitoring_end'] = 'Monitoring end';
$string['request_analysis_question'] = 'Do you want to request an analysis of the forum?';
$string['request_analysis'] = 'Request analysis';
$string['configure_recurrence'] = 'Configure recurrence';
$string['daily'] = 'Daily';
$string['weekly'] = 'Weekly';
$string['two_weeks'] = 'Every two weeks';
$string['three_weeks'] = 'Every three weeks';
$string['monthly'] = 'Monthly';
$string['start_on'] = 'Start on';
$string['save'] = 'Save';
$string['customize_text_settings'] = 'You can customize the email text in settings.';
$string['confirmation_email_variables'] = 'For the confirmation email, the variables';
$string['student_name'] = 'Student name';
$string['forum_name'] = 'Forum name';
$string['confirmation_link'] = 'Confirmation link';
$string['available'] = 'are available.';
$string['participation_consent'] = 'Participation consent';
$string['consent_description'] = 'You may consent to or withdraw from participation in forum monitoring.';
$string['current_status'] = 'Current status';
$string['consented'] = 'Consented';
$string['not_consented'] = 'Not consented';
$string['consent'] = 'Consent';
$string['withdraw_consent'] = 'Withdraw consent';
$string['title'] = 'Title';
$string['message'] = 'Message';
$string['success'] = 'Success';
$string['error'] = 'Error';
$string['token_generation_error'] = 'Error generating access token.';
$string['token_generation_failed'] = 'Token generation failed. Please try again later.';
$string['consent_update_error'] = 'Error updating consent. Please try again.';
$string['student_first_name'] = 'First name';
$string['student_last_name'] = 'Last name';
$string['student_email'] = 'Email';
$string['forum_will_be_analyzed'] = 'The forum will be analyzed {$a->interval} starting on {$a->date}.';
$string['schedule_failed'] = 'Could not schedule analyses. Please try again later.';
