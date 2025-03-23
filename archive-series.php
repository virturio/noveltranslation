<?php get_header(); ?>

<?php
$args = [
    'post_type' => 'series',
    'posts_per_page' => -1,
];
$query = new WP_Query($args);

if ($query->have_posts()):
    while ($query->have_posts()):
        $query->the_post();
        nv_construct_series_card(get_the_ID());
    endwhile;
    wp_reset_postdata();
endif;


?>




<?php get_footer(); ?>