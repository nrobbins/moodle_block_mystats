<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
	$settings->add(new admin_setting_heading(
		'adminheading',
		get_string('adminheading', 'block_mystats'),
		get_string('admintext', 'block_mystats')
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/allow_user_config',
		get_string('allowuserconfig', 'block_mystats'),
    get_string('allowuserconfigtext', 'block_mystats'),
		0
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/show_stats_forum',
		get_string('showforum', 'block_mystats'),
    get_string('showforumtext', 'block_mystats'),
		1
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/show_stats_blog',
		get_string('showblog', 'block_mystats'),
    get_string('showblogtext', 'block_mystats'),
		1
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/show_stats_msg',
		get_string('showmsg', 'block_mystats'),
    get_string('showmsgtext', 'block_mystats'),
		1
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/show_stats_file',
		get_string('showfile', 'block_mystats'),
    get_string('showfiletext', 'block_mystats'),
		1
	));
  $settings->add(new admin_setting_configcheckbox(
		'mystats/show_stats_quiz',
		get_string('showquiz', 'block_mystats'),
    get_string('showquiztext', 'block_mystats'),
		1
	));
}


