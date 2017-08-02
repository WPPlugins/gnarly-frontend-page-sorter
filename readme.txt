=== Gnarly Frontend Page Sort ===
Contributors: olie480
Tags: wordpress, admin, pages, page sorting, frontend
Requires at least: 3.9
Tested up to: 3.9.1
Stable tag: 1.0
License: GPLv3
Donate link: https://www.paypal.com/us/cgi-bin/webscr?cmd=_flow&SESSION=b-6EV4MS6gQ9Wgixrs6Hgv4aiG9ax1HjZjqOAX6RltwK92g5MxPQWNvHqxa&dispatch=50a222a57771920b6a3d7b606239e4d529b525e0b7e69bf0224adecfb0124e9b61f737ba21b081988ee87121ca8032a43984da2b890102b1
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Drag & Drop sorting of pages on the front end of your wordpress site. Great for sites that use get_pages to list sub pages of a parent page.

== Description ==

Gives user the ability to order sub-pages on the parent page from the frontend of Wordpress.

There are some backend plugins that do this, but I recently had a project that had about 30 parent pages, and those had sub-pages, and the sub-pages had sub-pages. We needed to find an alternative solution on the frontend because the majority of the plugins were drag and drop on the admin screen. This fixed this problem.

== Installation ==

This is the code I used to list the child pages on a parent page. I changed the sort_column to menu_order instead of the default post_title, and the sort_order is asc.

	<?php

		global $post;

        $args = array(          
            'hierarchical'  => 0,                       
            'child_of'      => 0,
            'parent'        => $post->ID,           
            'number'        => '',
            'sort_order'    =>  'asc',
            'sort_column'   =>  'menu_order'
        );

        $child_pages = get_pages( $args );  
    ?>

With the Child pages, I then created a custom loop (an example will be included later on in a separate file. I made 2 functions to spit out the pages of the parent page). From there I can add the gnarly tags to the parent container and the child items.

When you install the plugin, just find the template that controls your pages loop and make the following modifications. This example uses Twenty-Fourteen as an example.

    <div role="main" class="site-content" id="content" data-gnarly-sort='true'>

        <article data-gnarly-id="58" class="page-58 page" id="page-58">
            <div class="entry-content">
                <h2>Your Page Title</h2>
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #page-## -->    

        <article data-gnarly-id="57" class="page-57 page" id="page-57">
            <div class="entry-content">
                <h2>Your Page Title</h2>
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #page-## -->    

        <article data-gnarly-id="56" class="page-56 page" id="page-56">
            <div class="entry-content">
                <h2>Your Page Title</h2>
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #page-## -->    

        <article data-gnarly-id="55" class="page-55 page" id="page-55">
            <div class="entry-content">
                <h2>Your Page Title</h2>
            </div>
            <!-- .entry-content -->
        </article>
        <!-- #page-## -->    

    </div>

Notice the following code


    <div role="main" class="site-content" id="content" data-gnarly-sort="true">


The data-gnarly-sort='true' attribute tag. This tells the plug-in that it is the parent element of the sorter.

On each of the pages that get spit out, you must include the data-gnarly-id="58" in order to tell the sorter that it is a page item that will be drag and drop sorted using the page ID. This attribute is important because it tells wordpress what position it's in when you drag and drop each element by using the page ID. You can echo out the page ID by using the following PHP pre in the loop.


    <article data-gnarly-id="<?php the_ID(); ?>" class="page-58 page" id="page-58"> 


= Minimum Requirements =

* WordPress 3.9 or greater
* PHP version 5.2.4 or greater
* MySQL version 5.0 or greater

== Screenshots ==

1. Screenshots to come!

== Frequently Asked Questions ==

= I installed the plugin, I don't know what to do. =

Please follow the instructions in the readme.txt file in order to set up the plugin correctly. Everyone's themes are different, and I am currently working on a better solution to specify the parent and child elements

== Upgrade Notice ==

* No Upgrade Notices at this time. Initial Release

== Changelog ==

= 1.0 =
* Updated admin check in plugins main file

= 1.0.0 - 06/24/2014 =

* Initial Release