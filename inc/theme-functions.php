<?php

if (!function_exists('nv_get_icon')) {
    function nv_get_icon($icon, $attr = [])
    {
        return Novo_Icons::get_icon($icon, $attr);
    }
}
