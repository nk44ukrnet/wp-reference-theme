<?php
get_header();

while(have_posts()){
    the_post();
    pageBanner();
    ?>
    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox-with-home-link">
            <p><a href="<?php echo site_url('/blog'); ?>" class="metabox__blog-home-link"><i class="fa fa-home"></i> Blog home</a><span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time('j F Y G:i a') ?> in <?php echo get_the_category_list(','); ?></span></p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>
    </div>
<?php
}

get_footer();
?>