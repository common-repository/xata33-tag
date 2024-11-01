var color_to_switch;
var size_to_switch;

jQuery.noConflict();

// document onload routine
jQuery(document).ready(function(){

    jQuery("#loadingBar").ajaxStart(function(){
        jQuery(this).show();
    });
    
    jQuery("#loadingBar").ajaxStop(function(){
        jQuery(this).hide();
    });

    bind();
    updateColors();
    updateSizes();
});

// remove color from dom tree 
function rmColor(node)
{
    var parent = node.parent();
    parent.remove();
    color_to_switch = null;
    updateColors();
    return false;
}

// remove size from dom tree
function rmSize(node)
{
    var parent = node.parent();
    parent.remove();
    size_to_switch = null;
    updateSizes();
    return false;
}

// update hidden input with colors values
function updateColors()
{
    var str = '';
    jQuery("#color_list > div > span").each(function(){
        str += jQuery(this).text();
        str += '|';
    });
    jQuery("#color_list_hidden").val(str);
}

// update hidden input with sizes values
function updateSizes()
{
    var str = '';
    jQuery("#size_list > div > span").each(function(){
        str += jQuery(this).text().replace('px','');
        str += '|';
    });
    jQuery("#size_list_hidden").val(str);
}

// switch two colors in dom tree
function switchColors(node)
{
    if (color_to_switch != null)
    {
        node.clone(true).insertAfter(color_to_switch);
        color_to_switch.replaceAll(node);
        jQuery("#color_list > div").removeClass("selected_node");
        color_to_switch = null;
        updateColors();
    }
    else
    {
        color_to_switch = node;
        color_to_switch.addClass("selected_node");
    }
}

// switch two sizes in dom tree
function switchSizes(node)
{
    if (size_to_switch != null)
    {
        node.clone(true).insertAfter(size_to_switch);
        size_to_switch.replaceAll(node);
        jQuery("#size_list > div").removeClass("selected_node");
        size_to_switch = null;
        updateSizes();
    }
    else
    {
        size_to_switch = node;
        size_to_switch.addClass("selected_node");
    }
}

// unbind all events
function unbind()
{
    jQuery("#x33_tag_add_color").unbind();
    jQuery("#x33_tag_add_size").unbind();
    jQuery("#color_list > div > .rm_item").unbind();
    jQuery("#size_list > div > .rm_item").unbind();
    jQuery("#size_list > div").unbind();
    jQuery("#color_list > div").unbind();
}

// bind events to elements
function bind()
{
    jQuery("#x33_tag_add_color").bind('click', function(){
        addColor(jQuery(this).prev("input").val());
    });
    
    jQuery("#x33_tag_add_size").bind('click', function(){
        addSize(jQuery(this).prev("input").val());
    });
    
    jQuery("#color_list > div > .rm_item").bind('click',function(){
        rmColor(jQuery(this));
    });
    
    jQuery("#size_list > div > .rm_item").bind('click',function(){
        rmSize(jQuery(this));
    });
    
    jQuery("#size_list > div").bind('click',function(){
        switchSizes(jQuery(this));
    });
    
    jQuery("#color_list > div").bind('click',function(){
        switchColors(jQuery(this));
    });
}

// make ajax request to validate color and insert it into dom tree
function addColor(color)
{
    unbind();
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=add_color&color=" + color,
    success: function(data){
       jQuery("#color_list").append(data);
       updateColors();
       bind();
    }
    });
}

// make ajax request to validate size and insert it into dom tree
function addSize(size)
{
    unbind();
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=add_size&size=" + size,
    success: function(data){
       jQuery("#size_list").append(data);
       updateSizes();
       bind();
    }
    });
}