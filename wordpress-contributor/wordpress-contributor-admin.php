<?php
if ( is_admin() ) {
	/**
	 * Add meta box
	 */
	add_action( 'admin_init', 'post_contribuitor_metabox' );

	function post_contribuitor_metabox(){

		//argument to return only custom post types.
		$arrArgs = array('_builtin' => false);

		//get only custom post types
		$arrAllCustomType = get_post_types($arrArgs);

		//for post type post
		array_push($arrAllCustomType,"post");
		
		foreach ($arrAllCustomType  as $strPostType) {
			add_meta_box("contributors", "Contributors", "display_contributors_box",$strPostType,"normal","high");
		}
	}

	/**
	 *  Callback for metabox
	 */
	function display_contributors_box($post){
		//retreive all the users
		$arrUsers = get_users();
		$arrContributorCount = get_post_meta($post->ID, "contributor_count", true);
		?>
		<div id="contributor_metabox">
			<div class="tagcloud">
			<?php
				if($arrUsers) {
					foreach ($arrUsers as $objUser) { 
			?>
						<div class='tagval'><?php echo $objUser->user_login;?>
							<input type='checkbox' name='contributor[]' value='<?php echo $objUser->ID; ?>' 
								<?php if($arrContributorCount) {
									foreach ($arrContributorCount as $intContributor) {
										 if($intContributor == $objUser->ID) echo 'checked="checked"';
									}
								} 
								?>
							>
						</div>
			<?php 	} 
				}
			?>
			</div>
		</div>
		<?php 
	}

	/**
	 * Saving POSTMETA with save_post action
	 */
	add_action("save_post", "save_post_contributors",10,2);
	 
	function save_post_contributors($post_id,$post)
	{
		if($post->post_type == "post") {
			$arrContributorCount=get_post_meta($post->ID, "contributor_count", true);
			
			//Save the contributors for that post
			if(isset($_POST['contributor']) && $_POST['contributor']!='') {			
				update_post_meta($post_id, "contributor_count", $_POST['contributor']);
			} else {
				delete_post_meta($post_id, "contributor_count");
			}
		}
	}	
}


/**
 * load scripts and style necessary for backend 
*/
add_action("admin_head", "contributor_load_header");

function contributor_load_header()
{
	wp_register_style("admin-contributor-css", plugin_dir_url(__FILE__)."css/admin-contributor.css");
	wp_enqueue_style("admin-contributor-css");
}