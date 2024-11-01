<?php
// get list of all tags or tags for certain post(optional). also can get only one tag
// result is an array of objects 
function x33_tag_get_tags($tag_id = '', $post_id = '')
{
    global $tag_table;
    global $relations_table;
    global $wpdb;

    $sql = "SELECT * FROM " . $tag_table;
    if (!empty($tag_id) || !empty($post_id))
    {
        $sql .= " tt LEFT JOIN " . $relations_table . " rt ON tt.tag_id = rt.tag_id WHERE 1";
        $sql .= !empty($tag_id) ? " AND tt.tag_id = '" . $wpdb->escape($tag_id) . "'" : '';
        $sql .= !empty($post_id) ? " AND rt.post_id = '" . $wpdb->escape($post_id) . "'" : '';
    }
    $sql .= " ORDER BY tag_title";
    return $wpdb->get_results($sql);
}

// get detailed info about some tag
function x33_tag_get_tag_info($tag_id = '')
{
	// get first item from array 
    $tag_data = array_shift(x33_tag_get_tags($tag_id));
    // check if tag query returned correct title (tag exists)
    $tag_title = $tag_data->tag_title;
    if (strlen($tag_title) > 0)
    {
	    $posts = x33_tag_tagged_posts_num($tag_id);
	    $color = x33_tag_get_tag_color($tag_id);
	    $size = x33_tag_get_tag_size($tag_id);
	    
	    $result = array(
	        'id' => $tag_id,
            'title' => $tag_title,
	        'posts' => $posts,
	        'color' => $color,
	        'size' => $size
	    );
	
	    return $result;
    }
    else
    {
    	return false;
    }
}

// get number of tagged posts (for some tag - optional)
function x33_tag_tagged_posts_num($tag_id = '')
{
    global $relations_table;
    global $wpdb;
    
    $sql = "SELECT COUNT(DISTINCT post_id) FROM " . $relations_table;
    if(!empty($tag_id))
    {
        $sql .= " WHERE tag_id = '" . $wpdb->escape($tag_id) . "'";
    }
    return $wpdb->get_var($sql);
}

// make html for tag info
function x33_tag_format_tag_info($tag_data = array())
{
    $result = '';
    if ($tag_data !== false)
    {
        $result .= xx3_tag_format_cloud_item($tag_data);
        $result .= '<p>Posts number: <span>' . $tag_data['posts'] . '</span></p>';
        $result .= !empty($tag_data['color']) ? '<p>Color: <span>' . $tag_data['color'] . '</span></p>' : '';
        $result .= !empty($tag_data['size']) ? '<p>Size: <span>' . $tag_data['size'] . 'px</span></p>' : '';
        $result .= '<p><input type="button" class="rm_tag" value="Delete tag" />';
    }
    return $result;
}

// make html dropdown for tag list
function x33_tag_make_tag_list($list = array())
{
    $result = '<select id="tag_list" name="tag_list">';
    foreach ($list as $row)
    {
        $result .= x33_tag_format_tag_list_item($row);
    }
    $result .= '</select>';
    return $result;
}

// make html for a single item of tag list
function x33_tag_format_tag_list_item($row = '')
{
    if (is_object($row) && array_key_exists('tag_id',get_object_vars($row)) && array_key_exists('tag_title',get_object_vars($row)))
    {
        $result = '<option value="' . $row->tag_id . '">' . $row->tag_title . '</option>';
        return $result;
    }
    else
    {
        return '';
    }
}

// check if tag with specified title or id exists
function x33_tag_tag_exists($title = '', $id = '')
{
    global $tag_table;
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM " . $tag_table . " WHERE tag_id = '" . $wpdb->escape($id) . "' OR tag_title = '" . $wpdb->escape($title) . "'";
    $num = $wpdb->get_var($sql);
    return $num == '0' ? false : true;
}

// get color for a tag. color depends on tag's posts number
function x33_tag_get_tag_color($id = '')
{
    if (x33_tag_tag_exists('', $id) === true)
    {
        $tagged_posts = x33_tag_tagged_posts_num();
        $colors = get_option('x33_tag_colors');
        $colors_count = count($colors);
        $posts_count = x33_tag_tagged_posts_num($id);        
        $default_color = $colors_count > 0 ? $colors[0] : '';
        
        if ($tagged_posts > 0 && $colors_count > 0)
        {
            $posts_delta = $tagged_posts/$colors_count;
            $colors_index = ceil($posts_count/$posts_delta) - 1;
            return $colors_index < 0 ? $default_color : $colors[$colors_index];
        }
        else
        {
            return $default_color;
        }
    }
    else
    {
        return '';
    }
}

// get size for a tag. size depends on tag's posts number

