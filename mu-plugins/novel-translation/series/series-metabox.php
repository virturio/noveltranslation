<?php

// Meta Box Class: Series Detail
class Series_Metabox
{

    private $screen = array(
        'series',
    );

    private $meta_fields = array(
        array(
            'label' => 'Synopsis',
            'id' => '_nv_series_synopsis',
            'default' => '-',
            'type' => 'wysiwyg',
        ),
        array(
            'label' => 'Publisher',
            'id' => '_nv_series_publisher',
            'default' => '-',
            'type' => 'text',
        ),
        array(
            'label' => 'Publisher Url',
            'id' => '_nv_series_publisher_url',
            'type' => 'url',
        ),
        array(
            'label' => 'Raw Source',
            'id' => '_nv_series_rawsource_url',
            'type' => 'url',
        ),
        array(
            'label' => 'Author',
            'id' => '_nv_series_author',
            'default' => '-',
            'type' => 'text',
        ),
        array(
            'label' => 'Status',
            'id' => '_nv_series_status',
            'default' => 'Ongoing',
            'type' => 'select',
            'options' => array(
                'Ongoing',
                'Complete',
                'Hiatus',
                'Dropped',
            ),
        ),
        array(
            'label' => 'Alternative Names',
            'id' => '_nv_series_alternative_names',
            'default' => 'Comma-separated list of series alternative names.\r\nE.g., series 1, series 2',
            'type' => 'textarea',
        ),
    );

    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_fields'));
    }

    public function add_meta_boxes()
    {
        foreach ($this->screen as $single_screen) {
            add_meta_box(
                'seriesdetail',
                __('Series Detail', 'novel-translation'),
                array($this, 'meta_box_callback'),
                $single_screen,
                'normal',
                'high'
            );
        }
    }

    public function meta_box_callback($post)
    {
        wp_nonce_field('seriesdetail_data', 'seriesdetail_nonce');
        $this->field_generator($post);
    }

    public function field_generator($post)
    {
        $output = '';
        foreach ($this->meta_fields as $meta_field) {
            $label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
            $meta_value = get_post_meta($post->ID, $meta_field['id'], true);
            if (empty($meta_value)) {
                if (isset($meta_field['default'])) {
                    $meta_value = $meta_field['default'];
                }
            }
            switch ($meta_field['type']) {
                case 'select':
                    $input = sprintf(
                        '<select id="%s" name="%s">',
                        $meta_field['id'],
                        $meta_field['id']
                    );
                    foreach ($meta_field['options'] as $key => $value) {
                        $meta_field_value = !is_numeric($key) ? $key : $value;
                        $input .= sprintf(
                            '<option %s value="%s">%s</option>',
                            $meta_value === $meta_field_value ? 'selected' : '',
                            $meta_field_value,
                            $value
                        );
                    }
                    $input .= '</select>';
                    break;
                case 'textarea':
                    $input = sprintf(
                        '<textarea style="width: 100%%" id="%s" name="%s" rows="5" placeholder="%s">%s</textarea>',
                        $meta_field['id'],
                        $meta_field['id'],
                        $meta_field['default'],
                        $this->value($meta_field)
                    );
                    break;
                case 'wysiwyg':
                    ob_start();
                    wp_editor($meta_value, $meta_field['id']);
                    $input = ob_get_contents();
                    ob_end_clean();
                    break;
                default:
                    $input = sprintf(
                        '<input %s id="%s" name="%s" type="%s" value="%s">',
                        $meta_field['type'] !== 'color' ? 'style="width: 100%"' : '',
                        $meta_field['id'],
                        $meta_field['id'],
                        $meta_field['type'],
                        $meta_value
                    );
            }
            $output .= $this->format_rows($label, $input);
        }
        echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
    }

    public function format_rows($label, $input)
    {
        return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
    }

    public function save_fields($post_id)
    {
        if (!isset($_POST['seriesdetail_nonce']))
            return $post_id;
        $nonce = $_POST['seriesdetail_nonce'];
        if (!wp_verify_nonce($nonce, 'seriesdetail_data'))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        foreach ($this->meta_fields as $meta_field) {
            if (isset($_POST[$meta_field['id']])) {
                switch ($meta_field['type']) {
                    case 'email':
                        $_POST[$meta_field['id']] = sanitize_email($_POST[$meta_field['id']]);
                        break;
                    default:
                        $_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
                        break;
                }
                update_post_meta($post_id, $meta_field['id'], $_POST[$meta_field['id']]);
            } else if ($meta_field['type'] === 'checkbox') {
                update_post_meta($post_id, $meta_field['id'], '0');
            }
        }
    }

    private function value($field)
    {
        global $post;
        if (metadata_exists('post', $post->ID, $field['id'])) {
            $value = get_post_meta($post->ID, $field['id'], true);
        } else {
            return '';
        }
        return str_replace('\u0027', "'", $value);
    }
}
;

new Series_Metabox;
