<?php
/**
 * Template for displaying single chapter content
 */

get_header();

// Get chapter data
$chapter_id = get_the_ID();
$chapter_title = get_post_meta($chapter_id, '_nv_chapter_title', true);
?>

<main class="w-full flex items-center justify-center px-6 flex-col mt-[64px] mb-10 flex-grow">
    <div
        class="content-container flex flex-col max-w-[984px] w-full justify-center items-start text-nv-chapter-text gap-4">
        <!-- Header Section -->
        <div class="mb-2 mt-10 text-start w-full">
            <h1 class="text-xl font-semibold mb-2">
                <?php the_title(); ?>
            </h1>

            <?php nv_construct_breadcrumb($chapter_id); ?>
        </div>

        <!-- Top Navigation -->
        <?php nv_display_chapter_navigation(); ?>

        <!-- Chapter Content -->
        <article class="content flex flex-col gap-4 p-4 w-full">
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
    </div>
</main>

<?php get_footer(); ?>