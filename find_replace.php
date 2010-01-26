<?php
/*
Plugin Name: Find and replace
Plugin URI: http://www.websitefreelancers.nl
Description: Lets you find and replace pages and posts with a GUI.
Version: 1.0
Author: Bas Bosman
Author URI: http://www.websitefreelancers.nl
*/
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

if (!defined('PLUGIN_find_REPLACE_DIR')) {
   define('PLUGIN_FIND_REPLACE_DIR', WP_PLUGIN_DIR . '/' . plugin_basename(dirname(__FILE__)));
}

if (!defined('PLUGIN_FIND_REPLACE_BASENAME')) {
   define('PLUGIN_FIND_REPLACE_BASENAME', plugin_basename(__FILE__));
}

// Admin only :)
if (is_admin()) {
   require_once PLUGIN_FIND_REPLACE_DIR . '/find.php';
}
?>