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
 * Edit form
 *
 * @package    block
 * @subpackage mystats
 * @copyright  2012 onwards Nathan Robbins (https://github.com/nrobbins)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mystats_edit_form extends block_edit_form {
 
    protected function specific_definition($mform) {
        $context = $this->page->context;
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block_mystats'));

        // Give the block a title
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_mystats'));
        $mform->setDefault('config_title', get_string('pluginname', 'block_mystats'));
        $mform->setType('config_title', PARAM_MULTILANG);
        
        $mform->addHelpButton('config_title', 'blocktitle', 'block_mystats');

        $userConfig = get_config('mystats', 'allow_user_config');
        $showCharts = get_config('mystats', 'show_charts');
        $showForum = get_config('mystats', 'show_stats_forum');
        $showBlog = get_config('mystats', 'show_stats_blog');
        $showQuiz = get_config('mystats', 'show_stats_quiz');
        $showLesson = get_config('mystats', 'show_stats_lesson');
        $showAssignment = get_config('mystats', 'show_stats_assignment');
        $showMsg = get_config('mystats', 'show_stats_msg');
        $showFile = get_config('mystats', 'show_stats_file');
        $showGlossary = get_config('mystats', 'show_stats_glossary');
        
        if($userConfig){ // If the user is allowed to configure the block...

            if($showCharts){
                $mform->addElement('advcheckbox', 'config_charts', get_string('showcharts', 'block_mystats'), get_string('showchartstext', 'block_mystats'));
                $mform->setDefault('config_charts', 1);
            }

            if($showForum){
                $mform->addElement('advcheckbox', 'config_forum', get_string('showforum', 'block_mystats'), get_string('showforumtext', 'block_mystats'));
                $mform->setDefault('config_forum', 1);
            }

            if($showBlog){
                $mform->addElement('advcheckbox', 'config_blog', get_string('showblog', 'block_mystats'), get_string('showblogtext', 'block_mystats'));
                $mform->setDefault('config_blog', 1);
            }

            if($showQuiz){
                $mform->addElement('advcheckbox', 'config_quiz', get_string('showquiz', 'block_mystats'), get_string('showquiztext', 'block_mystats'));
                $mform->setDefault('config_quiz', 1);
            }

            if($showLesson){
                $mform->addElement('advcheckbox', 'config_lesson', get_string('showlesson', 'block_mystats'), get_string('showlessontext', 'block_mystats'));
                $mform->setDefault('config_lesson', 1);
            }

            if($showAssignment){
                $mform->addElement('advcheckbox', 'config_assignment', get_string('showassignment', 'block_mystats'), get_string('showassignmenttext', 'block_mystats'));
                $mform->setDefault('config_assignment', 1);
            }

            if($showMsg){
                $mform->addElement('advcheckbox', 'config_msg', get_string('showmsg', 'block_mystats'), get_string('showmsgtext', 'block_mystats'));
                $mform->setDefault('config_msg', 1);
            }

            if($showFile){
                $mform->addElement('advcheckbox', 'config_file', get_string('showfile', 'block_mystats'), get_string('showfiletext', 'block_mystats'));
                $mform->setDefault('config_file', 1);
            }

            if($showGlossary){
                $mform->addElement('advcheckbox', 'config_glossary', get_string('showglossary', 'block_mystats'), get_string('showglossarytext', 'block_mystats'));
                $mform->setDefault('config_glossary', 1);
            }
        }
    } 
}
