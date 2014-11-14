<?php
/*
Plugin Name: PMPro Protect Uploads
Plugin URI: http://www.paidmembershipspro.com/wp/pmpro-protect-uploads/
Description: Redirects traffic to files in the uploads folder through the PMPro getfile script to check for member access.
Version: .2
Author: Stranger Studios
Author URI: http://www.strangerstudios.com
*/

define('PMPRO_GETFILE_ENABLED', true);

/*
	Code that generates and adds the rewrite rule.
	Just directing all /wp-content/uploads/... to the getfile.php script in Paid Memberships Pro.
*/
function pmpropu_add_rewrite_rule()
{
	//is PMPro active?
	if(!defined('PMPRO_URL'))
		return;
	
	//get directories
	$upload_dir = wp_upload_dir();
	$upload_url = str_replace(home_url() . "/", "", $upload_dir['baseurl']);
	$pmpro_url = str_replace(home_url() . "/", "", PMPRO_URL);		
		
	//okay setup rule
	add_rewrite_rule(
	  $upload_url . '/(.*)$',
	  $pmpro_url . '/services/getfile.php',
	  'top'
	);	
}

/*
	Add our rewrite rule on activation.
*/
function pmpropu_activation()
{
	pmpropu_add_rewrite_rule();
	flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'pmpropu_activation');

/*
  Add rule on init in case another plugin flushes,
  but don't flush cause it's expensive
*/
function pmpropu_init()
{
	pmpropu_add_rewrite_rule();
}
add_action('init', 'pmpropu_init');

//Fush rewrite rules on deactivation to remove our rule.
function pmpropu_deactivation()
{
	flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'pmpropu_deactivation');
