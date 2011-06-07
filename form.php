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
				$query = "WHERE p.post_type = 'post'";
				break;
			case 'page':
				$query = "WHERE p.post_type = 'page'";
				break;
			case 'trash':
				$query = "WHERE p.post_type = 'trash'";
				break;
			default:
				$query = "WHERE p.post_type = 'page' OR p.post_type = 'post' OR p.post_type = 'trash'";
				break;												
		}

		$field = 'post_content';
		$search = $_POST['search'];
		$replace = $_POST['replace'];
$prio = ($_POST['low_priority'] == 'yes') ? ' LOW_PRIORITY ' : '';


$updatequery = $wpdb->prepare( "UPDATE ".$prio." $wpdb->posts AS p SET p.".$field." = REPLACE(p.".$field.", '%s', '%s') $query", 
       $search, $replace );

			$wpdb->query($updatequery);

		if(isset($_POST['postmeta']) && $_POST['postmeta'] == 'yes')
		{
			$field = 'meta_value';
		
			$updatequery = $wpdb->prepare( "UPDATE ".$prio." $wpdb->postmeta AS pm, $wpdb->posts AS p SET pm.".$field." = REPLACE(pm.".$field.", '%s', '%s') $query AND pm.post_id = p.ID", 
       $search, $replace );

			$wpdb->query($updatequery);
		}

		if(empty($prio))
		{		
			echo '<div id="message" class="updated fade">All instances of \'' . $search . '\' are replaced with \''. $replace .'\'.</div>';
		}
		else
		{
			echo '<div id="message" class="updated fade">All instances of \'' . $search . '\' will be replaced with \''. $replace .'\' when server resources are available.</div>';
		}
	}
}
?>
<form id="form1" name="form1" method="post" action=""
	onsubmit="return confirm('Are you sure?')">
<table>
	<tr>
		<td>Include postmeta values:</td>
		<td><input type="radio" name="postmeta" value="yes" checked="checked" /> Yes (Recommended)<br/>
<input type="radio" name="postmeta" value="no" /> No
</td>
	</tr>
	<tr>
		<td>Use <a href="http://dev.mysql.com/doc/refman/5.0/en/update.html" target="_blank">LOW_PRIORITY</a> to do the update:</td>
		<td><input type="radio" name="low_priority" value="no" checked="checked" /> No (Instant updates to your database)<br/>
<input type="radio" name="low_priority" value="yes" /> Yes (Delayed updates, when the server has resources. Could take a long time!)
</td>
	</tr>
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
