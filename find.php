<?php
/**
 * Find replace operations.
 *
 * @category      Wordpress Plugins
 * @package       Plugins
 * @author        Bas Bosman
 * @copyright     Yes, Open source, WebsiteFreelancers.nl
 * @version       v 1.0
*/
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

/**
 * Displays the admin page
 */
function plugin_find_replace_initpage() {
   if (isset ($_GET['page']) && ($_GET['page'] == 'find_replace/find.php' || $_GET['page'] == 'find-replace/find.php')) {
      require_once PLUGIN_FIND_REPLACE_DIR . '/form.php';
   }
}

/**
 * Additional links on the plugin page
 */
function plugin_find_replace_RegisterPluginLinks($links, $file) {
   if ($file == 'find-replace/find_replace.php') {
      $links[] = '<a href="plugins.php?page=find-replace/find.php">' . __('Settings') . '</a>';
      $links[] = '<a href="http://donate.ramonfincken.com">' . __('Donate') . '</a>';
      $links[] = '<a href="http://www.creativepulses.nl/scripting/wordpress">' . __('Custom WordPress coding nodig?') . '</a>';
   }
   return $links;
}

add_filter('plugin_row_meta','plugin_find_replace_RegisterPluginLinks', 10, 2);

/**
 * Left menu display in Plugin menu
 */
function plugin_find_replace_addMenu() {
   add_submenu_page("plugins.php", "Find and replace", "Find and replace", 10, __FILE__, 'plugin_find_replace_initpage');
}

add_action('admin_menu', 'plugin_find_replace_addMenu');

/**
 * Loading the CSS file
 */
function plugin_find_replace_css() {
   $admin_stylesheet_url = plugin_find_replace_plugin_url('styles/style.css');
   echo '<link rel="stylesheet" href="' . $admin_stylesheet_url . '" type="text/css" />';
}
add_action('admin_head', 'plugin_find_replace_css');

/**
 * Generating the url for current Plugin
 *
 * @param String $path
 * @return String
 */
function plugin_find_replace_plugin_url($path = '') {
   global $wp_version;

   if (version_compare($wp_version, '2.8', '<')) { // Using WordPress 2.7
      $folder = dirname(plugin_basename(__FILE__));
      if ('.' != $folder)
         $path = path_join(ltrim($folder, '/'), $path);
      return plugins_url($path);
   }
   return plugins_url($path, __FILE__);
}
?>
