<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This is the main class that is responsible for registering
 * the core functions, including the files and setting up all features. 
 * 
 * To add a new class, here's what you need to do: 
 * 1. Add your new class within the following folder: core/includes/classes
 * 2. Create a new variable you want to assign the class to (as e.g. public $helpers)
 * 3. Assign the class within the instance() function ( as e.g. self::$instance->helpers = new Pixel_Tooltips_Helpers();)
 * 4. Register the class you added to core/includes/classes within the includes() function
 * 
 * HELPER COMMENT END
 */

if ( ! class_exists( 'Pixel_Tooltips' ) ) :

	/**
	 * Main Pixel_Tooltips Class.
	 *
	 * @package		PIXELTOOLTIP
	 * @subpackage	Classes/Pixel_Tooltips
	 * @since		1.0.0
	 * @author		Pixel Key
	 */
	final class Pixel_Tooltips {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Pixel_Tooltips
		 */
		private static $instance;

		/**
		 * PIXELTOOLTIP helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Pixel_Tooltips_Helpers
		 */
		public $helpers;

		/**
		 * PIXELTOOLTIP settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Pixel_Tooltips_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'pixel-tooltips' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'pixel-tooltips' ), '1.0.0' );
		}

		/**
		 * Main Pixel_Tooltips Instance.
		 *
		 * Insures that only one instance of Pixel_Tooltips exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Pixel_Tooltips	The one true Pixel_Tooltips
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Pixel_Tooltips ) ) {
				self::$instance					= new Pixel_Tooltips;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Pixel_Tooltips_Helpers();
				self::$instance->settings		= new Pixel_Tooltips_Settings();

				//Fire the plugin logic
				new Pixel_Tooltips_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'PIXELTOOLTIP/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once PIXELTOOLTIP_PLUGIN_DIR . 'core/includes/classes/class-pixel-tooltips-helpers.php';
			require_once PIXELTOOLTIP_PLUGIN_DIR . 'core/includes/classes/class-pixel-tooltips-settings.php';

			require_once PIXELTOOLTIP_PLUGIN_DIR . 'core/includes/classes/class-pixel-tooltips-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'pixel-tooltips', FALSE, dirname( plugin_basename( PIXELTOOLTIP_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.