<?php
get_header();

while (have_posts()) {
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox-with-home-link">
            <p><a href="<?php echo get_post_type_archive_link('program'); ?>" class="metabox__blog-home-link"><i
                            class="fa fa-home"></i>
                    Programs home</a><span class="metabox__main"><?php the_title(); ?></span></p>
        </div>

        <div class="generic-content">
            <?php the_field('main_body_content'); ?>
        </div>

        <?php
        $relatedProfessors = new WP_Query(array(
            'posts_per_page' => -1, # -1 return all
            'post_type' => 'professor',
            'orderby' => 'title', #'post_date' <- default behaviour 'rand' random, 'title', 'meta_value'
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',
                )
            )
        ));

        if ($relatedProfessors->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium"> ' . get_the_title() . ' Professors</h2>';
            echo '<ul class="professor-cards">';
            while ($relatedProfessors->have_posts()) {
                $relatedProfessors->the_post();
                ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>"
                             alt="Professor">
                        <span class="professor-card__name"><?php the_title(); ?></span>
                    </a>
                </li>
            <?php }
            echo '</ul>';
        }
        wp_reset_postdata();
        //        END

        $today = date('Ymd');
        $homepageEvents = new WP_Query(array(
            'posts_per_page' => 2, # -1 return all
            'post_type' => 'event',
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num', #'post_date' <- default behaviour 'rand' random, 'title', 'meta_value'
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'related_programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"',
                )
            )
        ));

        if ($homepageEvents->have_posts()) {
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium"> Latest' . get_the_title() . ' Events</h2>';

            while ($homepageEvents->have_posts()) {
                $homepageEvents->the_post();


                get_template_part('template-parts/content-event');
            }
        }
        ?>

    </div>
    <?php
}

get_footer();
?>