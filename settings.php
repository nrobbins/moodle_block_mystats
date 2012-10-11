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
    get_string('allowuserconfiglink', 'block_mystats'),
		0
	));
}


