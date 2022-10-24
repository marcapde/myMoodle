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
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_greetings
 * @copyright   2022 Marc Marc@Capde.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require_once($CFG->dirroot. '/local/greetings/lib.php');
require_once($CFG->dirroot. '/local/greetings/message_form.php');
// Import cuntry function.

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

require_login();
if (isguestuser()) {
    throw new moodle_exception('noguest');
}
// To check if a user has the capability to post.
$allowpost = has_capability('local/greetings:postmessages', $context);
$allowread = has_capability('local/greetings:viewmessages', $context);
$deletepost = has_capability('local/greetings:deleteownmessage', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);
$editpost = has_capability('local/greetings:editownmessage', $context);
$editanypost = has_capability('local/greetings:editanymessage', $context);

$action = optional_param('action', '', PARAM_TEXT);

if ($action == 'del') {
    require_sesskey();
    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost || $deletepost) {
        $params = array('id' => $id);

        $DB->delete_records('local_greetings_messages', $params);
    }
}

$messageform = new local_greetings_message_form();


if ($action == 'edit'){
    require_sesskey();
    $id = required_param('id', PARAM_TEXT);
    $userid = required_param('usrid', PARAM_TEXT);
    if ($editanypost || $editpost){
        $dataobject->id = $id;
        $dataobject->userid = $USER->id;
        $dataobject->timecreated = time();
        $dataobject->message = 'this has been updated successfully';
        $DB -> update_record('local_greetings_messages', $dataobject, $bulk=false);
    }
}
if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);
    require_sesskey();

    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);
    }
}
echo $OUTPUT->header();

if (isloggedin()) {
    echo local_greetings_get_greeting($USER);
} else {
    echo get_string('greetinguser', 'local_greetings');
}
// Display only only if having permisions.
if ($allowpost) {
    $messageform->display();
}
if ($allowread){
    $userfields = \core_user\fields::for_name()->with_identity($context);
    $userfieldssql = $userfields->get_sql('u');

    $sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
            FROM {local_greetings_messages} m
        LEFT JOIN {user} u ON u.id = m.userid
        ORDER BY timecreated DESC";
        $messages = $DB->get_records_sql($sql);echo $OUTPUT->box_start('card-columns');

    foreach ($messages as $m) {
        echo html_writer::start_tag('div', array('class' => 'card'));
        echo html_writer::start_tag('div', array('class' => 'card-body'));
        // echo html_writer::tag('p', $m->message, array('class' => 'card-text')); without sanitasing
        echo html_writer::tag('p', format_text($m->message, FORMAT_PLAIN), array('class' => 'card-text')); // Sanitized.
        echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), array('class' => 'card-text'));
        echo html_writer::start_tag('p', array('class' => 'card-text'));
        echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
        echo html_writer::end_tag('p');
        if ($editanypost || ($editpost && $m->userid == $USER->id)){
            echo html_writer::start_tag('p', array('class' => 'card-footer text-center'));
            echo html_writer::link(
                new moodle_url(
                    '/local/greetings/index.php',
                    array('action' => 'edit', 'id' => $m->id, 'usrid' => $m->userid, 'sesskey' => sesskey())
                ),
                $OUTPUT->pix_icon('t/edit', '') ,
                array('role' => 'button', 'aria-label' => get_string('edit'), 'title' => get_string('edit'))
            );
            echo html_writer::end_tag('p');
        }        
        if ($deleteanypost || ($deletepost && $m->userid == $USER->id)) {
            echo html_writer::start_tag('p', array('class' => 'card-footer text-center'));
            echo html_writer::link(
                new moodle_url(
                    '/local/greetings/index.php',
                    array('action' => 'del', 'id' => $m->id,'sesskey' => sesskey())
                ),
                $OUTPUT->pix_icon('t/delete', '') ,
                array('role' => 'button', 'aria-label' => get_string('delete'), 'title' => get_string('delete'))
            );
            echo html_writer::end_tag('p');
        }
        echo html_writer::end_tag('div');
        echo html_writer::end_tag('div');
    }
}


echo $OUTPUT->box_end();

// Per depurar, et mostra per pantalla que ha escrit el user -> var_dump($data); .


echo $OUTPUT->footer();
