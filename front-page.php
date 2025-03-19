<?php get_header(); ?>
<!-- Banner -->
<?php
if (has_post_thumbnail()): ?>
    <div class="absolute z-[-1] max-h-[300px] mt-[24px] w-full overflow-hidden">
        <img src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="Novo Translation"
            class="w-full object-cover h-full translate-y-[-200px]" />
        <div class="absolute z-10 w-full h-full top-0 left-0 bg-gradient-to-b from-transparent to-novo-bg"></div>
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
                <?php novo_construct_dropdown('date'); ?>
                <!-- Sort Filter -->
                <?php novo_construct_dropdown('sort-by'); ?>
            </div>

            <!-- Search Bar -->
            <?php novo_construct_searchbar(); ?>

            <!-- Ko-fi Button -->
            <a href="https://ko-fi.com/F2F21C4DA1"
                class="kofi-button bg-novo-kofi px-6 h-[44px] rounded-lg flex items-center gap-3 max-sm:h-[44px] max-sm:w-[44px] max-sm:p-0">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/kofi-cup.png'); ?>"
                    alt='Buy Me a Coffee at ko-fi.com' target='_blank' class="kofi-cup h-5 max-sm:height-[18px]" />
                <span class="kofi-text max-sm:hidden text-base font-semibold">Buy Me a Coffee</span>
            </a>
        </div>

        <!-- Chapter List -->
        <div class="space-y-4 flex flex-col gap-4 items-center justify-center">
            <?php
            $paged = get_query_var('page') ? absint(get_query_var('page')) : 1;

            $args = array(
                'post_type' => 'chapter',
                'posts_per_page' => 5,
                'orderby' => 'date',
                'order' => 'DESC',
                'paged' => $paged,
            );

            $args = novo_filter_args($args);

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    ?>
                    <a href="<?php echo get_the_permalink(); ?>">
                        <div
                            class="bg-novo-card rounded-2xl px-8 py-6 space-y-2 cursor-pointer hover:bg-novo-card-hover transition-all duration-200">
                            <h3 class="text-xl font-semibold line-clamp-2">
                                <?php echo get_the_title(); ?>
                            </h3>
                            <p class="text-[14px] text-base font-semibold text-novo-text-gray">
                                <?php echo get_the_excerpt(); ?>
                            </p>
                        </div>
                    </a>
                    <?php
                }

                novo_construct_paginated_links($query);
            }
            wp_reset_postdata();
            ?>



        </div>
    </div>
</main>


<?php get_footer(); ?>