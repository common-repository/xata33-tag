=== Xata33 Tag ===
Contributors: atoi
Tags: tag, tags, post
Requires at least: 2.1.3
Tested up to:  2.1.3
Stable tag: trunk 

The main goal we wanted to achieve by writing this plugin was delicate tags appearance tweaking

== Description ==

The main goal we wanted to achieve by writing this plugin was delicate tags appearance tweaking. Now plugin supports tag color and size tweaking, pretty urls, automatic and manual tags and tag cloud output, tag preview and tagged posts count. Such features as tag aliases and search by tag are planned to be implemented in next release.

We are glad to introduce this plugin to the community. If you think that it is necessary and there is need in developing it further, we will do it.

Bug reports and feature requests are welcome.

== Installation ==

1. Uncompress the archive and put folder with plugin files into wp-content/plugins directory.
2. Enable plugin using administrator interface 

OPTIONS:

Go to "Options" -> "Xata33 Tag" menu. Here you can customize appearance of your tags and some options.

1. At "Size and Color Options" section you can manage appearance of your tags.

Note that nothing will be saved until you submit the form.

Number of sizes and number of colors are unlimited. 
The lower the size or color is in list the more popular tag it is associated with.
You can switch them by clicking the list item and then clicking the item you want to switch with.

2. At "Friendly URLs Options" you can enable URL rewriting (like "tags/some-tag")

Note that you must enable permalinks to use this option.

3. At "Auto tag Options" you can enable tags and tags cloud auto output. Tags are appended after each post. Tag cloud appended right after html <body> tag.


MANAGE:

1. Go to "Manage" -> "Xata33 Tag". Here you can view existing tags, add new tags, and delete them.


USAGE:
1. If you don't like how plugin automatically displays tags and tag cloud, you may output it manually. Just insert this code in template file for displaying tag cloud: 
<?php if (function_exists('x33_tag_display_cloud')) x33_tag_display_cloud(); ?>
and
<?php if (function_exists('x33_tag_display_post_tags')) x33_tag_display_post_tags($post); ?>
for displaying tags associated with some post.

Tag's size and color calculations are based on percent of posts, associated with this tag. 


== Frequently Asked Questions ==


== Screenshots ==

0. http://www.cult-f.net/ - live example
1. http://www.cult-f.net/wp-content/uploads/2008/02/options.jpg - Options page with size and color management.
2. http://www.cult-f.net/wp-content/uploads/2008/02/options2.jpg - Extra options
3. http://www.cult-f.net/wp-content/uploads/2008/02/manage.jpg - Manage tags


== Example ==

There are 100 posts with tags as your site.
There are four tags defined: tag1, tag2, tag3, tag4.
Tag1 has 10 posts, tag2 has 40 posts, tag3 has 20 posts, tag4 has 70 posts (post can have more than one tag).
There are 3 colors (color1, color2, color3) and 2 sizes defined (10px, 30px).
So there are 3 color categories and 2 size categories.
If a tag has less than 33% of tagged posts, it gets into 1st color category.
If it has more than 33% and less than 66% of tagged posts, in gets into 2nd color category.
If it has more than 66% of tagged posts, it gets into 3rd color category.
The same thing with colors.
So:
tag1 - 10 posts (10%); color:color1; size:10px;
tag2 - 40 posts (40%); color:color2; size:10px;
tag3 - 20 posts (20%); color:color1; size:10px;
tag4 - 70 posts (70%); color:color3; size:30px;