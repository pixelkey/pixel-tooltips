<?php

function tcgg_register_my_cpts_glossary_term()
{

	/**
	 * Post Type: Glossary Terms.
	 */

	$labels = [
		"name" => __("Glossary Terms", "understrap"),
		"singular_name" => __("Glossary Term", "understrap"),
		"menu_name" => __("Glossary", "understrap"),
		"all_items" => __("All Glossary Terms", "understrap"),
		"add_new" => __("Add New", "understrap"),
		"add_new_item" => __("Add New Term", "understrap"),
		"edit_item" => __("Edit Term", "understrap"),
		"new_item" => __("New Term", "understrap"),
		"view_item" => __("View Term", "understrap"),
		"view_items" => __("View Glossary Terms", "understrap"),
		"search_items" => __("Search Glossary Terms", "understrap"),
		"not_found" => __("Glossary term not found", "understrap"),
	];

	$args = [
		"label" => __("Glossary Terms", "understrap"),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => "glossary",
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => ["slug" => "glossary", "with_front" => true],
		"query_var" => true,
		"supports" => ["title", "editor", "thumbnail"],
		"taxonomies" => ["category", "post_tag"],
	];

	register_post_type("glossary_term", $args);
}

add_action('init', 'tcgg_register_my_cpts_glossary_term');





// No Pagination for Glossary Archive
function no_nopaging($query)
{
	if (is_post_type_archive('glossary_term')) {
		$query->set('nopaging', 1);
		$query->set('orderby', 'title');
		$query->set('order', 'ASC');
	}
}
add_action('parse_query', 'no_nopaging');




// In admin edit.php add a column before date for the shortcode to be copied and pasted
function tcgg_add_shortcode_column($columns)
{
	$columns['shortcode'] = __('Shortcode', 'understrap');
	return $columns;
}
add_filter('manage_glossary_term_posts_columns', 'tcgg_add_shortcode_column');


// For each column add the shortcode
function tcgg_add_shortcode_column_content($column_name, $post_id)
{
	if ($column_name == 'shortcode') {
		// Get title of glossary term
		$title = get_the_title($post_id);
		echo '<div class="glossary-shortcode-container" style = "display: flex;">';
		echo '<input type="text" onfocus="this.select();" readonly="readonly" value="[tooltip]' . $title . '[/tooltip]" class="large-text code">';
		// Copy to clipboard
		echo '<button class="button button-primary" onclick="event.preventDefault();this.previousElementSibling.select();document.execCommand(\'copy\');">Copy to Clipboard</button>';
		echo '</div>';
		// Change "Copy to Clipboard" to "Copied!" after click
		echo '<script>
					jQuery(document).ready(function($) {
						$(".glossary-shortcode-container button").click(function() {
							$(this).text("Copied!");
							setTimeout(function() {
								$(".glossary-shortcode-container button").text("Copy to Clipboard");
							}, 2000);
						});
					});
				</script>';
	}
}
add_action('manage_glossary_term_posts_custom_column', 'tcgg_add_shortcode_column_content', 10, 2);


// Move the shortcode column to before date
function tcgg_move_shortcode_column($columns)
{
	$new = [];
	foreach ($columns as $key => $value) {
		if ($key == 'categories') {
			$new['shortcode'] = __('Shortcode', 'understrap');
		}
		$new[$key] = $value;
	}
	return $new;
}
add_filter('manage_glossary_term_posts_columns', 'tcgg_move_shortcode_column');