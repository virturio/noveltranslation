<?php

class Novo_Icons
{

  public static function get_icon($icon)
  {
    $output = '';

    if ($icon === 'calendar') {
      $output = '<svg class="max-md:w-[18px] max-md:h-[18px]" viewBox="0 0 24 24"  width="1rem" height="1rem" fill="#ffffff" xmlns="http://www.w3.org/2000/svg"><path d="M19 4h-1V3c0-.55-.45-1-1-1s-1 .45-1 1v1H8V3c0-.55-.45-1-1-1s-1 .45-1 1v1H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zM5 8V6h14v2H5zm2 4h10v2H7zm0 4h7v2H7z"/></svg>';
    }

    if ($icon === 'sort') {
      $output = '<svg class="max-md:w-[18px] max-md:h-[18px]" viewBox="0 0 24 24"  width="1rem" height="1rem" fill="#ffffff" xmlns="http://www.w3.org/2000/svg"><path d="M3 18h6v-2H3v2zM3 6v2h18V6H3zm0 7h12v-2H3v2z"/></svg>';
    }

    if ($icon === 'chevron-down') {
      $output = '<svg class="chevron ml-2 max-md:hidden" viewBox="0 0 24 24"  width="1rem" height="1rem" fill="#ffffff" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z""/></svg>';
    }

    $classes = array(
      "icon-$icon",
    );

    $output = sprintf(
      '<span class="%1$s">%2$s</span>',
      implode(' ', $classes),
      $output
    );

    return apply_filters('novo_svg_icon', $output, $icon);
  }
}

new Novo_Icons();
