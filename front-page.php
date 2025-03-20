<?php get_header(); ?>
<!-- Banner -->
<?php
if (has_post_thumbnail()): ?>
    <div class="absolute z-[-1] max-h-[300px] mt-[64px] w-full overflow-hidden">
        <img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="Novo Translation"
            class="w-full object-cover h-full translate-y-[-200px]" />
        <div class="absolute z-10 w-full h-full top-0 left-0 bg-gradient-to-b from-transparent to-nv-bg"></div>
    </div>
<?php endif; ?>

<!-- Main Content -->
<main class="w-full flex items-center justify-center px-6">
    <div class="max-w-[984px] py-8 w-full">
        <h2 class="text-xl font-bold text-center mb-[32px] mt-[110px]">
            Recent Posts
        </h2>

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
    </div>
</main>


<?php get_footer(); ?>