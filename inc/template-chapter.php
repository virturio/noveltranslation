<?php

function nv_the_chapters_query()
{
    global $post;
    $paged = get_query_var('page') ? absint(get_query_var('page')) : 1;

    $args = array(
        'post_type' => 'chapter',
        'posts_per_page' => 10,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
    );

    if ($post->post_type === 'series') {
        $args['meta_key'] = '_nv_related_series_id';
        $args['meta_value'] = $post->ID;
        $args['posts_per_page'] = -1;
    }

    $args = nv_filter_args($args);
    $query = new WP_Query($args);
    return $query;
}

if (!function_exists('the_archive_chapters')) {
    function the_archive_chapters()
    {
        ?>
        <div class="space-y-4 flex flex-col gap-4 items-center justify-center">
            <?php
            $query = nv_the_chapters_query();

            if ($query->have_posts()):
                while ($query->have_posts()):
                    $query->the_post(); ?>
                    <?php the_archive_chapter(); ?>
                <?php endwhile;
            endif;

            nv_display_pagination($query);
            wp_reset_postdata();
            ?>
        </div>
        <?php
    }
}


if (!function_exists('the_archive_chapter')) {
    function the_archive_chapter()
    {
        ?>
        <div
            class="bg-nv-card rounded-2xl px-8 py-6 space-y-2 cursor-pointer hover:bg-nv-card-hover transition-all duration-200">
            <a href="<?php echo get_the_permalink(); ?>">
                <h3 class="text-xl font-semibold line-clamp-2">
                    <?php echo get_the_title(); ?>
                </h3>
            </a>
            <p class="text-[14px] text-base font-semibold text-nv-text-gray">
                <?php the_excerpt(); ?>
            </p>
            <p class="text-[14px] text-base font-semibold text-nv-text-gray">
                <?php the_time(); ?>
            </p>
        </div>
        <?php
    }
}

if (!function_exists('nv_the_series_chapters')) {
    function nv_the_series_chapters()
    {
        $query = nv_the_chapters_query();
        ?>
        <div
            class="chapters-container space-y-1 max-h-[500px] overflow-y-scroll no-scrollbar [&>*:nth-child(odd)]:bg-nv-chapter-odd [&>*:nth-child(even)]:bg-transparent"> <?php
            if ($query->have_posts()):
                while ($query->have_posts()):
                    $query->the_post();
                    nv_the_series_chapter();
                endwhile;
            endif;
            wp_reset_postdata();
            ?></div><?php
    }
}

if (!function_exists('nv_the_series_chapter')) {
    function nv_the_series_chapter()
    {
        ?>
        <div class="chapter-item px-3 py-4 flex justify-between items-center border-b border-nv-border">
            <a href="<?php echo get_the_permalink(); ?>"
                class="text-md"><?php echo get_post_meta(get_the_ID(), '_nv_chapter_title', true) ?></a>
            <span class="text-xs text-nv-text-gray font-semibold"><?php the_time() ?></span>
        </div>
        <?php
    }
}


?>