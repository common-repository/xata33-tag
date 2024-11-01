// prevent conflicts
jQuery.noConflict();

// document onload routine
jQuery(document).ready(function(){

    jQuery("#loadingBar").ajaxStart(function(){
        jQuery(this).show();
        hide_message();
    });
    
    jQuery("#loadingBar").ajaxStop(function(){
        jQuery(this).hide();
    });

    bind();
    update_tags();
});

// bind events to dom elements
function bind()
{
    jQuery("#x33_tag_add_tag").bind('click', function(){
    	add_tag(jQuery("#x33_tag_tag_field").val());
        return false;
    });
    
    jQuery("#tag_list").bind('change', function(){
        tag_info(jQuery(this).val());
        return false;
    });
    
    jQuery(".rm_tag").bind('click', function(){
        delete_tag(jQuery("#tag_list").val());
        return false;
    });
}

// unbind events
function unbind()
{
    jQuery("#x33_tag_add_tag").unbind();
    jQuery("#tag_list").unbind();
    jQuery("#.rm_tag").unbind();
}

// show message returned by ajax response 
function show_message(text)
{
    jQuery("#message").empty().append(text).show();	
}

// hide message
function hide_message()
{
	jQuery("#message").hide().empty();
}

// make ajax request to get detailed tag information
function tag_info(id)
{
    unbind();
    jQuery("#tag_info").hide().empty();
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=tag_info&tag_id=" + id,
    success: function(data){
       jQuery("#tag_info").append(data).show();
       bind();
    }
    });
}

// make ajax request to add new tag and insert it into dom tree
function add_tag(title)
{
    unbind();
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=add_tag&tag_title=" + urlencode(title),
    success: function(data){
       if (data != '')
       {
        show_message(data);
       }
       bind();
       update_tags();
    }
    });
}

// make ajax request to remove a tag from database and dom tree
function delete_tag(id)
{
    unbind();
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=rm_tag&tag_id=" + id,
    success: function(data){
       if (data != '')
       {
        show_message(data);
       }
       jQuery("#tag_info").hide().empty();
       bind();
       update_tags();     
    }
    });
}

// make ajax request to update tag list
function update_tags()
{
    jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: "action=get_tags",
    success: function(data){
        jQuery("#tag_list").empty().append(data);
        jQuery("#tag_list").trigger('change');     
    }
    });
}

// urlencode
function urlencode(str) {
	str = escape(str);
	str = str.replace('+', '%2B');
	str = str.replace('%20', '+');
	str = str.replace('*', '%2A');
	str = str.replace('/', '%2F');
	str = str.replace('@', '%40');
	return str;
}
