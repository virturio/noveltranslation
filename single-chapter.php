<?php get_header(); ?>
<?php
$series_name = get_post_meta(get_the_ID(), '_nv_related_series_name', true);
$series_slug = sanitize_title($series_name);
$chapter_title = get_post_meta(get_the_ID(), '_nv_chapter_title', true); ?>

<main class="w-full flex items-center justify-center px-6 flex-col mt-[64px] mb-10">
    <div
        class="content-container flex flex-col max-w-[984px] w-full justify-center items-start text-nv-chapter-text gap-4">
        <!-- Title & Breadcrumb -->
        <div class="mb-2 mt-10 text-start">
            <h1 class="text-xl font-semibold mb-2">
                <?php the_title(); ?>
            </h1>

            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="/"
                            class="inline-flex items-center text-sm font-light text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="/series"
                                class="ms-1 text-sm font-light text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Series</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="/series/<?php echo $series_slug; ?>"
                                class="ms-1 text-sm font-light text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white line-clamp-1"><?php echo $series_name; ?></a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span
                                class="ms-1 text-sm font-light text-gray-500 md:ms-2 dark:text-gray-400 line-clamp-1 line-clamp-1"><?php echo $chapter_title; ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Buttons -->
        <div class="h-[64px] bg-nv-header border-y w-full border-nv-border flex justify-between items-center">
            <div class="flex gap-2 w-full justify-between items-center">
                <button
                    class="prev-chapter text-white bg-nv-button hover:bg-nv-button-hover disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-nv-button font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center gap-2 transition-all duration-200 transform hover:-translate-x-1">
                    <svg class="w-4 h-4 rotate-180" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.1 19.5C12.8032 19.5001 12.5131 19.4122 12.2663 19.2474C12.0195 19.0826 11.8271 18.8483 11.7135 18.5741C11.5999 18.2999 11.5702 17.9982 11.6282 17.7072C11.6861 17.4161 11.8291 17.1488 12.039 16.939L16.625 12.354C16.6715 12.3076 16.7085 12.2524 16.7337 12.1916C16.7589 12.1309 16.7719 12.0658 16.7719 12C16.7719 11.9342 16.7589 11.8691 16.7337 11.8084C16.7085 11.7476 16.6715 11.6925 16.625 11.646L12.043 7.061C11.7616 6.77974 11.6035 6.39822 11.6034 6.00036C11.6033 5.6025 11.7612 5.2209 12.0425 4.9395C12.3237 4.65811 12.7053 4.49997 13.1031 4.49988C13.501 4.49978 13.8826 4.65774 14.164 4.939L18.75 9.525C19.4052 10.182 19.7732 11.0721 19.7732 12C19.7732 12.9279 19.4052 13.818 18.75 14.475L14.164 19.061C14.0243 19.2006 13.8584 19.3113 13.6758 19.3866C13.4932 19.462 13.2975 19.5005 13.1 19.5Z"
                            fill="currentColor" />
                    </svg>
                    Previous
                </button>
                <button
                    class="prev-chapter text-white bg-nv-button hover:bg-nv-button-hover disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-nv-button font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center gap-2 transition-all duration-200 transform hover:-translate-y-1">
                    Indeks
                </button>
                <button
                    class="next-chapter text-white bg-nv-button hover:bg-nv-button-hover disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-nv-button font-medium rounded-lg text-sm px-3 py-2 text-center inline-flex items-center gap-2 transition-all duration-200 transform hover:translate-x-1">
                    Next
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M13.1 19.5C12.8032 19.5001 12.5131 19.4122 12.2663 19.2474C12.0195 19.0826 11.8271 18.8483 11.7135 18.5741C11.5999 18.2999 11.5702 17.9982 11.6282 17.7072C11.6861 17.4161 11.8291 17.1488 12.039 16.939L16.625 12.354C16.6715 12.3076 16.7085 12.2524 16.7337 12.1916C16.7589 12.1309 16.7719 12.0658 16.7719 12C16.7719 11.9342 16.7589 11.8691 16.7337 11.8084C16.7085 11.7476 16.6715 11.6925 16.625 11.646L12.043 7.061C11.7616 6.77974 11.6035 6.39822 11.6034 6.00036C11.6033 5.6025 11.7612 5.2209 12.0425 4.9395C12.3237 4.65811 12.7053 4.49997 13.1031 4.49988C13.501 4.49978 13.8826 4.65774 14.164 4.939L18.75 9.525C19.4052 10.182 19.7732 11.0721 19.7732 12C19.7732 12.9279 19.4052 13.818 18.75 14.475L14.164 19.061C14.0243 19.2006 13.8584 19.3113 13.6758 19.3866C13.4932 19.462 13.2975 19.5005 13.1 19.5Z"
                            fill="currentColor" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="content flex flex-col gap-4 p-4">
            <h2 class="font-semibold m-auto text-center text-xl">
                <?php echo $chapter_title; ?>
            </h2>
            <?php the_content(); ?>
        </div>
</main>

<?php get_footer(); ?>