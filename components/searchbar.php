<?php

if (!function_exists('novo_construct_searchbar')) {
    function novo_construct_searchbar()
    {

        ?>
        <div class="flex-1 mx-2 h-[44px]">
            <div class="search-container h-full relative transition-all duration-300 ease">
                <div
                    class="search-bar group flex items-center justify-center bg-novo-search rounded-lg px-5 h-full gap-4 transition-all duration-300 ease border border-transparent hover:bg-novo-search-hover hover:shadow-lg focus-within:border-yellow-500 focus-within:border-2 focus-within:shadow-lg">
                    <svg class="h-6 text-novo-text-gray search-icon transition-all duration-300 ease group-hover:text-white group-hover:scale-110 group-focus-within:text-white group-focus-within:scale-110"
                        fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M21.71 20.29l-5.01-5.01C17.54 13.68 18 11.91 18 10c0-4.41-3.59-8-8-8S2 5.59 2 10s3.59 8 8 8c1.91 0 3.68-.46 5.28-1.3l5.01 5.01c.39.39 1.02.39 1.41 0l1.41-1.41c.39-.39.39-1.02 0-1.41zM10 16c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6z" />
                    </svg>
                    <input type="text" id="chapter-search" placeholder="Search posts"
                        class="search-input bg-transparent text-[14px] font-semibold text-white focus:outline-none w-full"
                        autocomplete="off" />
                </div>
                <!-- Loading Indicator -->
                <div id="search-loading"
                    class="hidden absolute top-full left-0 right-0 mt-2 bg-novo-search rounded-lg p-4 border border-novo-border">
                    <div class="flex items-center justify-center">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-yellow-500"></div>
                    </div>
                </div>
                <!-- Search Results Dropdown -->
                <div id="search-results"></div>
            </div>
        </div>
        <?php
    }
}
