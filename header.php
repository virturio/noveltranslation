<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet" />
    <title><?php wp_title(); ?></title>
    <?php wp_head(); ?>
</head>

<body <?php body_class('flex flex-col min-h-screen'); ?>>
    <?php wp_body_open(); ?>

    <header class="bg-nv-header fixed top-0 left-0 right-0 h-[64px] px-6 flex items-center justify-center z-10">
        <div class="max-w-[984px] w-full">
            <a href="/" class="text-xl font-bold no-underline">Novo Translation</a>
        </div>
    </header>
    <main class="w-full flex items-start justify-center px-6 mt-[64px] flex-grow">
        <div class="max-w-[984px] py-8 w-full">