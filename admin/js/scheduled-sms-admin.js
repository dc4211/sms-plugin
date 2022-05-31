(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
})( jQuery );



jQuery(document).ready(function() {

jQuery('.S_sms_box_pause').click(function() {
       //jQuery('.S_sms_box_content').toggleClass('pause-overlay');
	   var sms_id = jQuery(this).attr('data-id');
	   var status = jQuery(this).attr('data-status');
	   if(status == 1){
		   jQuery(this).attr('data-status',0);
		   jQuery(this).html('Resume');
		   status = 0;
	   }else{
		   jQuery(this).attr('data-status',1);
		   jQuery(this).html('Pause');
		   status = 1;
	   }
	   jQuery('#content_'+sms_id).toggleClass('pause-overlay');
	   pause_sheduled_sms(sms_id, status);
	  
   })	
   
   jQuery(".S_sms_box_delete").click(function() {
	   var value = jQuery(this).attr('data-id');
	   jQuery(".dlt_popUp").val(value);
	   
   });
   
   jQuery(".S_sms_cinfirm_delete").click(function() {
	   jQuery(".close").click();
	   jQuery("#delete_sms").submit();	   
	   jQuery(".close").click();
	   	   
   });
   
 /*  jQuery(".S_sms_box_pause").click(function() {
        	jQuery('.S_sms_box_pause').html(jQuery('.S_sms_box_pause').text() == 'Pause' ? 'Resume' : 'Pause');
   
   });	*/
   
   
   function addItem(){
	   alert('submit');
	   jQuery(".S_sms_box_delete").click();
	  // jQuery("#new_sms_form").submit();
	  // location.reload();
	   
   }

   
jQuery(".S_sms_box_edit").click(function(){
	 
		var sms_id = jQuery(this).attr('data-id');
		
		var data = {
			sms_id:sms_id,
			action:"update_sheduled_sms",
		}

    jQuery.ajax({
        type: "POST",
        url : ajax_url.adminurl,
        dataType: 'json',
        data: data ,
        success: function(data) {
			
			jQuery("#edit_msg_id").val(sms_id);
			jQuery("#edit_msg_title").val(data.title);
			jQuery("#edit_msg_body").val(data.msg_body);
			jQuery("#edit_msg_tag").val(data.msg_tag_id);
			jQuery("#edit_msg_day").val(data.msg_day);
			jQuery("#edit_msg_time").val(data.msg_time);
			
           
        },
    });
});


	//jQuery("#submit_edit_form").click(function(){
	function editItem(){
	 
		var form_data = jQuery('#edit_msg_form').serialize();
		jQuery.ajax({
			type: "POST",
			url : ajax_url.adminurl,
			dataType: 'json',
			data: form_data ,
			success: function(data) {
			
			console.log(data);

			},
		});
	}
   function pause_sheduled_sms(sms_id,status){
		var data = {
			sms_id:sms_id,
			status:status,
			action:'pause_sheduled_sms',
		};
		jQuery.ajax({
			type: "POST",
			url : ajax_url.adminurl,
			dataType: 'json',
			data: data ,
			success: function(data) {
				
			},
		});
	}
}); 