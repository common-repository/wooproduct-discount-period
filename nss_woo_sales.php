<?php
/*
Plugin Name: WooProduct Discount Period
Plugin URI: https://wordpress.org/plugins/wooproduct-discount-period
Description: This plugin used for price schedule or price addon. 
Author: NssTheme Team
Version: 1.0
Author URI: https://www.linkedin.com/in/saiful5721/
Text Domain: nsstheme
Tags: woocommerce sale price, sales price with time, woocommerce price addon, woocommerce price schedule etc;  
Stable tag:1.0
Tested up to: 4.9.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

/* protected */
if (!defined('ABSPATH'))
exit;

//define
define('NSS_PERIOD_PLUGIN_URL', plugin_dir_url(__FILE__));

//add file
include_once("nss_wooprice_discount.php");
//class allow
new nss_product_discount();

//register style
add_action('wp_enqueue_scripts',function()
{
	wp_register_style('nss_woo_style', NSS_PERIOD_PLUGIN_URL.'css/nss_woo_style.css');
	wp_enqueue_style('nss_woo_style');
});