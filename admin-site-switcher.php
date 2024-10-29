<?php
/*
Plugin Name: Site Switcher
Plugin URI: http://wordpress.kdari.net/2011/05/admin-site-switcher.html
Description: Switches between sites in a multisite install.
Version: 1.3
Author: lgedeon, cmdrmatt
Author URI: http://web.kdari.com/

Released under the GPL v.2, http://www.gnu.org/copyleft/gpl.html

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
*/


function kdari_site_switch_get_sites($args=array()){
    global $wpdb;
    $query = "SELECT * FROM {$wpdb->blogs}";
    $sql = $wpdb->prepare($query);
    $results = $wpdb->get_results($sql, ARRAY_A);
    return $results;
}


function kdari_site_switch_list() {
	if( !current_user_can( 'manage_sites' ) ) {
    	return;
	}
//	$CurrentPage = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/wp-admin/")+0);
	$CurrentPage = substr($_SERVER["REQUEST_URI"],strrpos($_SERVER["REQUEST_URI"],"/wp-admin/")+0);
	$output = '<div id="kdari-site-switch-list">Switch Sites<ul>';
	$allsites = kdari_site_switch_get_sites();
		foreach ($allsites AS $blog) {
			$blog_details = get_blog_details($blog['blog_id']);
			$output .= '<li><a href="http://'.$blog['domain'].$CurrentPage.'">'.$blog_details->blogname.'</a></li>';
		}
	$output .= '</ul></div>';
	echo $output;
}

function kdari_site_switch_list_css() {

	if( !current_user_can( 'manage_sites' ) ) {
    	return;
	}

	echo "<style type='text/css'>

#kdari-site-switch-list {
	float: left;
	padding: 14px;
	position: relative;
}

#kdari-site-switch-list ul {
	width: 600px;
	padding: 10px 0;
	top: 40px;
	left: -50px;
	background: #fff;
	border: 1px solid #ccc;
	position: absolute;
	visibility: hidden;
	z-index: 1;
}

#kdari-site-switch-list:hover ul,
#kdari-site-switch-list ul:hover {
	visibility: visible;
}

#kdari-site-switch-list ul li {
	width: 180px !important;
	margin: 1px 10px;
	display: inline-block;
	overflow: hidden;
	white-space: nowrap;
}

#kdari-site-switch-list a {
	color: #000;
}

#kdari-site-switch-list a:hover {
	text-decoration: underline;
	font-weight: bold;
}

#wphead {
	margin-top: 10px !important;
}

#hcsheader {
	background: #22384c !important;
	height: 10px !important;
	border: none !important;
}

#hcsheader #pnav {
	top: 0;
	left: 75px;
}

#hcsheader #pnav li a.button {
	margin: 1px 0 0 0;
	padding: 0px 20px 1px;
}

</style>";

}

add_action( 'in_admin_header', 'kdari_site_switch_list');
add_action( 'admin_head', 'kdari_site_switch_list_css');
