<?php
/*
Plugin Name: Minimal SEO Tools
Description: Lightweight SEO toolset for editing meta tags.
Version: 1.0
Author: Samuel
*/

// 1. Register meta box
add_action('add_meta_boxes', function () {
    add_meta_box('mst_meta_tags', 'SEO Meta Tags', 'mst_render_meta_box', ['post', 'page'], 'normal', 'default');
});

// 2. Render meta box in post editor
function mst_render_meta_box($post) {
    $keywords = get_post_meta($post->ID, '_mst_keywords', true);

    
    ?>
    <label>Keywords</label><br>
    <input type="text" name="mst_keywords" value="<?= esc_attr($keywords) ?>" style="width:100%"><br><br>
    <?php
}

// 3. Save the meta when the post is saved
add_action('save_post', function ($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    update_post_meta($post_id, '_mst_keywords', sanitize_text_field($_POST['mst_keywords'] ?? ''));
});

// 4. Output meta tags to <head>
add_action('wp_head', function () {
    if (!is_singular()) return;

    global $post;
    $keywords = get_post_meta($post->ID, '_mst_keywords', true);

    

    if ($keywords) {
        echo "<meta name='keywords' content='" . esc_attr($keywords) . "'>\n";
    }
});

// 5. Flush the output buffer (after <head> is built)
add_action('wp_footer', function () {
    if (ob_get_length()) {
        ob_end_flush();
    }
});
?>