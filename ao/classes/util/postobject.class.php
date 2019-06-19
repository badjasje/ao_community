<?php
/**
 * Wordpress post class
 */
class PostObject extends PhpObject {
    /**
     * get_post_meta()
     * update_post_meta()
     * update_field()
     * getSome($key,$value)  // get some, returns array, using get_posts(args)
     * getAll() // returns array
     */

    function __construct($postData=null) {
        $meta = array_map( function( $a ){ return $a[0]; }, get_post_meta($postData->ID));
        $props = array_merge(json_decode(json_encode($postData),true), $meta, array('id' => $postData->ID));
        parent::__construct($props);
    }
}
