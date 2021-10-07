<?php

//require file that contains search parameters (for REST API JSON)
require get_theme_file_path("/inc/search-route.php");

function university_custom_rest()
{
    //customising REST API
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {
            return get_the_author();
        }
    )); //3 fields: 1 post type you want customise, 2->propery name, 3->array how you want to manage
    register_rest_field('post', 'postCategory', array(
        'get_callback' => function () {
            return get_the_category();
        }
    ));
    register_rest_field('post', 'userNoteCount', array(
        'get_callback' => function () {
            return count_user_posts(get_current_user_id(), 'note');
        }
    ));
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = NULL)
{
    if (!$args['title']) {
        $args['title'] = get_the_title();
    }
    if (!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if (!$args['photo']) {
        if (get_field('page_banner_background_image')) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        } else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
    ?>

    <div class="page-banner">
        <div class="page-banner__bg-image"
             style="background-image: url(<?php echo $args['photo']; ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?php echo $args['subtitle']; ?></p>
            </div>
        </div>
    </div>

    <?php
}

function university_files()
{
    wp_enqueue_script('jQuery', 'https://code.jquery.com/jquery-3.5.1.min.js', NULL, '3.5', true);
    wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
    wp_enqueue_script('main-university-js2', get_theme_file_uri('/js/my-script.js'), NULL, '1.1', true);
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, '1.1');
    //wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
    wp_localize_script('main-university-js', 'universityData', array(
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest'), //nonce - number used once; For current user session;
    )); //3 args: 1-> include name of main (not exact name, but first, from above) js file,2->makeUp variable name,3->Array available in js
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
    //reg nav menus below
    /*register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');*/
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails'); //enable thumbnails -not everywhere
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

// add custom post type -> look at mu-plugins folder

function university_adjust_queries($query)
{
    if (!is_admin() && is_post_type_archive('program') && $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        //$query->set('posts_per_page', '1');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

//redirect subscriber accounts out from admin to homepage
add_action('admin_init', 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd()
{
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 && $ourCurrentUser->roles[0] == 'subscriber') {
        show_admin_bar(false);
    }
}

//customize login screen
add_filter('login_headerurl', 'ourHeaderUrl'); //1:value, 2:function
function ourHeaderUrl()
{
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS()
{
    wp_enqueue_style('university_main_styles', get_stylesheet_uri());
    wp_enqueue_style('custom-google-fonts', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

add_filter('login_headertitle', 'ourLoginTitle');
function ourLoginTitle()
{
    return get_bloginfo('name');
}

//force note posts to be private
add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2); // 4: num  of parameters, 3: priority num to execute
function makeNotePrivate($data, $postarr)
{
    if($data['post_type'] == 'note'){
        //limit to max 5 notes per user:
        if(count_user_posts(get_current_user_id(), 'note') > 4 && !$postarr['ID']) {
            die('You have reached your note limit');
        } //1: user account; which post type you want to count
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
    }
    if($data['post_type'] == 'note' && $data['post_status'] != 'trash'){
        $data['post_status'] = 'private';
    }
    return $data;
}
