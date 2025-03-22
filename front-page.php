<?php get_header(); ?>

<!-- Main Content -->

<!-- Search and Filter Section -->
<div class="flex items-center justify-between mb-4 h-[44px]">
    <div class="flex gap-2 h-full">
        <!-- Date Filter -->
        <?php nv_construct_dropdown('date'); ?>
        <!-- Sort Filter -->
        <?php nv_construct_dropdown('sort-by'); ?>
    </div>

    <!-- Search Bar -->
    <?php nv_construct_searchbar(); ?>

    <!-- Ko-fi Button -->
    <?php nv_construct_kofi_button(); ?>
</div>

<!-- Chapter List -->
<?php the_archive_chapters(); ?>


<?php get_footer(); ?>