<?php

require_once('../../../wp-load.php');

$text = $_POST['text'];
$message_id = $_POST['message_id'];

global $wpdb;

$q_table = $wpdb->prefix."questionandanswer";

$answerer_id = $wpdb->get_var("SELECT answerer_id
								FROM $q_table
								WHERE message_id=$message_id");

/**
 * trying to make sure that the right person,
 * which is the author, is answering the question
 */
if($answerer_id == get_current_user_id()) {
	// update the data table with the new time,
	// the new txt and the approved indicator
	$wpdb->update($wpdb->prefix . 'questionandanswer',
		array(
			'answer'=>$text,
			'answered'=>1,
			'time'=>date("Y-m-d H:i:s")
			),
		array('message_id'=>$message_id)
	);
}

?>