<?php
/*
 Plugin Name: Xata33 Tag
 Description: Enables tags for posts. Tags appearance is highly configurable.
 Author: xata33 team
 Version: 0.2
 Author URI: http://www.cult-f.net/
*/

require_once 'x33-tag-lib.php';
require_once 'x33-tag-options-lib.php';
require_once 'x33-tag-manage-lib.php';
require_once 'x33-tag-options-page.php';
require_once 'x33-tag-manage-page.php';
require_once 'x33-tag-ajax-js.php';

// set name for a tables we will use
$tag_table 		= $wpdb->prefix . "x33_tags";
$relations_table = $wpdb->prefix . "x33_tags2posts";
$alias_table 	= $wpdb->prefix . "x33_tag_aliases";

// handle activation and deactivation of plugin
register_activation_hook(__FILE__,'x33_tag_activate');
register_deactivation_hook(__FILE__,'x33_tag_deactivate');

// adds extra menu items
add_action('admin_menu', 'x33_tag_menu');

// load jquery and our js
add_action('admin_print_scripts', 'x33_tag_load_js');

// load css
add_action('admin_head', 'x33_tag_load_css');

// add or edit tags
add_action('simple_edit_form', 'x33_tag_display_tag_input');
add_action('edit_form_advanced', 'x33_tag_display_tag_input');
add_action('edit_page_form', 'x33_tag_display_tag_input');

// save tags
add_action('publish_post', 'x33_tag_save_post_tags');
add_action('edit_post', 'x33_tag_save_post_tags');
add_action('save_post', 'x33_tag_save_post_tags');

add_action('delete_post', 'x33_tag_delete_post');

// display tags
add_filter('the_content', 'x33_tag_auto_display_post_tags');
add_filter('get_header', 'x33_tag_auto_display_cloud');

add_filter('posts_join', 'x33_tag_posts_join');
add_filter('posts_where', 'x33_tag_posts_where');

// URL rewriting
add_filter('rewrite_rules_array', 'x33_tag_rewrite_rules');
add_filter('query_vars', 'x33_tag_query_vars');

?>