<?php

// add custom post type
function my_test_post_types() {
    //Campus post type
    register_post_type('campus', array(
        'capability_type' => 'campus',
        'map_meta_cap' => true,
        'supports' => array('title', 'editor', 'excerpt'), #'custom-fields'
        'rewrite' => array(
            'slug' => 'campuses'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Campuses',
            'add_new_item' => 'Add New Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus'
        ),
        'menu_icon' => 'dashicons-location-alt'
    ));
    //Event post type
    register_post_type('event', array(
        'capability_type' => 'event', //form custom user access
        'map_meta_cap' => true, //form custom user access
        'supports' => array('title', 'editor', 'excerpt'), #'custom-fields'
        'rewrite' => array(
            'slug' => 'events'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Events',
            'add_new_item' => 'Add New Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event'
        ),
        'menu_icon' => 'dashicons-flag'
    ));

    //program Post Type
    register_post_type('program', array(
        'supports' => array('title'), #'custom-fields'
        'rewrite' => array(
            'slug' => 'programs'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Program',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards'
    ));

    //professor Post Type
    register_post_type('professor', array(
        'show_in_rest' => true,
        'supports' => array('title', 'editor', 'thumbnail'), #'custom-fields'
        'public' => true,
        'labels' => array(
            'name' => 'professor',
            'add_new_item' => 'Add New professor',
            'edit_item' => 'Edit professor',
            'all_items' => 'All professors',
            'singular_name' => 'professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more'
    ));

    //Note Post Type
    register_post_type('note', array(
        'capability_type' => 'note', //smth unique - brand new permissions
        'map_meta_cap' => true, //require permission
        'show_in_rest' => true,
        'supports' => array('title', 'editor'), #'custom-fields'
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'notes',
            'add_new_item' => 'Add New note',
            'edit_item' => 'Edit note',
            'all_items' => 'All notes',
            'singular_name' => 'professor'
        ),
        'menu_icon' => 'dashicons-welcome-write-blog'
    ));

    //Like post type
    register_post_type('like', array(
        //'capability_type' => 'note', //smth unique - brand new permissions
        //'map_meta_cap' => true, //require permission
        //'show_in_rest' => true, //default = false
        'supports' => array('title'), #'custom-fields'
        'public' => false,
        'show_ui' => true,
        'labels' => array(
            'name' => 'likes',
            'add_new_item' => 'Add New like',
            'edit_item' => 'Edit like',
            'all_items' => 'All likes',
            'singular_name' => 'like'
        ),
        'menu_icon' => 'dashicons-heart'
    ));


}
add_action('init', 'my_test_post_types');