function x33_tag_get_tag_size($id = '')
{
    if (x33_tag_tag_exists('', $id) !== false)
    {
        $tagged_posts = x33_tag_tagged_posts_num();
        $sizes = get_option('x33_tag_sizes');
        $sizes_count = count($sizes);
        $posts_count = x33_tag_tagged_posts_num($id);        
        $default_size = $sizes_count > 0 ? $sizes[0] : '';
        
        if ($tagged_posts > 0 && $sizes_count > 0)
        {
            $posts_delta = $tagged_posts/$sizes_count;
            $sizes_index = ceil($posts_count/$posts_delta) - 1;
            return $sizes_index < 0 ? $default_size : $sizes[$sizes_index];
        }
        else
        {
            return $default_size;
        }
    }
    else
    {
        return '';
    }
}

// save tags to database
function x33_tag_save_post_tags($post_id)
{
    global $tag_table;
    global $relations_table;
    global $wpdb;

    $tag_list = isset($_POST['x33_tags']) ? $_POST['x33_tags'] : '';
    $tag_list = explode(',', $tag_list);
    foreach ($tag_list as $key => $value)
    {
        $tag_list[$key] = htmlspecialchars(trim($value));
    }
    $tag_list = array_unique($tag_list);
    
    // delete old relations
    $sql = "DELETE FROM " . $relations_table . " WHERE post_id = '" . $wpdb->escape($post_id) . "'";
    $wpdb->query($sql);
    
    foreach ($tag_list as $tag_title)
    {
        x33_tag_assign_tag_to_post($tag_title, $post_id);
    }
}

// add record to relations database
function x33_tag_assign_tag_to_post($tag_title = '', $post_id = '')
{
    global $tag_table;
    global $relations_table;
    global $wpdb;
    
    $tag_id = x33_tag_get_tag_by_title($tag_title);
    if (empty($tag_id))
    {
        $tag_id = x33_tag_add_tag($tag_title);
    }
    if ($tag_id !== false)
    {
        $sql = "INSERT INTO " . $relations_table . " VALUES('" . $tag_id . "','" . $post_id . "')";
        $wpdb->query($sql);
    } 
}

// get tag by it's title
function x33_tag_get_tag_by_title($title = '')
{
    global $tag_table;
    global $wpdb;
    
    $sql = "SELECT tag_id FROM " . $tag_table . " WHERE tag_title = '" . $wpdb->escape($title) . "'";
    return $wpdb->get_var($sql);
}

// insert record into tags table
function x33_tag_add_tag($title)
{
    global $tag_table;
    global $wpdb;
    
    $title = trim(strip_tags(preg_replace("/\s+/",' ',$title)));
    if (strlen($title) > 0 && !preg_match("/[^a-z0-9\s]/", $title) && x33_tag_tag_exists($title) === false)
    {
        $sql = "INSERT INTO " . $tag_table . " VALUES(DEFAULT, '" . strtolower($wpdb->escape($title)) . "')";
        $wpdb->query($sql);
        return $wpdb->insert_id;
    }
    else
    {
    	return false;
    }
}

// add tag input to edit post form
function x33_tag_display_tag_input()
{
    global $post;
    
    $result = '';
    $tags_html = '';
    $post_tags_html = '';
    $post_id = '';
    $post_tags = array();
    $post_tags_array = array();
    
    if (is_object($post) && array_key_exists('ID',get_object_vars($post)))
    {
        $post_id = $post->ID;
    }
    
    $all_tags = x33_tag_get_tags();
    
    if (is_numeric($post_id))
    {
        $post_tags = x33_tag_get_tags('', $post_id);
    }
    foreach ($all_tags as $row)
    {
        $tags_html .= "<a href='#' onclick='x33_add_tag(this); return false;' >" . $row->tag_title . '</a> ';
    }
    
    foreach ($post_tags as $row) {
        $post_tags_array[] = $row->tag_title;
    }
    
    $post_tags_html = implode(', ', $post_tags_array);
    
    $result .= "<div class='dbx-content'>";
    $result .= "<h3 class='dbx-handle'>Xata33 Tags (comma separated list)</h3>";
    $result .= "<input type='text' id='x33_tags' name='x33_tags' style='width:98%' value='" . $post_tags_html . "'/><br />";
    $result .= "Add existing tag: " . trim($tags_html);
    $result .= "</div>";
    echo $result;
}

// remove tag relations to deleted post
function x33_tag_delete_post($post_id)
{
	global $relations_table;
    global $wpdb;
    
	$sql = "DELETE FROM " . $relations_table . " WHERE post_id = '" . $wpdb->escape($post_id) . "'";
	$wpdb->query($sql);
}

