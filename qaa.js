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
			});
		}
	});
});
