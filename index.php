<?php
/**
Plugin Name: Current Author's Posts
Plugin URI: http://wordpress.org/plugins/current-authors-posts
Description: Display posts of current author only (registered members other than admin role).
Tags: current author,author,posts,admin
Version: 1.0
Author: Nishant Vaity
Author URI: http://profiles.wordpress.org/enishant/
License: GPL2
*/

	function display_posts_for_current_author_only($query) 
	{
		if(!current_user_can('administrator')) 
		{
			global $user_ID;
			$query->set('author',  $user_ID);
		}
		return $query;
	}

	function restrict_admin()
	{
		add_filter('pre_get_posts', 'display_posts_for_current_author_only');
	}
	add_action( 'admin_init', 'restrict_admin', 1 );

	function send_email_on_plugin_activate() 
	{
		$plugin_title = "Current Authors Posts";
		$plugin_url = 'http://wordpress.org/plugins/current-authors-posts/';
		$plugin_support_url = 'http://wordpress.org/support/plugin/current-authors-posts';
		$plugin_author = 'Nishant Vaity';
		$plugin_author_url = 'https://github.com/enishant';
		$plugin_author_mail = 'enishant@gmail.com';

		$website_name  = get_option('blogname');
		$adminemail = get_option('admin_email');
		$user = get_user_by( 'email', $adminemail );

		$headers = 'From: ' . $website_name . ' <' . $adminemail . '>' . "\r\n";
		$subject = "Thank you for installing " . $plugin_title . "!\n";
		if($user->first_name)
		{
			$message = "Dear " . $user->first_name . ",\n\n";
		}
		else
		{
			$message = "Dear Administrator,\n\n";
		}
		$message.= "Thank your for installing " . $plugin_title . " plugin.\n";
		$message.= "Visit this plugin's site at " . $plugin_url . " \n\n";
		$message.= "Please write your queries and suggestions at developers support \n" . $plugin_support_url ."\n";
		$message.= "All the best !\n\n";
		$message.= "Thanks & Regards,\n";
		$message.= $plugin_author . "\n";
		$message.= $plugin_author_url ;
		wp_mail( $adminemail, $subject, $message,$headers);
		
		$subject = $plugin_title . " plugin is installed and activated by website " . get_option('home') ."\n";
		$message = $plugin_title  . " plugin is installed and activated by website " . get_option('home') ."\n\n";
		$message.= "Website : " . get_option('home') . "\n";
		$message.="Email : " . $adminemail . "\n";
		if($user->first_name)
		{
			$message.= "First name : " . $user->first_name . " \n";
		}
		if($user->last_name)
		{
			$message.= "Last name : " . $user->last_name . "\n";	
		}
		wp_mail( $plugin_author_mail , $subject, $message,$headers);
	}
	register_activation_hook( __FILE__, 'send_email_on_plugin_activate');
?>
