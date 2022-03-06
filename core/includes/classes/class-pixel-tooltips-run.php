<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

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
class Pixel_Tooltips_Run
{

	/**
	 * Our Pixel_Tooltips_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct()
	{
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
	private function add_hooks()
	{

		add_action('plugin_action_links_' . PIXELTOOLTIP_PLUGIN_BASE, array($this, 'add_plugin_action_link'), 20);

		// Check if shortcode is already registered for [tooltip] shortcode
		if (!shortcode_exists('tooltip')) {
			add_shortcode('tooltip', array($this, 'add_pixel_tooltip_shortcode_callback'));
		}

		if (!shortcode_exists('glossary')) {
			add_shortcode('glossary', array($this, 'add_pixel_tooltip_list_shortcode_callback'));
		}
		
		if (!shortcode_exists('tooltips')) {
			add_shortcode('tooltips', array($this, 'add_pixel_tooltip_list_shortcode_callback'));
		}
		
		add_action('admin_enqueue_scripts', array($this, 'enqueue_backend_scripts_and_styles'), 20);
		add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts_and_styles'), 20);
		add_action('init', array($this, 'add_custom_post_type'), 20);
		register_activation_hook(PIXELTOOLTIP_PLUGIN_FILE, array($this, 'activation_hook_callback'));
		register_deactivation_hook(PIXELTOOLTIP_PLUGIN_FILE, array($this, 'deactivation_hook_callback'));

		add_filter('manage_pixel_tooltip_posts_columns', array($this, 'pixel_tooltip_add_shortcode_column'));
		add_filter('manage_pixel_tooltip_posts_columns', array($this, 'pixel_tooltip_move_shortcode_column'));
		add_action('manage_pixel_tooltip_posts_custom_column', array($this, 'pixel_tooltip_add_shortcode_column_content'), 10, 2);
		// add_action('parse_query', 'pixel_tooltip_no_nopaging');
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
	public function add_plugin_action_link($links)
	{
		// get wp-admin home
		$links['our_shop'] = sprintf('<a href="%s" title="Settings" style="font-weight:700;">%s</a>', admin_url() . 'edit.php?post_type=pixel_tooltip', __('Settings', 'pixel-tooltips'));

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
	public function add_pixel_tooltip_shortcode_callback($attr = array(), $content = '')
	{
		// do something to $content

		$tooltip = '';
		$result = '';
		$has_result = false;

		$args = array(
			'post_type'      => 'pixel_tooltip',
			'posts_per_page' => -1,
		);
		$loop = new WP_Query($args);

		while ($loop->have_posts()) {
			$loop->the_post();

			$term = get_the_title();
			$termId = get_the_ID();


			$term_found = stripos($content, $term);

			if ($term_found !== false) {

				$tooltip .= '<span class = "pixel-tooltip-container" onmouseover="pixelTooltipFollow(this)">';
				$tooltip .= '<span class ="pixel-tooltip-term" data-toggle="pixel-tooltip" data-tooltip-id="' . $termId . '">';
				$tooltip .= '<a href = "' . get_permalink() . '">';
				$tooltip .= $term;
				$tooltip .= '</a>';
				$tooltip .= '</span>';

				$tooltip .= '<span class = "pixel-tooltip-content">';
				$tooltip .= get_the_content();
				$tooltip .= '</span>';
				$tooltip .= '</span>';

				$result = str_ireplace($term, $tooltip, $content);
				$has_result = true;
			}
		}

		wp_reset_postdata();

		if ($has_result === true) {

			// Add the tooltip script if not already added
			if (!wp_script_is('pixeltooltip-frontend-scripts', 'enqueued')) {
				wp_enqueue_script('pixeltooltip-frontend-scripts', PIXELTOOLTIP_PLUGIN_URL . 'core/includes/public/js/pixeltooltip-frontend.min.js', array(), PIXELTOOLTIP_VERSION, true);
			}

			// run shortcode parser recursively
			$result = do_shortcode($result);
			return $result;
		} else {
			// run shortcode parser recursively
			$content = do_shortcode($content);
			return $content;
		}
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
	public function enqueue_backend_scripts_and_styles()
	{
		wp_enqueue_style('pixeltooltip-backend-styles', PIXELTOOLTIP_PLUGIN_URL . 'core/includes/public/css/pixeltooltip-backend.css', array(), PIXELTOOLTIP_VERSION, 'all');
		wp_enqueue_script('pixeltooltip-backend-scripts', PIXELTOOLTIP_PLUGIN_URL . 'core/includes/public/js/pixeltooltip-backend.min.js', array(), PIXELTOOLTIP_VERSION, true);
	}


	/**
	 * Enqueue the frontend related scripts and styles for this plugin.
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function enqueue_frontend_scripts_and_styles()
	{
		wp_enqueue_style('pixeltooltip-frontend-styles', PIXELTOOLTIP_PLUGIN_URL . 'core/includes/public/css/pixeltooltip-frontend.css', array(), PIXELTOOLTIP_VERSION, 'all');
	}

	/**
	 * Add all of the available custom post types
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function add_custom_post_type()
	{

		$labels = array(
			'name'                  => _x('Pixel Tooltips', 'Post type general name', 'pixel-tooltips'),
			'singular_name'         => _x('Pixel Tooltip', 'Post type singular name', 'pixel-tooltips'),
			'menu_name'             => _x('Pixel Tooltips', 'Admin Menu text', 'pixel-tooltips'),
			'name_admin_bar'        => _x('Pixel Tooltip', 'Add New on Toolbar', 'pixel-tooltips'),
			'add_new'               => __('Add New', 'pixel-tooltips'),
			'add_new_item'          => __('Add New Pixel Tooltip', 'pixel-tooltips'),
			'new_item'              => __('New Pixel Tooltip', 'pixel-tooltips'),
			'edit_item'             => __('Edit Pixel Tooltip', 'pixel-tooltips'),
			'view_item'             => __('View Pixel Tooltip', 'pixel-tooltips'),
			'all_items'             => __('All Pixel Tooltips', 'pixel-tooltips'),
			'search_items'          => __('Search Pixel Tooltips', 'pixel-tooltips'),
			'parent_item_colon'     => __('Parent Pixel Tooltips:', 'pixel-tooltips'),
			'not_found'             => __('No Pixel Tooltips found.', 'pixel-tooltips'),
			'not_found_in_trash'    => __('No Pixel Tooltips found in Trash.', 'pixel-tooltips'),
			'featured_image'        => _x('Pixel Tooltip Cover Image', 'Overrides the "Featured Image" phrase for this post type.', 'pixel-tooltips'),
			'set_featured_image'    => _x('Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'pixel-tooltips'),
			'remove_featured_image' => _x('Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'pixel-tooltips'),
			'use_featured_image'    => _x('Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'pixel-tooltips'),
			'archives'              => _x('Pixel Tooltip archives', 'The post type archive label used in nav menus. Default "Post Archives".', 'pixel-tooltips'),
			'insert_into_item'      => _x('Insert into Pixel Tooltip', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post).', 'pixel-tooltips'),
			'uploaded_to_this_item' => _x('Uploaded to this Pixel Tooltip', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post).', 'pixel-tooltips'),
			'filter_items_list'     => _x('Filter Pixel Tooltips list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list".', 'pixel-tooltips'),
			'items_list_navigation' => _x('Pixel Tooltips list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation".', 'pixel-tooltips'),
			'items_list'            => _x('Pixel Tooltips list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list".', 'pixel-tooltips'),
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
			'rewrite'				=> array('slug' => 'pixel_tooltip'),
			'capability_type'		=> 'post',
			'has_archive'			=> true,
			'hierarchical'			=> false,
			'menu_position'			=> null,
		);

		register_post_type('pixel_tooltip', $args);
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
	public function activation_hook_callback()
	{

		//Your code

	}

	/*
	 * This function is called on deactivation of the plugin
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	public function deactivation_hook_callback()
	{

		//Your code

	}





	/**
	 * No Pagination for Pixel Tooltips Archive
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @return	void
	 */
	function pixel_tooltip_no_nopaging($query)
	{
		if (is_post_type_archive('pixel_tooltip')) {
			$query->set('nopaging', 1);
			$query->set('orderby', 'title');
			$query->set('order', 'ASC');
		}
	}


