<?php
// validate color value
function x33_tag_validate_color($color = '')
{
    if (strlen($color) == 7 || strlen($color) == 6)
    {
        $color = strtoupper(substr($color, -6));
        if (preg_match("/[^0-9A-F]/", $color))
        {
            return false;
        }
        else
        {
            return '#' . $color;
        }
    }
    else
    {
        return false;
    }
}

// validate size value
function x33_tag_validate_size($size = '')
{
    if (strlen($size) > 0 && !preg_match("/\D/", $size))
    {
        return intval($size);
    }
    else
    {
        return false;
    }
}

// validate submitted size list for options page
function x33_tag_validate_size_list($str = '')
{
    $result = array();
    $array = explode('|', $str);
    foreach ($array as $value)
    {
        $value = x33_tag_validate_size($value);
        if ($value !== false)
        {
            $result[] = $value;
        }
    }
    return $result;
}

// validate submitted size list for options page
function x33_tag_validate_color_list($str = '')
{
    $result = array();
    $array = explode('|', $str);
    foreach ($array as $value)
    {
        $value = x33_tag_validate_color($value);
        if ($value !== false)
        {
            $result[] = $value;
        }
    }
    return $result;
}


// make color list
function x33_tag_make_color_list($list = array())
{
    $result = '';
    foreach ($list as $value)
    {
        $result .= x33_tag_format_color_list_item($value);
    }
    return $result;
}

// make size list
function x33_tag_make_size_list($list = array())
{
    $result = '';
    foreach ($list as $value)
    {
        $result .= x33_tag_format_size_list_item($value);
    }
    return $result;
}

function x33_tag_format_size_list_item($value = '')
{
    if (strlen($value) != 0)
    {
        $result = "<div class='size_list_item'><span>" . $value . "px</span><a href='#' class='rm_item'>remove</a><div style='clear:both'></div></div>";
        return $result;
    }
    else
    {
        return $value;
    }
}

function x33_tag_format_color_list_item($value = '')
{
    if (strlen($value) != 0)
    {
        $result = "<div class='color_list_item'><span style='color:" . $value . "'>" . $value . "</span><a href='#' class='rm_item'>remove</a><div style='clear:both'></div></div>";
        return $result;
    }
    else
    {
        return $value;
    }
}
?>