<?php


/**
 * Comment Form Functions
 * 
 * This file contains functions for handling the comment form display and structure
 */

if (!function_exists('nv_get_comment_section_classes')) {
    /**
     * Get the base classes for the comment section
     * 
     * @return string
     */
    function nv_get_comment_section_classes()
    {
        return 'comment-section bg-nv-card py-8 lg:py-16 p-10 antialiased mt-6 rounded-lg';
    }
}


if (!function_exists('nv_render_author_fields')) {
    /**
     * Render the author and email input fields
     * 
     * @return void
     */
    function nv_render_author_fields()
    {
        ?>
        <div id="comment-form-author-fields" class="flex max-sm:flex-col gap-4 mb-4 ">
            <div
                class="comment-form-author bg-nv-bg/50 overflow-hidden flex-1 border border-nv-border text-nv-text text-sm rounded-md">
                <label for="author" class="sr-only">Name</label>
                <input id="author" class="w-full px-4 py-3" name="author" type="text" size="30" maxlength="245"
                    autocomplete="name" placeholder="Username" required>
            </div>
            <div
                class="comment-form-email bg-nv-bg/50 overflow-hidden flex-1 border border-nv-border text-nv-text text-sm rounded-md">
                <label for="email" class="sr-only">Email</label>
                <input id="email" class="w-full px-4 py-3" name="email" type="email" placeholder="your-email@mail.com" size="30"
                    maxlength="100" aria-describedby="email-notes" autocomplete="email" required>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('nv_render_comment_textarea')) {
    /**
     * Render the comment textarea field
     * 
     * @return void
     */
    function nv_render_comment_textarea()
    {
        ?>
        <div
            class="comment-form-comment peer/comment overflow-hidden w-full bg-nv-bg/50 rounded-md border has-[textarea:valid]:rounded-b-none has-[textarea:valid]:border-b-0 border-nv-border">
            <label for="comment-field" class="sr-only">Comment</label>
            <textarea id="comment-field" class="px-4 py-3 text-sm text-nv-text w-full focus:outline-0" name="comment" cols="45"
                rows="5" maxlength="65525" required="required" placeholder="Write a comment..."></textarea>
        </div>
        <?php
    }
}

if (!function_exists('nv_render_cookies_consent')) {
    /**
     * Render the cookies consent checkbox
     * 
     * @return void
     */
    function nv_render_cookies_consent()
    {
        ?>
        <div class="comment-form-cookies-consent mr-auto text-sm text-nv-text-gray flex items-center gap-2">
            <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent"
                data-author="<?php echo 'comment_author_' . COOKIEHASH; ?>"
                data-authoremail="<?php echo 'comment_author_email_' . COOKIEHASH; ?>" type="checkbox" value="yes" checked>
            <label for="wp-comment-cookies-consent">
                <?php _e('Save my username and email in this browser for the next time I comment.', DOMAIN); ?>
            </label>
        </div>
        <?php
    }
}

if (!function_exists('nv_render_submit_section')) {
    /**
     * Render the submit section with buttons
     * 
     * @return void
     */
    function nv_render_submit_section()
    {
        ?>
        <div
            class="form-submit flex items-center gap-2 justify-end bg-nv-border/20 border border-nv-border p-2 peer-has-[textarea:invalid]/comment:hidden">
            <?php nv_render_cookies_consent(); ?>
            <a rel="nofollow" id="cancel-comment-reply-link" href="#respond"
                class="no-underline inline-flex justify-center items-center gap-2 py-2 px-4 text-xs font-regular tracking-wide text-center text-white cursor-pointer rounded-sm bg-nv-bg/50 border border-nv-border hover:border-red-500 hover:text-red-500">
                ❌ <?php _e('Cancel', DOMAIN); ?>
            </a>
            <input name="submit" type="submit" id="submit"
                class="submit py-2 px-4 text-xs font-regular bg-[#07466e] tracking-wide text-center text-white cursor-pointer rounded-sm hover:bg-nv-nav-btn-hover"
                value="<?php _e('Post', DOMAIN); ?>">
            <?php comment_id_fields(); ?>
        </div>
        <?php
    }
}

if (!function_exists('nv_comment_form')) {
    /**
     * Render the complete comment form
     * 
     * @return void
     */
    function nv_comment_form()
    {
        ?>
        <div id="respond" class="comment-respond my-6">
            <form action="/wp-comments-post.php" method="post" id="commentform" class="comment-form">
                <?php
                nv_render_author_fields();
                nv_render_comment_textarea();
                nv_render_submit_section();
                ?>
            </form>
        </div>
        <?php
    }
}