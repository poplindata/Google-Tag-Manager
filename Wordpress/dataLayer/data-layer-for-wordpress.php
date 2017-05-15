<?php
/*
 * Plugin Name: dataLayer Code Generator
 * Plugin URI: http://www.snowflake-analytics.com
 * Description:  Inject SP datalayer in the head.
 * Author: Snowflake Analytics
 * Version: 1.3
 * Author URI: http://www.snowflake-analytics.com
*/
// Installation and uninstallation hooks
register_activation_hook(__FILE__, 'spcode_activate');
register_deactivation_hook(__FILE__, 'spcode_deactivate');

/*
 * Activate the plugin
 */
function spcode_activate() {
    // Do nothing
} // END public static function activate

/*
 * Deactivate the plugin
 */
function spcode_deactivate() {
    // Do nothing
} // END public static function deactivate

function injectSPCode() {
    global $post, $wp_query, $wpdb, $current_user;
    $post_categories = get_the_category( get_the_ID() );
    $post_tags = get_the_tags( get_the_ID() );
    $wp_app_ID = get_bloginfo( 'name' );
    $wp_platform = $_SERVER['HTTP_HOST'];
    $wp_site_profile = $_SERVER['REMOTE_ADDR'] == '0.0.0.0' ? "true" : "false";
    $role = $wpdb->prefix . 'capabilities';
    if(is_user_logged_in()) {
        $current_user->role = array_keys($current_user->$role);
        $wp_visitor_type = $current_user->role[0];
    } else {
        $wp_visitor_type = "guest";
    }

    $dl = array();

    switch (true) {
        case is_front_page():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'front-page';
            $dl["WPtemplateDisplayType"] = 'blog';
            $dl["WPtemplateFileName"] = 'index';
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_home():
            $dl["WPtemplateFile"] = 'page';
            $dl["WPtemplateDisplay"] = 'home';
            $dl["WPtemplateDisplayType"] = 'home';
            $dl["WPtemplateFileName"] = 'index';
            $dl["WPtemplateFileId"] = '';
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_single():
            $last_category = end($post_categories);
            $formatted_tags = array();
            if ( !empty($post_tags) ) {
                foreach(@$post_tags as $tag) {
                    $formatted_tags[] = $tag->name;
                }
            }

            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'single-post';
            $dl["WPtemplateDisplayType"] = strtolower( get_post_type( get_the_ID() ) );
            $dl["WPtemplateFileName"] = strtolower( get_the_title() );
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPpostCategory"] = $last_category->cat_name;
            $dl["WPpostTags"] = $formatted_tags;
            $dl["WPpostDate"] =  date( 'Y-m-d', strtotime( $post->post_date ) );
            $dl["WPpostAuthor"] = get_the_author_meta( 'user_nicename' , $post->post_author );
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_page():
            $dl["WPtemplateFile"] = 'page';
            $dl["WPtemplateDisplay"] = 'page';
            $dl["WPtemplateDisplayType"] = strtolower( $post->post_name );
            $dl["WPtemplateFileName"] = strtolower( get_the_title() );
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPpostDate"] = date( 'Y-m-d', strtotime( $post->post_date ) );
            $dl["WPpostAuthor"] = get_the_author_meta( 'user_nicename' , $post->post_author );
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_category():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'category';
            $dl["WPtemplateDisplayType"] = 'archive';
            $dl["WPtemplateFileName"] = strtolower( get_cat_name( $wp_query->query_vars['cat'] ) );
            $dl["WPtemplateFileId"] = intval($wp_query->query_vars['cat']);
            $dl["WPpostCountOnPage"] = intval($wp_query->post_count);
            $dl["WPpostCountTotal"] = intval($wp_query->found_posts);
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_tag():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'tag';
            $dl["WPtemplateDisplayType"] = 'archive';
            $dl["WPtemplateFileName"] = strtolower( $wp_query->query_vars['tag'] );
            $dl["WPtemplateFileId"] = intval($wp_query->query_vars['tag_id']);
            $dl["WPpostCountOnPage"] = intval($wp_query->post_count);
            $dl["WPpostCountTotal"] = intval($wp_query->found_posts);
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_author():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'author';
            $dl["WPtemplateDisplayType"] = 'archive';
            $dl["WPtemplateFileName"] = strtolower( get_the_author_meta( 'user_nicename' , $post->post_author ) ) . '.php';
            $dl["WPtemplateFileId"] = intval($post->post_author);
            $dl["WPpostCountOnPage"] = intval($wp_query->post_count);
            $dl["WPpostCountTotal"] = intval($wp_query->found_posts);
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_date():
            if ( is_day() ) {
                $wp_template_file_name = "Day";
            } else if ( is_month() ) {
                $wp_template_file_name = "Month";
            } else if ( is_year() ) {
                $wp_template_file_name = "Year";
            }

            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'date';
            $dl["WPtemplateDisplayType"] = 'archive';
            $dl["WPtemplateFileName"] = strtolower( $wp_template_file_name );
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPpostCountOnPage"] = intval($wp_query->post_count);
            $dl["WPpostCountTotal"] = intval($wp_query->found_posts);
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_search():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'search';
            $dl["WPtemplateDisplayType"] = 'archive';
            $dl["WPtemplateFileName"] = 'search';
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPpostCountOnPage"] = intval($wp_query->post_count);
            $dl["WPpostCountTotal"] = intval($wp_query->found_posts);
            $dl["WPsiteSearchFrom"] = wp_get_referer();
            $dl["WPsiteSearchResults"] = intval($wp_query->found_posts);
            $dl["WPsiteSearchTerm"] = $wp_query->query['s'];
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
        case is_404():
            $dl["WPtemplateFile"] = 'post';
            $dl["WPtemplateDisplay"] = 'error';
            $dl["WPtemplateDisplayType"] = '404';
            $dl["WPtemplateFileName"] = strtolower( $post->post_name );
            $dl["WPtemplateFileId"] = intval(get_the_ID());
            $dl["WPappId"] = $wp_app_ID;
            $dl["WPplatform"] = $wp_platform;
            $dl["WPsiteProfile"] = $wp_site_profile;
            $dl["WPvisitorType"] = $wp_visitor_type;
            break;
    }
?>
    <!-- dataLayer starts -->
    <script>
        dataLayer = [<?php
            echo json_encode($dl, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_PARTIAL_OUTPUT_ON_ERROR);
            ?>];
    </script>
    <!-- dataLayer stops -->
<?php
}
add_action('wp_head','injectSPCode');
