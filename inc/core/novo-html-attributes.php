<?php

class Novo_HTML_Attributes
{
    public static function get_instance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function __construct()
    {
        add_filter('novo_parse_attr', array($this, 'parse_attributes'), 10, 3);
    }

    public function parse_attributes($attributes, $context, $settings)
    {
        switch ($context) {
            case 'filter-button':
                $attributes['class'] = 'dropdown-button filter-button flex items-center justify-between px-4 h-full rounded-lg bg-novo-card border border-novo-border focus:outline-none max-md:min-w-[40px] max-md:px-0 max-md:py-[10px] max-lg:min-w-[44px] max-lg:justify-center';
                $attributes['onclick'] = 'filterSelect(event)';
                $attributes['aria-expanded'] = 'false';
                $attributes['aria-controls'] = 'dropdown-content';
                break;
            case 'dropdown-item':
                $attributes['class'] = 'dropdown-item px-4 py-2 cursor-pointer text-[14px] font-semibold';
                break;
            case 'dropdown-content':
                $attributes['class'] = 'dropdown-content absolute top-[calc(100%+4px)] left-0 w-full text-center bg-novo-card-full border border-novo-border rounded-lg overflow-hidden z-10';
                break;
            case 'filter-text':
                $attributes['class'] = 'filter-text max-md:hidden text-[14px] font-semibold selected-value filter-text ml-2';
                break;
        }
        return $attributes;
    }
}
;

Novo_HTML_Attributes::get_instance();
