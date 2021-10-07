<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox-with-home-link">
            <p><a href="<?php echo get_post_type_archive_link('event'); ?>" class="metabox__blog-home-link"><i
                            class="fa fa-home"></i> Events home</a><span
                        class="metabox__main"><?php the_title(); ?></span></p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
        $relatedPrograms = get_field('related_programs');

        if ($relatedPrograms) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Related program(s):</h2>';
            echo '<ul class="link-list min-list">';
            foreach ($relatedPrograms as $program) { ?>
                <li><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title($program); ?></a></li>
            <?php }
            echo '</ul>';
        }
        ?>

    </div>
    <?php
}

get_footer();
?>