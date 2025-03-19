<?php get_header(); ?>

<?php
if (has_post_thumbnail()):
    the_post_thumbnail('full', array('class' => 'w-full h-full object-cover'));
endif;
?>

<?php get_footer(); ?>