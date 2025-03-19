<?php

if (!function_exists('the_chapters')) {
    function the_chapters()
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
                <?php echo get_the_excerpt(); ?>
            </p>
            <p class="text-[14px] text-base font-semibold text-nv-text-gray">
                <?php echo the_time(); ?>
            </p>
        </div>
        <?php
    }
}


?>