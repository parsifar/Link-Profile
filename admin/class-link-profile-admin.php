<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://parsifar.com/
 * @since      1.0.0
 *
 * @package    Link_Profile
 * @subpackage Link_Profile/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Link_Profile
 * @subpackage Link_Profile/admin
 * @author     Ali Parsifar <ali@parsifar.com>
 */
class Link_Profile_Admin {

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
		 * defined in Link_Profile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Link_Profile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/link-profile-admin.css', array(), $this->version, 'all' );
		
		//enqueue Bootstrap CSS
		wp_enqueue_style( 'bootstrap-css-link-profile', plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array(), $this->version, 'all' );
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
		 * defined in Link_Profile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Link_Profile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/link-profile-admin.js', array( 'jquery' ), $this->version, false );

		//enqueue Bootstrap JS
		wp_enqueue_script( 'bootstrap-js-link-profile', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * add custom admin menu.
	 *
	 * @since    1.0.0
	 */
	public function my_admin_menu(){
		add_menu_page('Link Profile Plugin' , 'Link Profile' , 'manage_options' , 'link-profile' , array($this, 'link_profile_admin_page') , 'dashicons-admin-links' , 250);
	}

	//this function renders the HTML of the admin page
	public function link_profile_admin_page(){
		require_once 'partials/link-profile-admin-display.php';
	}

	//this function handles the AJAX request
	public function process_link_ajax_requests(){
		require_once 'partials/link-profile-handle-ajax.php';
	}

	

}
