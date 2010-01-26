<?php
/**
 * Admin renamer extended operations.
 *
 * Managing the worpress additonal admin renamer extended operations.
 *
 * @category      Wordpress Plugins
 * @package    	  Plugins
 * @author        Bas Bosman <>
 * @copyright     Yes, Open source, WebsiteFreelancers.nl
 * @version       v 1.0  05-01-2010 Bas$
 */
if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");
global $wpdb, $current_user;

$message = null;
$showMsg = 'none';

/**
 * If submiting the form
 */
if (isset($_POST['submitbutton']) && isset($_POST['postorpage'])){
	if (!isset($_POST['search']) || !$_POST['search']) {
		echo '<div id="message" class="error">No search string</div>';
	}
	else if (!isset($_POST['replace']) || !$_POST['replace']){
		echo '<div id="message" class="error">No replace string</div>';
	}else{
		//Is magic quotes on?
		if (get_magic_quotes_gpc()) {
			// Yes? Strip the added slashes
			$_POST = array_map('stripslashes', $_POST);
		}

		//logic
		$query = "";
		switch ($_POST['postorpage'])
		{
			case 'post':
				$query = "WHERE post_type = 'post'";
				break;
			case 'page':
				$query = "WHERE post_type = 'page'";
				break;
			case 'trash':
				$query = "WHERE post_type = 'trash'";
				break;
			default:
				$query = "WHERE post_type = 'page' OR post_type = 'post' or post_type = 'trash'";
				break;												
		}

		$field = 'post_content';
		$search = $_POST['search'];
		$replace = $_POST['replace'];
		$updatequery = "UPDATE LOW_PRIORITY $wpdb->posts SET post_content = REPLACE($field, '".mysql_real_escape_string($search)."', '".mysql_real_escape_string($replace)."') $query";
		$wpdb->query($updatequery);
		echo '<div id="message" class="updated fade">All instances of \'' . $search . '\' will be replaced with \''. $replace .'\' when server resources are available.</div>';
	}
}
?>
<form id="form1" name="form1" method="post" action=""
	onsubmit="return confirm('Are you sure?')">
<table>
	<tr>
		<td>Search string:</td>
		<td><input type="text" name="search" size="60" /></td>
	</tr>
	<tr>
		<td>Replace string:</td>
		<td><input type="text" name="replace" size="60" /></td>
	</tr>
	<tr>
		<td>Post or page:</td>
		<td><select name="postorpage">
			<option value="post">Post</option>
			<option value="page">Page</option>
			<option value="page">Trash</option>
			<option value="postpage">Post, Page & Trash</option>
		</select></td>
	
	
	<tr>

</table>
<input type="submit" name="submitbutton" value="Search and replace"
	style="margin-top: 2px;"></form>

<h3>How to use?</h3>
<p class="updated">* Search & replace works case sensitive!<br />
&nbsp;&nbsp;&nbsp;A search for "MySearch" will not find content with
"mysearch".<br />
* Only the current version of your page or post will be updated.<br />
* Example when you moved domains and you want to replace all links in
your content:<br />
&nbsp;&nbsp;&nbsp; Search for:<br />
&nbsp;&nbsp;&nbsp; <em>http://www.myoldserver.tld</em><br />
&nbsp;&nbsp;&nbsp; Replace with:<br />
&nbsp;&nbsp;&nbsp; <em>http://www.mynewserver.tld</em></p>
