<?php
   /**
    * Provide a admin area view for the plugin
    *
    * This file is used to markup the admin-facing aspects of the plugin.
    *
    * @link       https://cloud1.me
    * @since      1.0.0
    *
    * @package    Scheduled_Sms
    * @subpackage Scheduled_Sms/admin/partials
    */
   ?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
   <div class="S_sms_main">
      <div class="S_sms_inner">
         <div class="S_sms_add"><a href="#add">+</a></div>
         <div class="S_sms_box_container">
            <ul>
			<?php 
			
			
			
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_sms'){
				$sms_id = $_REQUEST['sms_id'];
				$result = wp_delete_post( $sms_id,true );  
				if ( is_wp_error( $result ) ) {
					$error_code = array_key_first( $result->errors );
					$error_message = $result->errors[$error_code][0];
				}
			}
			
			 global $wpdb;
			 $results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."posts` WHERE `post_type` = 'sheduled_sms'");
			
			 foreach($results as $val){
			
				$tag = get_post_meta($val->ID,'msg_tag',true);
				$msg_day = get_post_meta($val->ID,'msg_day',true);
				$msg_time = get_post_meta($val->ID,'msg_time',true);
			?>
			
               <li>
                  <!--- Message Box Start --->
                  <div class="S_sms_box">
                     <!--- Header Starts--->
                     <div class="S_sms_box_header">
                        <h3 class="S_sms_box_title"><?php echo $val->post_title; ?></h3>
                        <a class="S_sms_box_edit" href="#edit">edit</a>
                     </div>
                     <!--- Header End--->
                     <!--- Content Start --->
                     <div class="S_sms_box_content">
                        <span>Scheduling: Every <?php echo $msg_day; ?> @ <?php echo $msg_time; ?> AEST</span>
                        <span>Tag: <?php echo $tag; ?></span>
                        <span>Message Body: <?php echo $val->post_content; ?></span>
                     </div>
                     <!--- Content End --->
                     <!--- Footer Start --->
                     <div class="S_sms_box_footer">
                        <button class="S_sms_box_pause" id="sms_pause" href="#">Pause</button>
                        <a class="S_sms_box_delete" data-id="<?php echo $val->ID; ?>" href="#popup1">Delete</a>
						
                     </div>
                     <!--- Footer End --->
                  </div>
                  <!--- Message Box End --->
               </li>
			   
		<?php  } ?>   

            </ul>
         </div>
      </div>
	  <form method="post" id="delete_sms">
		<input type="hidden" name="sms_id" class="dlt_popUp" value=""/>
		<input type="hidden" name="action" value="delete_sms"/>
	  </form>
	  
      <!---Popup when delete Starts --->
      <div id="popup1" class="overlay">
         <div class="popup">
            <h1>!</h1>
            <h2>Are you sure?</h2>
            <a class="close" href="#">&times;</a>
			<div class="content">
			  <a class="S_sms_cinfirm_delete" href="#">Confirm delete</a>
			</div>			
         </div>
      </div>
      <!---Popup when delete Ends --->
      <!---Popup when Add New --->
	  
	 <?php 
	  
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."gh_tags` ORDER BY `tag_id` DESC");
		$options = '';
		foreach($results as $val){
			
			$options .= "<option value='".$val->tag_id."'>$val->tag_name</option>";
			
		}
		
		
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'add_new_sms'){
			
			$msg_title = $_REQUEST['msg_title'];
			$msg_day   = $_REQUEST['msg_day'];
			$msg_time  = $_REQUEST['msg_time'];
			$msg_tag   = $_REQUEST['msg_tag'];
			$msg_body  = $_REQUEST['msg_body'];
			
			if(empty($msg_title) || empty($msg_day) || empty($msg_time) || empty($msg_tag) || empty($msg_body)){
				
				echo "<h3>Not Added, All fields are mandatory!</h3>";
				
			}else{
				
				$args = array(
					'post_type' => 'sheduled_sms',
					'post_status' => 'publish',
					'post_title' => $msg_title,
					'post_content' => $msg_body,
					'meta_input'    => array(
											'msg_day'   => $msg_day,
											'msg_time'  => $msg_time,
											'msg_tag'   => $msg_tag,
											'status'    => 1,
											)
			
						);
				 
					$id = wp_insert_post( $args );
				
					if ( is_wp_error( $id ) ) {
						$error_code = array_key_first( $id->errors );
						$error_message = $id->errors[$error_code][0];
					}
				
			}
			
			
		}
		
		

	?>
	  
	  
      <div id="add" class="overlay">
         <div class="popup">
            <div class="S_sms_box">
               <h3>Add New Schedule</h3>
               <!--- Message Box Starts --->
               <form action="" method="post">
                  <!--- Content Start --->
                  <input type="text" name="msg_title" placeholder="Enter message title">
                  <div class="S_sms_box_content">
                     <div class="S_select_day_time">
                        <div class="S_select_day">
                           <select name="msg_day">
                              <option disabled selected>Select day</option>
                              <option>Monday</option>
                              <option>Tuesday</option>
                              <option>Wednesday</option>
                              <option>Thursday</option>
                              <option>Friday</option>
                              <option>Saturday</option>
                           </select>
                        </div>
                        <div class="S_select_time">
                           <input type="time" name="msg_time">
                        </div>
                     </div>
                     <div class="S_select_tag">
                        <select name="msg_tag">
                           <option disabled selected>Select tag</option>
						   <?php echo $options; ?>
                          <!-- <option>enrolled_VCE_physics</option>
                           <option>enrolled_VCE_maths</option>
                           <option>enrolled_VCE_science</option>
                           <option>enrolled_VCE_english</option>
                           <option>enrolled_VCE_biology</option>
                           <option>enrolled_VCE_french</option>-->
                        </select>
                     </div>
                     <textarea name="msg_body" placeholder="Message Body"></textarea>
					 <input type="hidden" name="action" value="add_new_sms"/>
                  </div>
                  <!--- Content End --->
                  <!--- Footer Start --->
                  <div class="S_sms_box_footer">
                     <button class="S_sms_box_add" href="#">Add</button>
                     <a class="S_sms_box_delete" href="#">Close</a>
                  </div>
                  <!--- Footer End --->
               </form>
            </div>
            <!--- Message Box End --->
         </div>
      </div>
      <!---Popup when Add New Ends --->	
      <!---Popup When Edit --->
      <div id="edit" class="overlay">
         <div class="popup">
            <div class="S_sms_box">
               <h3>Edit Schedule</h3>
               <!--- Message Box Starts --->
               <!--- Content Start --->
               <form action="#">
                  <input type="text" name="#" value="Text Message Name">
                  <div class="S_sms_box_content">
                     <div class="S_select_day_time">
                        <div class="S_select_day">
                           <select>
                              <option selected>Monday</option>
                              <option>Tuesday</option>
                              <option>Wednesday</option>
                              <option>Thursday</option>
                              <option>Friday</option>
                              <option>Saturday</option>
                           </select>
                        </div>
                        <div class="S_select_time">
                           <input type="time" name="#">
                        </div>
                     </div>
                     <div class="S_select_tag">
                        <select>
                           <option selected>enrolled_VCE_physics</option>
                           <option>enrolled_VCE_maths</option>
                           <option>enrolled_VCE_science</option>
                           <option>enrolled_VCE_english</option>
                           <option>enrolled_VCE_biology</option>
                           <option>enrolled_VCE_french</option>
                        </select>
                     </div>
                     <textarea placeholder="Message Body">A reminder that your class starts at 6pm today. The link to attand is https://google.com</textarea>
                  </div>
                  <!--- Content End --->
                  <!--- Footer Start --->
                  <div class="S_sms_box_footer">
                     <button class="S_sms_box_add" href="#">Save</button>
                     <a class="S_sms_box_delete" href="#">Close</a>
                  </div>
                  <!--- Footer End --->
               </form>
            </div>
            <!--- Message Box End --->
         </div>
      </div>
      <!---Popup when Edit Ends --->	  
   </div>
</div>
<script>
   jQuery('.S_sms_box_pause').click(function() {
       jQuery('.S_sms_box_content').toggleClass('pause-overlay');
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
   
   jQuery(".S_sms_box_pause").click(function() {
        	jQuery('.S_sms_box_pause').html(jQuery('.S_sms_box_pause').text() == 'Pause' ? 'Resume' : 'Pause');
   
   });	
   
</script>