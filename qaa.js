jQuery(document).ready(function(){
	jQuery('#submitQuestion').click(function(e){
		var text = jQuery("#questionText").val();
		var asker_id = jQuery('#asker_id').val();
		var campaign_id = jQuery('#campaign_id').val();
		var answerer_id = jQuery('#answerer_id').val();
		var plugindir = jQuery('#plugindir').val();
		var camp_url = jQuery("#camp_url").val();


		if(text != "") {
			jQuery('#questionText').val('');
			jQuery.post(plugindir+'/q-and-a/ask-question.php',{text:text,asker_id:asker_id,campaign_id:campaign_id,answerer_id:answerer_id,camp_url:camp_url},function(data){
				if(data == 1) {
					jQuery('#askQuestionModalContent').html('<div style="text-align: center;font-weight:bold;font-size:16px;">Your question has been sent to the campaign author!</div>');
					
				} else {
					jQuery('#askQuestionModalContent').html('<div style="text-align: center;font-weight:bold;color:#900;font-size:16px;">Sorry! Your question cannot be sent at this time. Please try again later!</div>');
				}
			});
		}
	});

	jQuery('#submitAnswer').click(function(e){
		var text = jQuery('#answerText').val();
		if(text != "")
		{
			var plugindir = jQuery("#plugindir").val();
			var message_id = jQuery("#message_id").val();
			jQuery('#answerText').val('');
			jQuery.post(plugindir+'/q-and-a/answer.php', {text:text,message_id:message_id}, function(data){
				location.reload();
			});
		}
	});
});
