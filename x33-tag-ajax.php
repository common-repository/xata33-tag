<?php

require_once 'x33-tag-lib.php';
require_once 'x33-tag-options-lib.php';
require_once 'x33-tag-manage-lib.php';
require_once '../../../wp-blog-header.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';
$color  = isset($_POST['color']) ? $_POST['color'] : '';
$size  = isset($_POST['size']) ? $_POST['size'] : '';
$tag_title  = isset($_POST['tag_title']) ? $_POST['tag_title'] : '';
$tag_id  = isset($_POST['tag_id']) ? $_POST['tag_id'] : '';

switch ($action) {
	case 'add_color':
	   x33_tag_ajax_process_color($color);
	break;
	
	case 'add_size':
       x33_tag_ajax_process_size($size);
    break;
	
    case 'add_tag':
       x33_tag_ajax_add_tag($tag_title);
    break;
    
    case 'get_tags':
       x33_tag_ajax_get_tags();
    break;
    
    case 'tag_info':
       x33_tag_ajax_tag_info($tag_id);
    break;
    
    case 'rm_tag':
       x33_tag_ajax_rm_tag($tag_id);
    break;
    
	default:
	break;
}


function x33_tag_ajax_process_color($color = '')
{
	$color = x33_tag_validate_color($color);
    if ($color != false)
    {
    	echo x33_tag_format_color_list_item($color);
    }
    else
    {
    	echo '';
    }
}

function x33_tag_ajax_process_size($size = '')
{
    $size = x33_tag_validate_size($size);
    if ($size != false)
    {
        echo x33_tag_format_size_list_item($size);
    }
    else
    {
        echo '';
    }
}

function x33_tag_ajax_add_tag($titles = '')
{
	global $wpdb;
	global $tag_table;

	$titles = explode(',', $titles);
	$result = '';
	foreach ($titles as $key => $value)
	{
		$titles[$key] = trim(strip_tags(preg_replace("/\s+/",' ',$value)));
	}
	$titles = array_unique($titles);
	foreach ($titles as $title)
	{
		if (x33_tag_tag_exists($title) === false)
		{
			if (strlen($title) > 0 && !preg_match("/[^a-zA-Z0-9\s]/", $title))
			{
		      $sql = "INSERT INTO " . $tag_table . " VALUES(DEFAULT, '" . strtolower($wpdb->escape($title)) . "')";
		      $wpdb->query($sql);
			}
		}
	}
	echo $result;
}

function x33_tag_ajax_rm_tag($id = '')
{
    global $wpdb;
    global $tag_table;
    global $relations_table;
    
    if (x33_tag_tag_exists('', $id) === true)
    {
       $sql = "DELETE FROM " . $tag_table . " WHERE tag_id = '" . $wpdb->escape($id) . "'";
       $wpdb->query($sql);
       
       $sql = "DELETE FROM " . $relations_table . " WHERE tag_id = '" . $wpdb->escape($id) . "'";
       $wpdb->query($sql);
    }
    else
    {
        echo "Tag does not exist!";
    }
}

function x33_tag_ajax_get_tags()
{
	$result = '';
	$tags_result = x33_tag_get_tags();
	foreach ($tags_result as $row)
	{
		$result .= x33_tag_format_tag_list_item($row);
	}
	echo $result;
}

function x33_tag_ajax_tag_info($id = '')
{
	$tag_data = x33_tag_get_tag_info($id);
    echo x33_tag_format_tag_info($tag_data);
}
?>