<?php
/**
 * Template for displaying single chapter content
 */

get_header();

// Get chapter data
$chapter_id = get_the_ID();
$chapter_title = get_post_meta($chapter_id, '_nv_chapter_title', true);
?>

<!-- Header Section -->
<div class="mb-4 text-start w-full">
    <h1 class="text-xl font-semibold mb-2">
        <?php the_title(); ?>
    </h1>

    <?php nv_construct_breadcrumb(); ?>
</div>

<!-- Top Navigation -->
<?php nv_display_chapter_navigation(); ?>

<!-- Chapter Content -->
<article class="content flex flex-col gap-4 py-4 w-full">
    <?php if ($chapter_title): ?>
        <h2 class="font-semibold m-auto text-center text-xl">
            <?php echo esc_html($chapter_title); ?>
        </h2>
    <?php endif; ?>

    <div class="chapter-content prose prose-lg max-w-none">
        <?php the_content(); ?>
    </div>
</article>

<!-- Bottom Navigation -->
<?php nv_display_chapter_navigation(); ?>
<?php get_footer(); ?>