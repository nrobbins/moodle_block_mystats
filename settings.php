<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Global settings
 *
 * @package    block
 * @subpackage mystats
 * @copyright  2012 onwards Nathan Robbins (https://github.com/nrobbins)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

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
        1
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/allow_profile_page',
        get_string('allowprofile', 'block_mystats'),
        get_string('allowprofiletext', 'block_mystats'),
        1
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_charts',
        get_string('showcharts', 'block_mystats'),
        get_string('showchartstext', 'block_mystats'),
        1
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
        'mystats/show_stats_quiz',
        get_string('showquiz', 'block_mystats'),
        get_string('showquiztext', 'block_mystats'),
        1
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_stats_lesson',
        get_string('showlesson', 'block_mystats'),
        get_string('showlessontext', 'block_mystats'),
        1
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_stats_assignment',
        get_string('showassignment', 'block_mystats'),
        get_string('showassignmenttext', 'block_mystats'),
        1
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_stats_msg',
        get_string('showmsg', 'block_mystats'),
        get_string('showmsgtext', 'block_mystats'),
        0
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_stats_file',
        get_string('showfile', 'block_mystats'),
        get_string('showfiletext', 'block_mystats'),
        0
    ));
  $settings->add(new admin_setting_configcheckbox(
        'mystats/show_stats_glossary',
        get_string('showglossary', 'block_mystats'),
        get_string('showglossarytext', 'block_mystats'),
        0
    ));
}