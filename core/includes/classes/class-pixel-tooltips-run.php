<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * HELPER COMMENT START
 * 
 * This class is used to bring your plugin to life. 
 * All the other registered classed bring features which are
 * controlled and managed by this class.
 * 
 * Within the add_hooks() function, you can register all of 
 * your WordPress related actions and filters as followed:
 * 
 * add_action( 'my_action_hook_to_call', array( $this, 'the_action_hook_callback', 10, 1 ) );
 * or
 * add_filter( 'my_filter_hook_to_call', array( $this, 'the_filter_hook_callback', 10, 1 ) );
 * or
 * add_shortcode( 'my_shortcode_tag', array( $this, 'the_shortcode_callback', 10 ) );
 * 
 * Once added, you can create the callback function, within this class, as followed: 
 * 
 * public function the_action_hook_callback( $some_variable ){}
 * or
 * public function the_filter_hook_callback( $some_variable ){}
 * or
 * public function the_shortcode_callback( $attributes = array(), $content = '' ){}
 * 
 * 
 * HELPER COMMENT END
 */

/**
 * Class Pixel_Tooltips_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		PIXELTOOLTIP
 * @subpackage	Classes/Pixel_Tooltips_Run
 * @author		Pixel Key
 * @since		1.0.0
 */
class Pixel_Tooltips_Run{

	/**
	 * Our Pixel_Tooltips_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . PIXELTOOLTIP_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
		add_shortcode( 'pixel_tooltip', array( $this, 'add_shortcode_callback' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_backend_scripts_and_styles' ), 20 );
		add_action( 'init', array( $this, 'add_custom_post_type' ), 20 );
		register_activation_hook( PIXELTOOLTIP_PLUGIN_FILE, array( $this, 'activation_hook_callback' ) );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	1.0.0
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*/
	public function add_plugin_action_link( $links ) {

		$links['our_shop'] = sprintf( '<a href="%s" title="Custom Link" style="font-weight:700;">%s</a>', 'https://test.test', __( 'Custom Link', 'pixel-tooltips' ) );

		return $links;
	}

	/**
	 * Add the shortcode callback for [pixel_tooltip]
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$attr		Additional attributes you have added within the shortcode tag.
	 * @param	string	$content	The content you added between an opening and closing shortcode tag.
	 *
	 * @return	string	The customized content by the shortcode.
	 */
	public function add_shortcode_callback( $attr = array(), $content = '' ) {

		$content .= ' this content is added by the add_shortcode_callback() function';

		return $content;
	}

	/**
	 * Enqueue the backend related scripts and styles for this plugin.
	 * All of the added scripts andstyles will be available on every page within the backend.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_backend_scripts_and_styles() {
		wp_enqueue_style( 'pixeltooltip-backend-styles', PIXELTOOLTIP_PLUGIN_URL . 'core/includes/assets/css/backend-styles.css', array(), PIXELTOOLTIP_VERSION, 'all' );
	}

	/**
	 * Add all of the available custom post types
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function add_custom_post_type(){

		$labels = array(
			'name'                  => _x( '', 'Post type general name', 'pixel-tooltips' ),
			'singular_name'         => _x( 'Demo Type', 'Post type singular name', 'pixel-tooltips' ),
			'menu_name'             => _x( '', 'Admin Menu text', 'pixel-tooltips' ),
			'name_admin_bar'        => _x( 'Demo Type', 'Add New on Toolbar', 'pixel-tooltips' ),
			'add_new'               => __( 'Add New', 'pixel-tooltips' ),
			'add_new_item'          => __( 'Add New Demo Type', 'pixel-tooltips' ),
			'new_item'              => __( 'New Demo Type', 'pixel-tooltips' ),
			'edit_item'             => __( 'Edit Demo Type', 'pixel-tooltips' ),
			'view_item'             => __( 'View Demo Type', 'pixel-tooltips' ),
			'all_items'             => __( 'All ', 'pixel-tooltips' ),
			'search_items'          => __( 'Search ', 'pixel-tooltips' ),
			'parent_item_colon'     => __( 'Parent :', 'pixel-tooltips' ),
			'not_found'             => __( 'No  found.', 'pixel-tooltips' ),
			'not_found_in_trash'    => __( 'No  found in Trash.', 'pixel-tooltips' ),
			'featured_image'        => _x( 'Demo Type Cover Image', 'Overrides the "Featured Image" phrase for this post type.', 'pixel-tooltips' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'pixel-tooltips' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'pixel-tooltips' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'pixel-tooltips' ),
			'archives'              => _x( 'Demo Type archives', 'The post type archive label used in nav menus. Default "Post Archives".', 'pixel-tooltips' ),
			'insert_into_item'      => _x( 'Insert into Demo Type', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post).', 'pixel-tooltips' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this Demo Type', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'pixel-tooltips' ),
			'filter_items_list'     => _x( 'Filter  list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list".', 'pixel-tooltips' ),
			'items_list_navigation' => _x( ' list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation".', 'pixel-tooltips' ),
			'items_list'            => _x( ' list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list".', 'pixel-tooltips' ),
		);

		$supports = array(
			'title',			// post title
			'editor',			// post content
			'author',			// post author
			'thumbnail',		// featured image
			'excerpt',			// post excerpt
			'custom-fields',	// custom fields
			'comments',			// post comments
			'revisions',		// post revisions
			'post-formats',		// post formats
		);

		$args = array(
			'labels'				=> $labels,
			'supports' 				=> $supports,
			'public'				=> true,
			'publicly_queryable'	=> true,
			'show_ui'				=> true,
			'show_in_menu'			=> true,
			'query_var'				=> true,
			'rewrite'				=> array( 'slug' => 'demotype' ),
			'capability_type'		=> 'post',
			'has_archive'			=> true,
			'hierarchical'			=> false,
			'menu_position'			=> null,
		);

		register_post_type( 'demotype', $args );
		
	}

	/**
	 * ####################
	 * ### Activation/Deactivation hooks
	 * ####################
	 */
	 
	/*
	 * This function is called on activation of the plugin
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function activation_hook_callback(){

		//Your code
		
	}

}
