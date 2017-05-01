<?php
    /*
     * Plugin Name: GTM Code Generator
     * Plugin URI: http://www.snowflake-analytics.com
     * Description:  Inject GTM just after the opening body tag.
     * Author: Snowflake Analytics
     * Version: 1.1
     * Author URI: http://www.snowflake-analytics.com
    */
    register_activation_hook(__FILE__, 'gtmcode_activate');
    register_deactivation_hook(__FILE__, 'gtmcode_deactivate');
    function gtmcode_activate() {
    }
    function gtmcode_deactivate() {
    }
    add_action( 'init', 'test_start_buffer', 0, 0 );
    function test_start_buffer(){
        ob_start( 'test_get_buffer' );
    }
    function test_get_buffer( $buffer ) {
        return preg_replace( '#<body(.+)>#', "<body$1>\n<!-- Google Tag Manager -->\n<noscript><iframe src='//www.googletagmanager.com/ns.html?id=GTM-5JKJ9S' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>\n<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-XXXXX');</script>\n<!-- End Google Tag Manager -->", $buffer);
    }