// display post tags after post
function x33_tag_auto_display_post_tags($content)
{
	if (get_option('x33_tag_auto_tag') == '1')
	{
		global $post;
		if (is_object($post) && array_key_exists('ID',get_object_vars($post)))
		{
			$tags = x33_tag_get_tags('', $post->ID);
		}
		
		$result = '';
		if (!empty($tags))
		{
			$site_url = get_option('siteurl');
			$base_url = $site_url . '/?x33tag=';
		    if (get_option('x33_tag_friendly_urls') == '1')
	        {
	            $base_url = $site_url . '/tag/';
	        }
			$result .= '<p>Tags: ';
			foreach ($tags as $tag)
			{
			   $result .= '<a href="' . $base_url . preg_replace('/\s/', '-', $tag->tag_title) . '">' . $tag->tag_title . '</a> ';
			}
			$result .= '</p>';
		}
		$content .= $result;
	}
	return $content;
}

// display post tags on demand
function x33_tag_display_post_tags($post = '')
{
    if (is_object($post) && array_key_exists('ID',get_object_vars($post)))
    {
        $tags = x33_tag_get_tags('', $post->ID);
    }
    
    $result = '';
    if (!empty($tags))
    {
        $site_url = get_option('siteurl');
        $base_url = $site_url . '/?x33tag=';
        if (get_option('x33_tag_friendly_urls') == '1')
        {
            $base_url = $site_url . '/tag/';
        }
        foreach ($tags as $tag)
        {
           $result .= '<a href="' . $base_url . preg_replace('/\s/', '-', $tag->tag_title) . '">' . $tag->tag_title . '</a> ';
        }
    }
    echo $result;
}

function x33_tag_posts_join($join) {
    if (get_query_var("x33tag") != "") {
        global $wpdb;
        global $tag_table;
        global $relations_table;

        $join .= " INNER JOIN " . $relations_table . " rt on " . $wpdb->posts . ".ID  = rt.post_id INNER JOIN " . $tag_table . " tt on rt.tag_id = tt.tag_id ";
    }
    return $join;
}

function x33_tag_posts_where($where) {
    $tag = trim(strtolower(preg_replace("/\-/",' ',get_query_var("x33tag"))));
    if ($tag != "") {
        global $wpdb;
        global $wp_query;

        $wp_query->is_home=false;

        $where .= " AND tt.tag_title = '" . $wpdb->escape($tag) . "'";
    }
    return $where;
}

// auto display cloud
function x33_tag_auto_display_cloud()
{
	if (get_option('x33_tag_auto_cloud') == '1')
	{
	    $result .= '<div class="x33tag_cloud">';
	    $tags = x33_tag_get_tags();
	    foreach ($tags as $tag)
	    {
	        $result .= xx3_tag_format_cloud_item($tag);
	    }
	    $result .= '</div>';
        echo $result;
    }
}

// display cloud on demand
function x33_tag_display_cloud()
{
	$result = '';
	$tags = x33_tag_get_tags();
	foreach ($tags as $tag)
	{
		$result .= xx3_tag_format_cloud_item($tag);
	}
	echo $result;
}

// format tag according to it's rating
function xx3_tag_format_cloud_item($tag)
{
	$result = '';
	
	$site_url = get_option('siteurl');
	$base_url = $site_url . '/?x33tag=';
	
    if (get_option('x33_tag_friendly_urls') == '1')
    {
        $base_url = $site_url . '/tag/';
    }
    
    if (is_object($tag) && array_key_exists('tag_id',get_object_vars($tag)))
    {
	   $tag_id = $tag->tag_id;
    }
    elseif(is_array($tag) && array_key_exists('id', $tag))
    {
    	$tag_id = $tag['id']; 
    }
	$tag = x33_tag_get_tag_info($tag_id);
	if ($tag != false)
	{
		$color = !empty($tag['color']) ? "color:" . $tag['color'] .';' : ''; 
		$size = !empty($tag['size']) ? "font-size:" . $tag['size'] . "px;" : '';
		
		
		$result .= '<a href="' . $base_url . preg_replace('/\s/', '-', $tag['title']) . '" style="'. $color . $size .'">' . $tag['title'] . '</a> ';
	}
	return $result;
}

// rewrite rules
function x33_tag_rewrite_rules($rules) 
{
    if (get_option('x33_tag_friendly_urls') == '1')
    {
        global $wp_rewrite;

        $wp_rewrite->add_rewrite_tag('%tag%', '([^/]+)', 'x33tag=');

        // without trailing slash
        $rules = $wp_rewrite->generate_rewrite_rules('/tag/%tag%') + $rules;
    }
    return $rules;
}

// add tag var to query string
function x33_tag_query_vars($vars) {
    $vars[] = 'x33tag';

    return $vars;
}
?>