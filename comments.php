<section class="<?php echo esc_attr(nv_get_comment_section_classes()); ?>">
    <?php if (!comments_open()): ?>
        <p class="text-sm text-nv-text">
            <?php esc_html_e('Comments are closed.', DOMAIN); ?>
        </p>


    <?php else: ?>
        <div class="flex justify-between items-center mb-6 text-nv-text">
            <h2 class="text-lg lg:text-2xl font-bold">
                <?php echo esc_html(get_comments_number()); ?>
                <span>Comments</span>
            </h2>
        </div>

        <?php
        nv_comment_form();

        if (have_comments()) {
            wp_list_comments([
                'style' => 'ol',
                'callback' => 'nv_render_comment',
            ]);
        } else {
            ?>
            <p class="text-center text-sm text-nv-text mt-6 border-t border-nv-border pt-6">
                <?php esc_html_e('No comments yet.', DOMAIN); ?>
            </p>
            <?php
        }
        ?>

    <?php endif; ?>
</section>

<?php
// comment_form();
?>