<?php
class WPPoster {
    public function create_post($title, $link, $content, $categories = []) {
        $post_data = array(
            'post_title'    => wp_strip_all_tags($title),
            'post_content'  => $content,
            'post_status'   => 'publish',
            'post_author'   => get_current_user_id(),
            'post_type'     => 'post'
        );
        
        $post_id = wp_insert_post($post_data);
        
        if ($post_id && !is_wp_error($post_id)) {
            if (!empty($categories)) {
                wp_set_post_categories($post_id, $categories);
            }
        }
        
        return $post_id;
    }
} 