<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cloud1.me
 * @since      1.0.0
 *
 * @package    Scheduled_Sms
 * @subpackage Scheduled_Sms/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Scheduled_Sms
 * @subpackage Scheduled_Sms/includes
 * @author     Gaurav Garg <gauravgargcs1991@gmail.com>
 */
class Scheduled_Sms_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'scheduled-sms',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
