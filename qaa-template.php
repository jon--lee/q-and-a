<?php
/**
 * template file for question and answer to go in the
 * campaign sidebar
 */

global $wpdb;
$q_table = $wpdb->prefix."questionandanswer";

$message_id = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['message_id']);
if($message_id){
	
	$q_info = $wpdb->get_row("SELECT campaign_id, asker_id, answerer_id, question, answered 
								FROM $q_table 
								WHERE message_id=$message_id", ARRAY_A);

	if($q_info
		&& get_current_user_id() == $q_info['answerer_id']
		&& get_the_ID() == $q_info['campaign_id']
		&& $q_info['answered'] == 0) {
			$question = $q_info['question'];
			echo "
			<script>
				jQuery(document).ready(function() {
	   				 jQuery('#answerQuestionModal').foundation('reveal', 'open');
				});

			</script>
			<input type='hidden' id='message_id' value='$message_id'>

			<div id='answerQuestionModal' class='reveal-modal campaign-form content-block' data-reveal aria-labelledby='answerQuestionModal' aria-hidden='true' role='dialog'>
				<div class='title-wrapper'><h2 class='block-title'>Answer Question</h2></div>

				<div class='row '>
					<div class='large-12 columns'>
						<span class='questionToAnswer'>Q: $question</span>
				    	<textarea placeholder='Write an answer...' id='answerText'></textarea>
				 	</div>
				</div>

				<a class='close-reveal-modal' aria-label='Close'><i class='icon-remove-sign'></i></a>

				<p>
					<a id='submitAnswer' class='button button-small' data-reveal-id='answerQuestionModal'>Send</a>
					<a class='button button-small' href='#' data-reveal-id='answerQuestionModal'>Cancel</a>
				</p>
			</div>

		";
	}
}

$campaign_id = get_the_ID();
$answered_questions_count = $wpdb->get_var("SELECT COUNT(*) FROM ".$wpdb->prefix."questionandanswer WHERE campaign_id='$campaign_id' AND answered='1'");


?>

<aside id='qandaWidget' class='widget cf widget-q-and-a-widget'>
	<div class='title-wrapper'>
		<h4 class='widget-title'>Q&A</h4>
	</div>

	<?php if ($answered_questions_count) {?>
		<script>
			var qandas = {};
		</script>
		<div class='qandas'>
			<?php

				$questions = $wpdb->get_results("SELECT *
											 FROM $q_table
											 WHERE campaign_id=$campaign_id
											 AND answered='1'
											 ORDER BY time
											 DESC");
				foreach($questions as $q) {
					$question = nl2br(stripslashes($q->question));
					$qid = $q->id;
					$asker_id = $q->asker_id;
					$answerer_id = $q->answerer_id;

					$answererinfo = get_userdata($answerer_id);
					$askerinfo = get_userdata($asker_id);
					$answer =	nl2br(stripslashes($q->answer));
					$asker_link = "../../members/$askerinfo->user_nicename";
					$answerer_link = "../../members/$answererinfo->user_nicename";

					echo "
						<div class='quest'>
							<a href='$asker_link'><div class='avatar'>".get_avatar($asker_id,'25')."</div></a>
							<div class='questContent'><a href='$asker_link'><strong>$askerinfo->user_nicename</strong></a>
								<br>
								<span class='qaa-all'>
									<span class='quest-text' quest-id='$qid' collapsed='0'><strong>Q:</strong> $question</span>
									<br>
									<a href='$answerer_link'><div class='avatar'>".get_avatar($answerer_id,'25')."</div></a>
									<a href='$answerer_link'><strong>$answererinfo->user_nicename</strong></a>
									<br>
									<span class='answer-text' quest-id='$qid' collapsed='0'><strong>A:</strong> $answer</span>
								</span>
							</div>
						</div>
					";
				}
			?>
		</div>

		<script>
			var MAX_CHARACTERS = 75;

			jQuery('.qaa-all').hover(function(){
				jQuery(this).css('color', 'blue');
			}, function(){
				jQuery(this).css('color', 'black');
			})

			jQuery('.qaa-all').click(function(){
				jQuery(this).children('span').each(function(){
					collapseQ(jQuery(this));
				});
			});


			jQuery(window).ready(function(){
				jQuery('.qaa-all').each(function(index){
					jQuery(this).children('span').each(function(){

						var element = jQuery(this);
						var questId = element.attr('quest-id');
						var questClass = element.attr('class').toString();

						var content = element.html().toString();
						var subcontent = content.substring(0, MAX_CHARACTERS) + "...";
						var firstOccurence = subcontent.indexOf("<br");
						var secondOccurence = subcontent.indexOf("<br", firstOccurence + 3);
						if(secondOccurence >= 0) {
							subcontent = subcontent.substring(0, secondOccurence) + "...";
						}

						qandas[questClass + questId.toString()] = { content: content, subcontent: subcontent }
						collapseQ(element);

					});
				});
			});


			function collapseQ(element) {
				var isCollapsed = element.attr('collapsed');
				var questId = element.attr('quest-id').toString();
				var questClass = element.attr('class').toString();

				if (isCollapsed == '0') {
					var subcontent = qandas[questClass + questId].subcontent
					element.html(subcontent);
					element.attr("collapsed", '1');
				}
				else {
					var content = qandas[questClass + questId].content;
					element.html(content);
					element.attr('collapsed', '0');
				}
			}

		</script>
	<?php } ?>

	<?php if(get_current_user_id()){ ?>	
	<div class='suggest-idea'>
		<a href='imagebutton' href='#' data-reveal-id='askQuestionModal'>
			<img src='<?php echo plugins_url();?>/q-and-a/images/AskAQuestion_n.png' 
			onmouseover="this.src='<?php echo plugins_url();?>/q-and-a/images/AskAQuestion_s.png'" 
			onmouseout="this.src='<?php echo plugins_url();?>/q-and-a/images/AskAQuestion_n.png'" />
		</a>
	</div>
	<?php } ?>

</aside>


<?php
	$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	$qIndex = strrpos($link, "?");
	if ($qIndex > 0){
		$link = substr($link, 0, $qIndex);
	}
	
?>

<input type="hidden" id="plugindir" value="<?php echo plugins_url();?>">
<input type="hidden" id="asker_id" value="<?php echo get_current_user_id();?>">
<input type="hidden" id="campaign_id" value="<?php echo get_the_ID();?>">
<input type="hidden" id="answerer_id" value="<?php echo the_author_meta('ID');?>">
<input type='hidden' id='camp_url' value="<?php echo $link; ?>">

<div id='askQuestionModal' class='reveal-modal campaign-form content-block' data-reveal aria-labelledby='askQuestionModal' aria-hidden='true' role='dialog'>
	<div class='title-wrapper'><h2 class='block-title'>Ask a Question</h2></div>
	<div id='askQuestionModalContent'>
		<div class="row ">
			<div class="large-12 columns">
		    	<textarea placeholder="What is your question?" id="questionText"></textarea>
		 	</div>
		</div>

		<a class="close-reveal-modal" aria-label="Close"><i class="icon-remove-sign"></i></a>

		<p>
			<a id="submitQuestion" class="button button-small">Send</a>
			<a class="button button-small" href="#" data-reveal-id="askQuestionModal">Cancel</a>
		</p>
	</div>
	<a class="close-reveal-modal" aria-label="Close"><i class="icon-remove-sign"></i></a>
</div>








