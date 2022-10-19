<?php

//

/**
 * Definition of log events
 *
 * @package    local_message
 * @author     Marc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//moodleform is defined in formslib.php

require_once("$CFG->libdir/formslib.php");

class edit extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
       
        $mform = $this->_form; // Don't forget the underscore! 

        $mform->addElement('text', 'messagetext', get_string('message_text', 'local_message')); // Add elements to your form.
        $mform->setType('messagetext', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('messagetext', get_string('enter_message', 'local_message'));        // Default value.


        $options = array();
        $options['0'] = \core\output\notification::NOTIFY_SUCCESS;
        $options['1'] = \core\output\notification::NOTIFY_ERROR;
        $options['2'] = \core\output\notification::NOTIFY_INFO;
        $options['3'] = \core\output\notification::NOTIFY_WARNING;
        $mform->addElement('select', 'messagetype', get_string('message_type', 'local_message'), $options); // Add elements to your form.
        // $mform->setType('messagetype', PARAM_NOTAGS);                   // Set type of element.
        $mform->setDefault('messagetype', '2');        // Default value.


        $this->add_action_buttons();
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}