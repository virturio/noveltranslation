<?php

/**
 * Chapter Detail Meta Box Handler
 * 
 * This class manages the meta box functionality for chapter posts, including:
 * - Custom fields for chapter details
 * - Series relationship handling
 * - Data validation and sanitization
 * - Meta box rendering and saving
 * 
 * @package Novel_Translation
 * @since 1.0.0
 */
class Chapters_Metabox
{
    /**
     * Array of post types where the meta box should be displayed
     * 
     * @var array
     */
    private $screen = array(
        'chapter',
    );

    /**
     * Array of meta fields configuration
     * 
     * @var array
     */
    private $meta_fields = array(
        array(
            'label' => 'Chapter Title',
            'id' => '_nv_chapter_title',
            'type' => 'text',
        ),
        array(
            'label' => 'Related Series',
            'id' => '_nv_related_series',
            'type' => 'series_select',
        ),
    );

    /**
     * Constructor
     * 
     * Initializes the meta box by registering necessary WordPress hooks
     */
    public function __construct()
    {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_fields'));
    }

    /**
     * Register meta boxes for chapter post type
     * 
     * Adds the Chapter Detail meta box to the post editor screen
     * 
     * @return void
     */
    public function add_meta_boxes()
    {
        foreach ($this->screen as $single_screen) {
            add_meta_box(
                'chapterdetail',
                __('Chapter Detail', 'novel-translation'),
                array($this, 'meta_box_callback'),
                $single_screen,
                'advanced',
                'low'
            );
        }
    }

    /**
     * Meta box callback function
     * 
     * Renders the meta box content and adds nonce field for security
     * 
     * @param WP_Post $post The post object
     * @return void
     */
    public function meta_box_callback($post)
    {
        wp_nonce_field('chapterdetail_data', 'chapterdetail_nonce');
        $this->field_generator($post);
    }

    /**
     * Generate meta box fields
     * 
     * Creates and renders all meta fields based on configuration
     * 
     * @param WP_Post $post The post object
     * @return void
     */
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
                case 'series_select':
                    $input = sprintf(
                        '<select name="%s">%s</select>',
                        $meta_field['id'],
                        $this->select_options($post)
                    );
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

    /**
     * Generate series select options
     * 
     * Creates a dropdown list of all available series
     * 
     * @param WP_Post $post The current post object
     * @return string HTML for select options
     */
    private function select_options($post)
    {
        $output = [];
        $meta_key = PREFIX . ID_KEY;
        $selected_series = get_post_meta($post->ID, $meta_key, true);
        $args = array(
            'post_type' => 'series',
            'numberposts' => -1
        );
        $posts = get_posts($args);

        foreach ($posts as $p) {
            $value = "{$p->ID}-{$p->post_title}";
            $output[] = sprintf(
                '<option %s value="%s">%s</option>',
                selected($selected_series, $p->ID, false),
                $value,
                esc_html($p->post_title)
            );
        }
        return implode('<br>', $output);
    }

    /**
     * Format meta box row
     * 
     * Creates HTML structure for a meta field row
     * 
     * @param string $label The field label
     * @param string $input The input HTML
     * @return string Formatted row HTML
     */
    public function format_rows($label, $input)
    {
        return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
    }

    /**
     * Save meta box fields
     * 
     * Handles saving and sanitizing of all meta fields
     * Includes security checks and data validation
     * 
     * @param int $post_id The post ID
     * @return int|void The post ID if validation fails
     */
    public function save_fields($post_id)
    {
        if (!isset($_POST['chapterdetail_nonce']))
            return $post_id;
        $nonce = $_POST['chapterdetail_nonce'];
        if (!wp_verify_nonce($nonce, 'chapterdetail_data'))
            return $post_id;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;
        foreach ($this->meta_fields as $meta_field) {
            if (isset($_POST[$meta_field['id']])) {
                switch ($meta_field['type']) {
                    case 'email':
                        $_POST[$meta_field['id']] = sanitize_email($_POST[$meta_field['id']]);
                        break;
                    case 'text':
                        $_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
                        break;
                    case 'series_select':
                        $_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
                        break;
                }
                $this->update_post_meta($post_id, $meta_field['id'], $_POST[$meta_field['id']]);
            } else if ($meta_field['type'] === 'checkbox') {
                update_post_meta($post_id, $meta_field['id'], '0');
            }
        }
    }

    /**
     * Add prefix to meta key
     * 
     * @param string $str The string to prefix
     * @return string Prefixed string
     */
    private function with_prefix($str)
    {
        return PREFIX . $str;
    }

    /**
     * Update post meta with special handling for series selection
     * 
     * @param int    $post_id    The post ID
     * @param string $meta_key   The meta key
     * @param string $meta_value The meta value
     * @return void
     */
    private function update_post_meta($post_id, $meta_key, $meta_value)
    {
        if ($meta_key === '_nv_related_series') {
            $this->update_series_select_meta($post_id, $meta_value);
        } else {
            update_post_meta($post_id, $meta_key, $meta_value);
        }
    }

    /**
     * Update series selection meta fields
     * 
     * Handles the special case of series selection by updating
     * related meta fields (name, ID, and slug)
     * 
     * @param int    $post_id    The post ID
     * @param string $meta_value The combined series ID and name
     * @return void
     */
    private function update_series_select_meta($post_id, $meta_value)
    {
        $value = explode('-', $meta_value);
        $series_id = trim($value[0]);
        $series_name = trim($value[1]);

        update_post_meta($post_id, $this->with_prefix(NAME_KEY), $series_name);
        update_post_meta($post_id, $this->with_prefix(ID_KEY), $series_id);
        update_post_meta($post_id, $this->with_prefix(SLUG_KEY), sanitize_title($series_name));
    }
}

if (class_exists('Chapters_Metabox')) {
    new Chapters_Metabox;
}