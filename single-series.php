<?php get_header(); ?>
<?php
$series_id = get_the_ID();
$series_title = get_the_title($series_id);
$series_description = get_post_field('post_content', $series_id);
$series_image = get_the_post_thumbnail_url($series_id, array(200, 300));

?>

<!-- Main Content -->
<main class="w-full flex items-center justify-center px-6">
    <div class="max-w-[984px] py-8 w-full">
        <!-- Title & Breadcrumb -->
        <div class="mb-6 mt-10">
            <h1 class="text-xl font-semibold mb-2">
                <?php echo $series_title; ?>
            </h1>

            <?php nv_construct_breadcrumb(); ?>
        </div>

        <!-- Series Info -->
        <div
            class="flex gap-6 bg-nv-header rounded-lg p-6 series-container max-md:flex-col max-md:items-center max-md:p-2">
            <!-- Cover Section -->
            <div class="w-[200px] flex flex-col gap-4 cover-section">
                <img src="<?php echo $series_image; ?>" alt="Series Cover" class="w-full h-auto rounded" />
                <!-- Rating -->
                <?php nv_construct_rating(); ?>
            </div>

            <!-- Info Section -->
            <?php nv_construct_tags() ?>

            <!-- Series Details -->
            <?php nv_construct_series_details(); ?>

            <!-- Alternative Names -->
            <?php nv_construct_series_alternative_names(); ?>
        </div>

        <!-- Social Buttons -->
        <?php nv_construct_social_buttons(); ?>
    </div>
    </div>

    <!-- Synopsis -->
    <?php nv_construct_series_synopsis(); ?>

    <!-- Chapters -->
    <div class="bg-nv-header rounded-lg p-6 mt-6">
        <div class="border-b border-nv-text-gray pb-4 mb-4">
            <h2 class="text-lg font-semibold">
                Read <span class="text-white"><?php echo $series_title; ?></span>
            </h2>
        </div>

        <?php nv_the_series_chapters(); ?>
    </div>
    </div>
</main>
<?php get_footer(); ?>