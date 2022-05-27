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
		//$this->insert_new_sheduled_sms();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
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
	}
	
	public function insert_new_sheduled_sms(){
		
		
		echo "insert new sheduled sms";
		
	}

}
