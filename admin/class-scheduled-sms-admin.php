<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://cloud1.me
 * @since      1.0.0
 *
 * @package    Scheduled_Sms
 * @subpackage Scheduled_Sms/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Scheduled_Sms
 * @subpackage Scheduled_Sms/admin
 * @author     Gaurav Garg <gauravgargcs1991@gmail.com>
 */
class Scheduled_Sms_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->hooks();
		$this->shedule_sms();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	 
	public function hooks(){
		
		add_action('wp_ajax_update_sheduled_sms',array($this, 'get_sheduled_sms_data'));
		//add_action('wp_ajax_insert_sheduled_sms',array($this, 'insert_sheduled_sms'));
		//add_action('wp_ajax_edit_sms',array($this, 'ajax_edit_sheduled_sms'));
		add_action('wp_ajax_pause_sheduled_sms',array($this, 'ajax_pause_sheduled_sms'));
		
		
		///recursive event....
		add_action('init',array( $this, 'shedule_event' ));
		add_action('my_cloud_schedule_hook',array($this, 'print_code' ));
		//add_filter('cron_schedules', array( $this, 'my_cron_schedules'));
		add_filter( 'cron_schedules', array( $this,'custom_cron_job_recurrence' ));
	}
	
	
	 
	
	public function my_cron_schedules($schedules){
		if(!isset($schedules["two_sec"])){
			$schedules["two_sec"] = array(
				'interval' => 1,
				'display' => __('Once every 1 minutes'));
		}
		if(!isset($schedules["30min"])){
			$schedules["30min"] = array(
				'interval' => 30*60,
				'display' => __('Once every 30 minutes'));
		}
		return $schedules;
	}
	
	
	public function shedule_event(){
		wp_clear_scheduled_hook( 'my_cloud_schedule_hook' );
	//if ( ! wp_next_scheduled( 'my_cloud_schedule_hook' ) ) {
	//   
	//  wp_schedule_event(time(), '10sec', 'my_cloud_schedule_hook');
	//}
	}
	
	
	public function print_code(){
		
		$current_time = date('h:i');
		
			$msg_title = 'every 10 sec cron test';
			$msg_day   = 'Monday';
			$msg_time  = $current_time ;
			$msg_tag   = 87;
			$msg_body  = 'Here is the test for cron job!';
			
			$args = array(
				'post_type' => 'sheduled_sms',
				'post_status' => 'publish',
				'post_title' => $msg_title,
				'post_content' => $msg_body,
				'meta_input'    => array(
										'msg_day'   => $msg_day,
										'msg_time'  => $current_time,
										'msg_tag'   => $msg_tag,
										'status'    => 0,
										)
		
					);
				 
			$id = wp_insert_post( $args );
			$page = $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms';
			$sec = "0";
			header("Refresh: $sec; url=$page");
		
	}
		
	
	public	function custom_cron_job_recurrence( $schedules ){
			if(!isset($schedules['10sec']))
			{
				$schedules['10sec'] = array(
					'display' => __( 'Every 10 Seconds', 'twentyfifteen' ),
					'interval' => 10,
				);
			}
			 
			if(!isset($schedules['15sec']))
			{
				$schedules['15sec'] = array(
				'display' => __( 'Every 15 Seconds', 'twentyfifteen' ),
				'interval' => 15,
				);
			}
			 
			return $schedules;
	}

	
	
	
	
	
	public function insert_sheduled_sms(){
		
		$msg_id = $_POST['msg_id'];
		$msg_title = $_POST['msg_title'];
		$msg_day = $_POST['msg_day'];
		$msg_time = $_POST['msg_time'];
		$msg_tag = $_POST['msg_tag'];
		$msg_body = $_POST['msg_body'];
		
		$args = array(
					'post_type'   => 'sheduled_sms',
					'post_status' => 'publish',
					'post_title'  => $msg_title,
					'post_content'=> $msg_body,
					'meta_input'  => array(
											'msg_day'   => $msg_day,
											'msg_time'  => $msg_time,
											'msg_tag'   => $msg_tag,
											'status'    => 1,
											)
			
						);
				 
					$result = wp_insert_post( $args );
				
				
				
					if ( is_wp_error( $result ) ) {
						//$error_code = array_key_first( $result->errors );
						//$message = $result->errors[$error_code][0];
						$message = 'not edited';
					}else{
						$message = 'edited successfully!';
					}
					echo json_encode($message);
					die();
	}
	
	
	public function get_sheduled_sms_data(){
		$sms_id = $_POST['sms_id'];		
		$post = get_post($sms_id);
		
		$tag_name = $this->get_tag($post->msg_tag);

		$msg_data = array(	
			'title'		=>$post->post_title,
			'msg_body'	=>$post->post_content,
			'msg_tag_id'=>$post->msg_tag,
			'msg_tag'	=>$tag_name,
			'msg_day'	=>$post->msg_day,
			'msg_time'	=>$post->msg_time
		);
			
		echo json_encode($msg_data);
		die();
	}
	
	
	public function ajax_edit_sheduled_sms(){
		
		$msg_id = $_POST['msg_id'];
		$msg_title = $_POST['msg_title'];
		$msg_day = $_POST['msg_day'];
		$msg_time = $_POST['msg_time'];
		$msg_tag = $_POST['msg_tag'];
		$msg_body = $_POST['edit_msg_body'];
		
		$args = array(
					'ID' 		  => $msg_id,
					'post_type'   => 'sheduled_sms',
					'post_status' => 'publish',
					'post_title'  => $msg_title,
					'post_content'=> $msg_body,
					'meta_input'  => array(
											'msg_day'   => $msg_day,
											'msg_time'  => $msg_time,
											'msg_tag'   => $msg_tag,
											)
			
						);
				 
					$result = wp_insert_post( $args );
				
					if ( is_wp_error( $result ) ) {
						$error_code = array_key_first( $result->errors );
						$error_message = $result->errors[$error_code][0];
					}else{
						echo "post updated!";
					}
					die();
	}
	 
	 
	public function get_tag($tag_id){
		
		global $wpdb;
		//$results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."gh_tags` ORDER BY `tag_id` DESC");
		$results = $wpdb->get_results("SELECT `tag_name` FROM `".$wpdb->prefix."gh_tags` WHERE `tag_id` = ".$tag_id."");
		return $results[0]->tag_name;
	}
	 
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Scheduled_Sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Scheduled_Sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/scheduled-sms-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Scheduled_Sms_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Scheduled_Sms_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/scheduled-sms-admin.js', array( 'jquery' ), $this->version, false );
		
		wp_localize_script($this->plugin_name, 'ajax_url', array(
			'adminurl' => admin_url() . 'admin-ajax.php',
			'loggedIn' => (is_user_logged_in()) ? 1 : 0,
			'baseUrl' => site_url(),
			'userId' => (get_current_user_id()) ? get_current_user_id() : 0,
			)
		);

	}
	
	public function co_s_sms_admin_menu(){
		add_menu_page( 
			__( 'Scheduled SMS' ),
			'Scheduled SMS',
			'manage_options',
			'ssms_schedule_sms',
			array($this, 'ssms_schedule_sms_callback')
		); 
	}
	
	public function ssms_schedule_sms_callback(){
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/scheduled-sms-admin-display.php';
		$this->insert_new_sheduled_sms();
		$this->edit_sheduled_sms();
		$this->delete_sheduled_sms();
	}


	public function delete_sheduled_sms(){
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'delete_sms'){
				$sms_id = $_REQUEST['sms_id'];
				$result = wp_delete_post( $sms_id,true );  
				if ( is_wp_error( $result ) ) {
					$error_code = array_key_first( $result->errors );
					$error_message = $result->errors[$error_code][0];
				}
				
				$page = $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms';
				$sec = "0";
				header("Refresh: $sec; url=$page");
				
			}
	}
	
	public function edit_sheduled_sms(){
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit_sheduled_sms'){
								
				$msg_id = $_POST['msg_id'];
				$msg_title = $_POST['msg_title'];
				$msg_day = $_POST['msg_day'];
				$msg_time = $_POST['msg_time'];
				$msg_tag = $_POST['msg_tag'];
				$msg_body = $_POST['msg_body'];
				
				$args = array(
							'ID'   => $msg_id,
							'post_type'   => 'sheduled_sms',
							'post_status' => 'publish',
							'post_title'  => $msg_title,
							'post_content'=> $msg_body,
							'meta_input'  => array(
												'msg_day'   => $msg_day,
												'msg_time'  => $msg_time,
												'msg_tag'   => $msg_tag,
												)
			
							);
				 
				$result = wp_insert_post( $args );
				
				if ( is_wp_error( $result ) ) {
					echo $result->errors[$error_code][0];
				}else{
					
					$page = $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms';
					$sec = "0";
					header("Refresh: $sec; url=$page");
					
				}
				
		}
	}
	
	public function insert_new_sheduled_sms(){
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
					}else{
						$page = $_SERVER['PHP_SELF'].'?page=ssms_schedule_sms';
						$sec = "0";
						header("Refresh: $sec; url=$page");
					}
			}
		}
	}
	
	
	public function get_tag_list(){
		global $wpdb;
		//$results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."gh_tags` ORDER BY `tag_id` DESC");
		$results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."gh_tags` ORDER BY `tag_name` ASC");
		return $results;
	}
	
	public function get_sheduled_sms(){
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM `".$wpdb->prefix."posts` WHERE `post_type` = 'sheduled_sms'ORDER BY `id` ASC");
		return $results;
	}
	
	public function ajax_pause_sheduled_sms(){
		
		$msg_id = $_POST['sms_id'];
		$status = $_POST['status'];
		update_post_meta($msg_id,'status',$status);
		$result = get_post_meta($msg_id,'status',true);
		if($result == 1){
			$update_status = "active";
		}else{
			$update_status = "pause";
		}
		echo  "status updated to ".$update_status;
		die();
	}
	
	
	
	
	public function shedule_sms(){
		$current_time = date('h:i');
		$args = array(
				'post_type'  => 'sheduled_sms',
				'meta_query' => array(
					array(
						'key'   => 'status',
						'value' => 1,
					),
					array(
						'key'   => 'msg_time',
						'value' => $current_time,
					)
				)
			);
		$smslist = get_posts( $args );
		
		foreach($smslist as $sms){
			$contact_ids = $this->get_contact_id_by_tag($sms->msg_tag);
			foreach($contact_ids as $val){
				$contact_detail = $this->get_phoneNo_by_contact($val->contact_id);
				foreach($contact_detail as $contact_val){
					$primary_number = $contact_val->meta_value;
					
				}
			}
		}
		
	}
	
	
	
	public function get_contact_id_by_tag($tag_id){
		
		global $wpdb;
		$results = $wpdb->get_results("SELECT `contact_id` FROM `wp_gh_tag_relationships` WHERE `tag_id` = '".$tag_id."'");
		return $results;
		
	}
	
	public function get_phoneNo_by_contact($contact_id){
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM `wp_gh_contactmeta` WHERE `contact_id` = '".$contact_id."' AND `meta_key` = 'primary_phone'");
		return $results;
	}
	
	
	/*
	public function schedule_email_cron($post_id){
		// Get the UNIX 30 days from now time
		$thirty_days = time() + 60; // (30 * 24 * 60 * 60)
		$post = get_post($post_id);
		$email = get_the_author_meta('user_email', $post->post_author);
		$args = array('email' => $email, 'title' => $post->post_title);
		wp_schedule_single_event($thirty_days, 'email_about_coupon_action', $args); 
	}

	add_action('save_post', 'schedule_email_cron', 1, 1);

	add_action('email_about_coupon_action', 'email_about_coupon', 1, 1);

	function email_about_coupon($args){
		// Email text
		$text = "<html><body><p>Your coupon titled, ".$args['title']." is expiring soon. Please visit <a href=\"\">".get_bloginfo('siteurl')."/registered/</a> ".get_bloginfo('siteurl')."/registered/ to renew your coupon.</p></body></html>";

		// Email headers
		$headers = array(
			'From: '.get_bloginfo('name').' <'.get_bloginfo('admin_email').'>',
			"Content-Type: text/html"
		);
		$h = implode("\r\n",$headers) . "\r\n";

		// Send email
		wp_mail($args['email'], 'Renew Your Coupon Now!', $text, $h);
	}*/
	
	
	
}
