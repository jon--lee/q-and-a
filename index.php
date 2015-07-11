<?php
/*
Plugin Name: Q and A Widget
Description: Widget that appears in the campaign sidebar and allows users to ask questions to the campaign owner who can then reply. Question and answers will appear in the sidebar.
Author: Jonathan Lee
Version 1.0.0
Author URI: http://www.jonathannlee.com
 */

if(!defined('ABSPATH')) {
	die('1');
}

add_action('widgets_init', function(){
	register_widget('Q_And_A_Widget');
});

/**
 * Adds Q_And_A_Widget
 */
class Q_And_A_Widget extends WP_Widget {
	/**
	 * register with wordpress
	 */
	function __construct(){
		parent::__construct(
			'Q_And_A_Widget',
			__('Q And A Widget', 'text_domain'),
			array('description'=>__('Widget that appears in the campaign sidebar and allows users to ask questions to the campaign owner who can then reply. Question and answers will appear in the sidebar.'),)
		);
	}

	/**
	 * front-end display
	 */
	public function widget($args, $instance){
		wp_enqueue_script('qaa-js', plugins_url('qaa.js', __FILE__));
		wp_enqueue_style('qaa', plugins_url('qaa.css', __FILE__));
		$template = locate_template(array('qaa-template.php'));
		if($template=='') $template = 'qaa-template.php';
		include($template);
	}
}

global $wpdb;
$table_name = $wpdb->prefix . 'questionandanswer';
$charset_collate = $wpdb->get_charset_collate();
$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		asker_id int(11) NOT NULL,
		answerer_id int(11) NOT NULL,
		campaign_id int(11) NOT NULL,
		question text NOT NULL,
		answer text NOT NULL,
		answered int(1) DEFAULT 0 NOT NULL,
		message_id int(11) NOT NULL,
		UNIQUE KEY id (id)
) $charset_collate;";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
dbDelta($sql);