	/**
	 * In admin edit.php add a column before date for the shortcode to be copied and pasted
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$columns	The columns
	 *
	 * @return	array The columns with the shortcode column
	 */
	public function pixel_tooltip_add_shortcode_column($columns)
	{
		$columns['shortcode'] = 'Shortcode';
		return $columns;
	}


	/**
	 * For each column add the shortcode
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	string	$column_name	The column name
	 * @param	int		$post_id		The post ID
	 *
	 * @return	void
	 */
	public function pixel_tooltip_add_shortcode_column_content($column_name, $post_id)
	{
		if ($column_name == 'shortcode') {
			// Get title of tooltip
			$title = get_the_title($post_id);
			$value = '[tooltip]' . $title . '[/tooltip]';

			echo '<div class="tooltip-shortcode-container" style = "display: flex;">';
			echo '<input type="text" onfocus="this.select();" readonly="readonly" value="' . $value . '" class="large-text code">';
			// Copy to clipboard
			echo '<button class="button button-primary" onclick="event.preventDefault();this.previousElementSibling.select();document.execCommand(\'copy\');">Copy to Clipboard</button>';
			echo '</div>';
			// Change "Copy to Clipboard" to "Copied!" after click
			echo '<script>
					jQuery(document).ready(function($) {
						$(".tooltip-shortcode-container button").click(function() {
							$(this).text("Copied!");
							setTimeout(function() {
								$(".tooltip-shortcode-container button").text("Copy to Clipboard");
							}, 2000);
						});
					});
				</script>';
		}
	}


	/**
	 * Move the shortcode column to after title
	 *
	 * @access	public
	 * @since	1.0.0
	 *
	 * @param	array	$columns	The columns
	 *
	 * @return	array $columns with the shortcode column moved
	 */
	public function pixel_tooltip_move_shortcode_column($columns)
	{
		$new = [];
		foreach ($columns as $key => $value) {
			if ($key == 'author') {
				$new['shortcode'] = 'Shortcode';
			}
			$new[$key] = $value;
		}
		return $new;
	}







	public function add_pixel_tooltip_list_shortcode_callback()
	{
		$args = array(
			'post_type' => 'pixel_tooltip',
			'posts_per_page' => -1,
			'orderby' => 'title',
			'order' => 'ASC',
		);
		$query = new WP_Query($args);
		$posts = $query->posts;
		$output = '<div class ="pixel-tooltip-list-container">';
		$output .= '<ul class ="pixel-tooltip-list">';
		foreach ($posts as $post) {
			$output .= '<li><a href="' . get_permalink($post->ID) . '">' . $post->post_title . '</a></li>';
		}
		$output .= '</ul>';
		$output .= '</div>';
		return $output;
	}










	// End of class
}
