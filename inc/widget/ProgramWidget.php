<?php
// Creating the widget
class ProgramWidget extends WP_Widget
{
    // The construct part
    function __construct()
    {
        // Add Widget scripts
        add_action('admin_enqueue_scripts', [$this, 'scripts']);

        parent::__construct(
            'program_widget', // Base ID
            __('Program Unggulan', 'program_widget_domain'), // Name
            [
                'description' => __(
                    'Our Widget with media files',
                    'program_widget_domain'
                ),
            ] // Args
        );
    }

    /**
     * Load script.
     *
     * @return void function
     */
    public function scripts()
    {
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
        wp_enqueue_script(
            'our_admin',
            get_template_directory_uri() . '/assets/js/our_admin.js',
            ['jquery']
        );
    }

    /**
     * Generate widget frontend.
     * @var array $args $instance
     * @return void
     */
    public function widget($args, $instance)
    {
    }

    /**
     * Generate widget backend.
     *
     * @return void html widget 
     */
    public function form($instance)
    {
        $title = !empty($instance['title'])
            ? $instance['title']
            : __('New title', 'text_domain');
        $image = !empty($instance['image']) ? $instance['image'] : '';
        $content = !empty($instance['content']) ? $instance['content'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_id(
                'title'
            ); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id(
                'title'
            ); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id(
                'image'
            ); ?>"><?php _e('Image:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id(
                'image'
            ); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo esc_url($image); ?>" />
            <button class="upload_image_button button button-primary">Upload Image</button>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id(
                'content'
            ); ?>"><?php _e('Content:'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id(
                'content'
            ); ?>" name="<?php echo $this->get_field_name('content'); ?>" type="text"><?php echo esc_attr($content); ?></textarea>
        </p>
        <?php
    }

     /**
     * Updating widget replacing old instances with new
     *
     * @return array widget option
     */
    public function update($new_instance, $old_instance)
    {
        $instance = [];
        $instance['title'] = !empty($new_instance['title'])
            ? strip_tags($new_instance['title'])
            : '';
        $instance['image'] = !empty($new_instance['image'])
            ? $new_instance['image']
            : '';
        $instance['content'] = !empty($new_instance['content'])
            ? $new_instance['content']
            : '';

        return $instance;
    }
}
