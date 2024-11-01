<?php
/*
 * This file contains functions that Xata33 Tag uses.
 */

// plugin activation
function x33_tag_activate()
{
	global $tag_table;
	global $relations_table;
	global $alias_table;

	global $wpdb;

	// add some options
	add_option('x33_tag_colors', array(), 'Tag colors.', 'no');
	add_option('x33_tag_sizes', array(), 'Tag sizes.', 'no');
	add_option('x33_tag_friendly_urls', '0', 'Use friendly URLs.', 'no');
	add_option('x33_tag_auto_tag', '0', 'Use theme-independant tag output.', 'no');
	add_option('x33_tag_auto_cloud', '0', 'Use theme-independant tag cloud output.', 'no');

	// create tables to store data
	$sql = "CREATE TABLE IF NOT EXISTS " . $tag_table . " (
  		tag_id INT NOT NULL AUTO_INCREMENT,
  		tag_title VARCHAR(50) NOT NULL,
  		PRIMARY KEY (tag_id)
		)
		ENGINE = MyISAM
		CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$wpdb->query($sql);

	$sql = "CREATE TABLE IF NOT EXISTS " . $relations_table . " (
  		tag_id INT NOT NULL,
  		post_id INT NOT NULL,
  		PRIMARY KEY (tag_id, post_id)
		)
		ENGINE = MyISAM
		CHARACTER SET utf8 COLLATE utf8_general_ci;";

	$wpdb->query($sql);

	$sql = "CREATE TABLE IF NOT EXISTS " . $alias_table . " (
  		tag_id INT NOT NULL,
  		alias_id INT NOT NULL,
  		PRIMARY KEY (tag_id, alias_id)
		)
		ENGINE = MyISAM
		CHARACTER SET utf8 COLLATE utf8_general_ci;";

	//$wpdb->query($sql);
}

// plugin deactivation
function x33_tag_deactivate()
{
	global $tag_table;
	global $relations_table;
	global $alias_table;

	global $wpdb;

	// remove options
	delete_option('x33_tag_colors');
	delete_option('x33_tag_sizes');
	delete_option('x33_tag_friendly_urls');
	delete_option('x33_tag_auto_tag');
    delete_option('x33_tag_auto_cloud');
	

	// drop tables
	$sql = "DROP TABLE " . $tag_table;
	$wpdb->query($sql);

	$sql = "DROP TABLE " . $relations_table;
	$wpdb->query($sql);

	$sql = "DROP TABLE " . $alias_table;
	//$wpdb->query($sql);
}

// administration menu
function x33_tag_menu()
{
	add_options_page('Xata33 Tag Options', 'Xata33 Tag', 0, 'x33_tag_options', 'x33_tag_options_page');
	add_submenu_page('edit.php', 'Manage Xata33 Tag', 'Xata33 Tag', 0, 'x33_tag_manage', 'x33_tag_manage_page');
}

// load css
function x33_tag_load_css()
{
	$page = isset($_GET['page']) ? $_GET['page'] : '';
	if ($page == 'x33_tag_options') //FIXME admin_print_scripts-{page} didn't work for me =(
	{
		echo '<link rel="stylesheet" href="' . get_settings('siteurl') . '/wp-content/plugins/x33-tag/css/style.css" type="text/css" />';
	}
	elseif($page == 'x33_tag_manage') //FIXME admin_print_scripts-{page} didn't work for me =(
	{
		echo '<link rel="stylesheet" href="' . get_settings('siteurl') . '/wp-content/plugins/x33-tag/css/style.css" type="text/css" />';
	}
}

// load javascript
function x33_tag_load_js()
{
	// load js at options page only
	$page = isset($_GET['page']) ? $_GET['page'] : '';
	if ($page == 'x33_tag_options') //FIXME admin_print_scripts-{page} didn't work for me =(
	{
		wp_enqueue_script('jquery-1.2.1', get_settings('siteurl') . '/wp-content/plugins/x33-tag/js/jquery.js');
		wp_enqueue_script('x33-tag-options', get_settings('siteurl') . '/wp-content/plugins/x33-tag/js/options.js');
		wp_print_scripts();
		x33_tag_ajax_js();
	}
	elseif($page == 'x33_tag_manage') //FIXME admin_print_scripts-{page} didn't work for me =(
	{
		wp_enqueue_script('jquery-1.2.1', get_settings('siteurl') . '/wp-content/plugins/x33-tag/js/jquery.js');
		wp_enqueue_script('x33-tag-manage', get_settings('siteurl') . '/wp-content/plugins/x33-tag/js/manage.js');
		wp_print_scripts();
		x33_tag_ajax_js();
	}
	else
	{
	   wp_enqueue_script('x33-tag-general', get_settings('siteurl') . '/wp-content/plugins/x33-tag/js/general.js');
	}
}
?>