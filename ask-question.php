<?php

require_once('../../../wp-load.php');

$content = $_POST['text'];
$asker_id = $_POST['asker_id'];
$campaign_id = $_POST['campaign_id'];
$answerer_id = $_POST['answerer_id'];
$camp_url = $_POST['camp_url'];


$data = array(
	'recipients'=>$answerer_id,
	'subject'=>"Someone asked a question",
	'content'=>$content
);



$data_confirmation = array (
	'recipients'=>$asker_id,
	'subject'=>"Question sent",
	'content'=>"The following question was sent to the campaign author: " . $content
);

$bp_id = messages_new_message($data);
messages_new_message($data_confirmation);

global $wpdb;


$id = $wpdb->get_var( "SELECT id FROM ".$wpdb->prefix."bp_messages_messages WHERE thread_id='$bp_id' ORDER BY id ASC LIMIT 1");

$content_with_link = $content."\n\n<a style='color:blue' href='$camp_url?message_id=$id'>Answer The Question Publicly</a>\n(Both the question and your answer will be listed on the Campaign page.)";

$wpdb->update($wpdb->prefix . 'bp_messages_messages',
	array(
		'message'=>$content_with_link
	),
	array('id'=>$id)
);

$wpdb->insert($wpdb->prefix . 'questionandanswer',
	array(
		'asker_id' => $asker_id,
		'campaign_id' => $campaign_id,
		'question' => $content,
		'answerer_id'=> $answerer_id,
		'answered' => 0,
		'message_id' => $id
	)
);

if ($bp_id) {
	echo 1;
} else {
	echo 0;
}


?>


