<?php
//

/**
 * Definition of log events
 *
 * @package    local_message
 * @author     Marc
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function local_message_before_footer(){
    // \core\notification::add('test message', \core\output\notification::NOTIFY_SUCCESS);

    // global $DB, $USER;
    

    // $sql = "SELECT lm.id, lm.messagetext, lm.messagetype  FROM {local_message} lm 
    //     left outer join {local_message_read} lmr ON lm.id = lmr.messageid
    //     WHERE lmr.userid <> :userid lmr.userid IS NULL";
    
    // $params = [
    //     'userid' => $USER->id,
    // ];

    // // $messages = $DB->get_records('local_message');
    // $messages = $DB->get_records_sql($sql, $params);

    // foreach ($messages as $message) {
    //     $type = \core\output\notification::NOTIFY_INFO;
    //     if($message->messagetype === '0'){
    //         $type = \core\output\notification::NOTIFY_SUCCESS;
    //     }
    //     if($message->messagetype === '1'){
    //         $type = \core\output\notification::NOTIFY_ERROR;
    //     }
    //     if($message->messagetype === '3'){
    //         $type = \core\output\notification::NOTIFY_WARNING;
    //     }
    //     \core\notification::add($message->messagetext, $type);

    //     $readrecord = new stdClass();
    //     $readrecord->messageid = $message->id;
    //     $readrecord->userid = $USER->id;
    //     $readrecord->timeread = time();
    //     $DB->insert_record('local_message_read', $readrecord);
    // }

}