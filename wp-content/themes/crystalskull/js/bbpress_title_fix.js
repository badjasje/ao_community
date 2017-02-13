/*global jQuery:false */
var forumtitle = jQuery('.bbpress .title_wrapper h1');
var newforumtitle = "<?php  the_title();?>";
forumtitle.html(newforumtitle);