<?php

get_header(); ?>

<h2 class="text-[200px] w-full font-bold text-center text-nv-header">
    404
</h2>
<p class="text-center text-[1.2rem] translate-y-[-50px] font-bold">
    Oops! The page you are looking for does not exist.
</p>
<div class="w-full flex items-center justify-center">
    <a href="<?php echo home_url(); ?>"
        class="text-center text-base font-bold no-underline hover:bg-blue-500 bg-blue-600  text-white px-4 py-2 rounded-md">
        Go back to home
    </a>
</div>

<?php
get_footer();
?>