<?php
/*
Plugin Name: wordpress contributor
Plugin URI: 
Description: the plugin will add metabox for each post to add contributors
Author: Rzayak Singh Oberoi
Version: 1.0
Author URI : 
Licence: GLP V2
*/

/**
 * if is admin then require
 */
if(is_admin()){
	require_once plugin_dir_path(__FILE__).'wordpress-contributor-admin.php';
}

/**
 * Content filter hook to appends new div for contributors 
 */
add_filter("the_content", "add_contributors");

function add_contributors($content)
{	
	//get all the contributors assigned to that post
	$arrContributorsId=get_post_meta(get_the_ID(), "contributor_count", true);
	if($arrContributorsId) {
		$strContributorBox="
		<div class='clear'>
			<h3>Contributors</h3>";
				foreach ($arrContributorsId as $intUserId){
					$objUser=get_user_by("id", $intUserId);					
					$strContributorBox.="<div class='contributor_box'>";
					$strContributorBox.= "<a href='".get_author_posts_url($intUserId)."'>".get_avatar( $intUserId, $size = '44')."<span>".$objUser->user_nicename."</span></a>";						
					$strContributorBox.="</div>";
				}
		$strContributorBox.="</div>";

		//display the contributors box
		return $content.$strContributorBox;
	} else {
		return $content;
	}
}

/**
 *  enque script and style in header 
 */
add_action("wp_head", "load_contributor_header_files");

function load_contributor_header_files(){
	wp_register_style("contributor-css", plugin_dir_url(__FILE__)."css/contributor.css");
	wp_enqueue_style("contributor-css");
}