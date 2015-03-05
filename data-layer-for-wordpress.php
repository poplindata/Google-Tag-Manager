<?php
/*
 * Plugin Name: dataLayer Code Generator
 * Plugin URI: http://www.digdeepdigital.com.au
 * Description:  Inject SP datalayer in the head.
 * Author: Digdeep Digital
 * Version: 1.3
 * Author URI: http://www.digdeepdigital.com.au
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
    $wp_site_profile = $_SERVER['REMOTE_ADDR'] == '14.203.110.26' ? "true" : "false";
    $role = $wpdb->prefix . 'capabilities';
    if(is_user_logged_in()) {
        $current_user->role = array_keys($current_user->$role);
        $wp_visitor_type = $current_user->role[0];
    } else {
        $wp_visitor_type = "guest";
    }
?>
    <!-- dataLayer starts -->
        <script>
            dataLayer = [{
                <?php 
                if ( is_front_page() ) {
                    echo 
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'front-page',
                'WPtemplateDisplayType': 'blog',
                'WPtemplateFileName': 'index',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_home() ) {
                    echo
                    "'WPtemplateFile': 'page',
                'WPtemplateDisplay': 'home',
                'WPtemplateDisplayType': 'home',
                'WPtemplateFileName': 'index',
                'WPtemplateFileId': '',
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_single() ) {
                    $last_category = end($post_categories);
                    $formatted_tags = '';
                    if ( !empty($post_tags) ) {
                        foreach(@$post_tags as $tag) {
                            $formatted_tags[] = "'" . $tag->name . "'";
                        }
                        $formatted_tags = implode( ', ', $formatted_tags );
                    }
                    echo 
                    "'WPtemplateFile': 'post', 
                'WPtemplateDisplay': 'single-post', 
                'WPtemplateDisplayType': '" . strtolower( get_post_type( get_the_ID() ) ) . "',
                'WPtemplateFileName': '" . strtolower( get_the_title() ) . "',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPpostCategory': '" . $last_category->cat_name . "',
                'WPpostTags': [" . $formatted_tags . "],
                'WPpostDate': '" . date( 'Y-m-d', strtotime( $post->post_date ) ) . "', 
                'WPpostAuthor': '" . get_the_author_meta( 'user_nicename' , $post->post_author ) . "',
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "', 
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_page() ) {
                    echo 
                    "'WPtemplateFile': 'page', 
                'WPtemplateDisplay': 'page', 
                'WPtemplateDisplayType': '" . strtolower( $post->post_name ) . "',
                'WPtemplateFileName': '" . strtolower( get_the_title() ) . "',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPpostDate': '" . date( 'Y-m-d', strtotime( $post->post_date ) ) . "',
                'WPpostAuthor': '" . get_the_author_meta( 'user_nicename' , $post->post_author ) . "',
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "', 
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_category() ) {
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'category',
                'WPtemplateDisplayType': 'archive',
                'WPtemplateFileName': '" . strtolower( get_cat_name( $wp_query->query_vars['cat'] ) ) . "',
                'WPtemplateFileId': " . intval($wp_query->query_vars['cat']) . ",
                'WPpostCountOnPage': " . intval($wp_query->post_count) . ",
                'WPpostCountTotal': " . intval($wp_query->found_posts) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_tag() ) {
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'tag',
                'WPtemplateDisplayType': 'archive',
                'WPtemplateFileName': '" . strtolower( $wp_query->query_vars['tag'] ) . "',
                'WPtemplateFileId': " . intval($wp_query->query_vars['tag_id']) . ",
                'WPpostCountOnPage': " . intval($wp_query->post_count) . ",
                'WPpostCountTotal': " . intval($wp_query->found_posts) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_author() ) {
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'author',
                'WPtemplateDisplayType': 'archive',
                'WPtemplateFileName': '" . strtolower( get_the_author_meta( 'user_nicename' , $post->post_author ) ) . ".php',
                'WPtemplateFileId': " . intval($post->post_author) . ",
                'WPpostCountOnPage': " . intval($wp_query->post_count) . ",
                'WPpostCountTotal': " . intval($wp_query->found_posts) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_date() ) {
                    if ( is_day() ) {
                        $wp_template_file_name = "Day";
                    } else if ( is_month() ) {
                        $wp_template_file_name = "Month";
                    } else if ( is_year() ) {
                        $wp_template_file_name = "Year";
                    }
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'date',
                'WPtemplateDisplayType': 'archive',
                'WPtemplateFileName': '" . strtolower( $wp_template_file_name ) . "',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPpostCountOnPage': " . intval($wp_query->post_count) . ",
                'WPpostCountTotal': " . intval($wp_query->found_posts) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_search() ) {
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'search',
                'WPtemplateDisplayType': 'archive',
                'WPtemplateFileName': 'search',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPpostCountOnPage': " . intval($wp_query->post_count) . ",
                'WPpostCountTotal': " . intval($wp_query->found_posts) . ",
                'WPsiteSearchFrom': '" . wp_get_referer() . "',
                'WPsiteSearchResults': " . intval($wp_query->found_posts) . ",
                'WPsiteSearchTerm': '" . $wp_query->query['s'] . "',
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                } else if ( is_404() ) {
                    echo
                    "'WPtemplateFile': 'post',
                'WPtemplateDisplay': 'error',
                'WPtemplateDisplayType': '404',
                'WPtemplateFileName': '" . strtolower( $post->post_name ) . "',
                'WPtemplateFileId': " . intval(get_the_ID()) . ",
                'WPappId': '" . $wp_app_ID . "',
                'WPplatform': '" . $wp_platform . "',
                'WPsiteProfile': '" . $wp_site_profile . "',
                'WPvisitorType': '" . $wp_visitor_type . "'";
                }
                ?>
                
            }];
        </script>
    <!-- dataLayer stops -->
<?php
}
add_action('wp_head','injectSPCode');
