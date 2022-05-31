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
			
			$results = $this->get_sheduled_sms();
			
			 foreach($results as $val){
			
				$tag_id   = get_post_meta($val->ID,'msg_tag',true);
				$tag 	  = $this->get_tag($tag_id);
				$msg_day  = get_post_meta($val->ID,'msg_day',true);
				$status  = get_post_meta($val->ID,'status',true);
				$msg_time = get_post_meta($val->ID,'msg_time',true);
				
				$pause = '';
				$status_btn = "Pause";
				if($status == 0){
					$pause = 'pause-overlay';
					$status_btn = "Resume";
				}
			?>
			
               <li>
                  <!--- Message Box Start --->
                  <div class="S_sms_box">
                     <!--- Header Starts--->
                     <div class="S_sms_box_header">
                        <h3 class="S_sms_box_title"><?php echo $val->post_title; ?></h3>
                        <a class="S_sms_box_edit" data-id="<?php echo $val->ID; ?>"  href="#edit">edit</a>
                     </div>
                     <!--- Header End--->
                     <!--- Content Start --->
                     <div class="S_sms_box_content <?php echo $pause; ?>" id="content_<?php echo $val->ID; ?>">
                        <span>Scheduling: Every <?php echo $msg_day; ?> @ <?php echo $msg_time; ?> AEST</span>
                        <span>Tag: <?php echo $tag; ?></span>
                        <span>Message Body: <?php echo $val->post_content; ?></span>
                     </div>
                     <!--- Content End --->
                     <!--- Footer Start --->
                     <div class="S_sms_box_footer">
                        <button class="S_sms_box_pause" data-status="<?php echo $status;  ?>" data-id="<?php echo $val->ID; ?>" href="#"><?php echo $status_btn; ?></button>
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
	  <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms'; ?>" id="delete_sms">
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
	  
	  
		$tags = $this->get_tag_list();
		$options = '';
		foreach($tags as $val){
			
			$options .= "<option value='".$val->tag_id."'>$val->tag_name</option>";
			
		}
		
	?>
	
	  <!---Popup When Edit --->
      <div id="edit" class="overlay">
         <div class="popup">
            <div class="S_sms_box">
               <h3>Edit Schedule</h3>
               <!--- Message Box Starts --->
               <!--- Content Start --->
               <form method="post" action="<?php echo $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms'; ?>" id="edit_msg_form">
				  <input type="hidden" id="edit_msg_id" name="msg_id" >
                  <input type="text" id="edit_msg_title" name="msg_title">
                  <div class="S_sms_box_content">
                     <div class="S_select_day_time">
                        <div class="S_select_day">	
                           <select name="msg_day" id="edit_msg_day">		
                              <option>Monday</option>
                              <option>Tuesday</option>
                              <option>Wednesday</option>
                              <option>Thursday</option>
                              <option>Friday</option>
                              <option>Saturday</option>
                           </select>
                        </div>
                        <div class="S_select_time">
                           <input type="time" name="msg_time" id="edit_msg_time">
                        </div>
                     </div>
                     <div class="S_select_tag">
                        <select name="msg_tag" id="edit_msg_tag">
						   <?php echo $options; ?>

                        </select>
                     </div>
                     <textarea name="msg_body" id="edit_msg_body" placeholder="Message Body"></textarea>
					 <input type="hidden" name="action" value="edit_sheduled_sms"/>
                  </div>
                  <!--- Content End --->
                  <!--- Footer Start --->
                  <div class="S_sms_box_footer">
                     <button id="submit_edit_form" class="S_sms_box_add" href="#">Save</button>
                     <a class="S_sms_box_delete" href="#">Close</a>
                  </div>
                  <!--- Footer End --->
               </form>
            </div>
            <!--- Message Box End --->
         </div>
      </div>
      <!---Popup when Edit Ends --->	  
	  
      <div id="add" class="overlay">
         <div class="popup">
            <div class="S_sms_box">
               <h3>Add New Schedule</h3>
               <!--- Message Box Starts --->
               <form action="<?php echo $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms'; ?>" id="new_sms_form" method="post">
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
                        </select>
                     </div>
                     <textarea name="msg_body" placeholder="Message Body"></textarea>
					 <input type="hidden" name="action" value="add_new_sms"/>
                  </div>
                  <!--- Content End --->
                  <!--- Footer Start ---> 
                  <div class="S_sms_box_footer">
                     <button id="addItem" class="S_sms_box_add" href="javascript:void(0)" onclick="addItem();">Add</button>
                     <a class="S_sms_box_delete" href="#">Close</a>
                  </div>
                  <!--- Footer End --->
               </form>
            </div>
            <!--- Message Box End --->
         </div>
      </div>
      <!---Popup when Add New Ends --->	
     
   </div>
</div>


<script>
function addItem(){
	   //alert('submit');
	  // jQuery('.S_sms_box_delete').trigger('click')
	  // jQuery(".S_sms_box_delete").click();
	   //jQuery("#new_sms_form").submit();
	   //location.reload();
	   
   }
</script>