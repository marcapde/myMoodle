<?php
//

/**
 * Definition of log events
 *
 * @package    local_message
 * @author     Marc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/classes/form/edit.php');

global $DB;

$PAGE->set_url(new moodle_url('/local/message/edit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Edit');


// we want to display our form
// $mform = new tool_licensemanager\form\edit_license()
$mform = new edit();


//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    //go back to manage page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('cancelled_form', 'local_message'));

} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.

  //insert data to database

  $recordtoinsert = new stdClass();
  $recordtoinsert->messagetext = $fromform->messagetext;
  $recordtoinsert->messagetype = $fromform->messagetype;

  $DB->insert_record('local_message', $recordtoinsert);
  redirect($CFG->wwwroot . '/local/message/manage.php', get_string('created_message', 'local_message') . $fromform->messagetext . ' and type ' . $fromform->messagetype);

}
echo $OUTPUT->header();

// $templatecontext = (object)[
//     'texttodisplay' => 'List of all current messages',
// ];
// echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

$mform->display();

echo $OUTPUT->footer();
