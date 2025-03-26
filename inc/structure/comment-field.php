<?php
/**
 * Comment Field Functions
 * 
 * This file contains functions for handling individual comment display
 */

if (!function_exists('nv_get_comment_classes')) {
    /**
     * Get the comment container classes
     * 
     * @param int $depth Comment depth
     * @return string Comment classes
     */
    function nv_get_comment_classes($depth)
    {
        $comment_id = get_comment_ID();
        return sprintf(
            'comment-%1s relative py-6 text-base bg-nv-card depth-%2s %3s',
            $comment_id,
            esc_attr($depth),
            $depth === 1 ? 'border-t border-nv-border' : ''
        );
    }
}

if (!function_exists('nv_render_comment_author_avatar')) {
    /**
     * Render the comment author avatar
     * 
     * @param WP_Comment $comment Comment object
     * @return void
     */
    function nv_render_comment_author_avatar($comment)
    {
        ?>
        <img class="w-9 h-9 rounded-full" src="<?php echo esc_url(get_avatar_url($comment->comment_ID)); ?>"
            alt="<?php comment_author($comment->comment_ID); ?>">
        <?php
    }
}

if (!function_exists('nv_render_comment_date')) {
    /**
     * Render the comment date
     * 
     * @param WP_Comment $comment Comment object
     * @return void
     */
    function nv_render_comment_date($comment)
    {
        ?>
        <time class="text-sm text-nv-text-gray ml-4" pubdate datetime="<?php comment_date(); ?>"
            title="<?php comment_date(); ?>">
            <?php echo esc_html(date('F j, Y', strtotime($comment->comment_date))); ?>
        </time>
        <?php
    }
}

function nv_render_comment_author_username($comment)
{
    ?>
    <span class="text-sm text-nv-text font-semibold">
        <?php echo comment_author($comment->comment_ID); ?>
    </span>
    <?php
}

if (!function_exists('nv_render_comment_author_section')) {
    /**
     * Render the comment author section with avatar and date
     * 
     * @param WP_Comment $comment Comment object
     * @return void
     */
    function nv_render_comment_author_section($comment)
    {
        ?>
        <div class="comment-author vcard flex items-center pt-2">
            <?php nv_render_comment_author_username($comment); ?>
            <?php nv_render_comment_date($comment); ?>
        </div>
        <?php
    }
}

if (!function_exists('nv_render_comment_content')) {
    /**
     * Render the comment content
     * 
     * @param WP_Comment $comment Comment object
     * @return void
     */
    function nv_render_comment_content($comment)
    {
        ?>
        <p class="text-sm font-light text-nv-text whitespace-pre-line">
            <?php echo get_comment_text(); ?>
        </p>
        <?php
    }
}

if (!function_exists('nv_render_comment_reply_link')) {
    /**
     * Render the comment reply link
     * 
     * @param WP_Comment $comment Comment object
     * @return void
     */
    function nv_render_comment_reply_link($comment)
    {
        $comment_id = get_comment_ID();
        $author = $comment->comment_author;
        ?>
        <div id="reply-<?php echo $comment_id; ?>" class="reply mt-6">
            <a rel="nofollow" class="comment-reply-link text-sm text-nv-text-gray"
                href="<?php echo esc_url(get_post_permalink()); ?>?replytocom=<?php echo $comment_id; ?>#respond"
                data-parentid="<?php echo esc_attr($comment->comment_parent ?: $comment_id); ?>"
                data-postid="<?php echo esc_attr($comment->comment_post_ID); ?>"
                data-belowelement="reply-<?php echo $comment_id; ?>" data-respondelement="respond"
                data-replyto="<?php echo esc_attr($author); ?>" aria-label="<?php echo esc_attr($author); ?>">Reply</a>
        </div>
        <?php
    }
}

if (!function_exists('nv_render_comment')) {
    /**
     * Render a single comment
     * 
     * @param WP_Comment $comment Comment object
     * @param array $args Comment arguments
     * @param int $depth Comment depth
     * @return void
     */
    function nv_render_comment($comment, $args, $depth)
    {
        $comment_id = get_comment_ID();
        ?>
        <li class="<?php echo nv_get_comment_classes($depth); ?>" id="comment-<?php echo $comment_id; ?>">
            <div id="div-comment-<?php echo $comment_id; ?>" class="comment-body flex gap-4">
                <?php
                nv_render_comment_author_avatar($comment); ?>
                <div class="flex flex-col w-full">
                    <?php nv_render_comment_author_section($comment); ?>
                    <?php nv_render_comment_content($comment); ?>
                    <?php nv_render_comment_reply_link($comment); ?>
                </div>
            </div>
        </li>
        <?php
    }
}