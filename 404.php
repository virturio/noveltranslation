<?php

get_header(); ?>

<main class="w-full flex items-center justify-center px-6 flex-grow mt-[64px]">
    <div class="max-w-[984px] py-8 w-full h-full flex flex-col items-center justify-center gap-4">
        <h2 class="text-[200px] w-full font-bold text-center mb-[32px] text-nv-header">
            404
        </h2>
        <p class="text-center text-[1.2rem] font-bold translate-y-[-200px]">
            Oops! The page you are looking for does not exist.
        </p>
        <a href="<?php echo home_url(); ?>"
            class="text-center text-base font-bold translate-y-[-200px] no-underline hover:bg-blue-500 bg-blue-600  text-white px-4 py-2 rounded-md">
            Go back to home
        </a>
    </div>
</main>

<?php
get_footer();
?>