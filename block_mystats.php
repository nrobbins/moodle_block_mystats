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
 * Main block code
 *
 * @package    block
 * @subpackage mystats
 * @copyright  2012 onwards Nathan Robbins
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mystats extends block_base {

    // Initialize the block
    function init() {
        $this->title = get_string('pluginname', 'block_mystats');
    }
		public function specialization() {
			global $CFG;
			if (!empty($this->config->title)) {
				$this->title = $this->config->title;
			} else {
				$this->title = get_string('pluginname', 'block_mystats');
			}
		}
    function applicable_formats() {
        return array(
				'my-index' => true,
				'user-profile' => true,
				);
    }
		public function instance_allow_multiple() {
			return true;
		}
    function get_content() {
        global $CFG, $OUTPUT, $USER, $DB;
        if ($this->content !== NULL) {
            return $this->content;
        }
				require_once($CFG->dirroot . '/blocks/mystats/lib.php');
        $this->content = new stdClass;
				//Getting Data
				$userId = $USER->id;
				$forumPosts = count($DB->get_records_sql('SELECT * FROM {forum_posts} WHERE userid = ?', array($userId)));
				$newTopics = count($DB->get_records_sql('SELECT * FROM {forum_posts} WHERE userid = ? AND parent=?', array($userId,0)));
				$msgContact = count($DB->get_records_SQL('SELECT * FROM {message_contacts} WHERE userid = ?', array($userId)));
				$sentMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridfrom = ?', array($userId)));
				$rcvdMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridto = ?', array($userId)));
				$blogPosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ?', array($userId)));
				$coursePosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ? AND courseid != ?', array($userId,0)));
				$modPosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ? AND moduleid != ?', array($userId,0)));
				$privateFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'private', 'NULL')));
				$subFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'submission_files', 'NULL')));
				$attachFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'attachment', 'NULL')));
				$quizAttempts = count($DB->get_records_sql('SELECT * FROM {quiz_attempts} WHERE userid = ?', array($userId)));
				$this->content->text  = '';
				$userConfig = get_config('mystats','allow_user_config');
				//Displaying Data
				//Forum Stats
				$this->content->text  .= '<div id="mystats_forums" class="mystats_section"><h3>Forums</h3>';
				$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/mod/forum/user.php?id='.$userId.'">Forum Posts</a>: '.$forumPosts.'</p>';
				//$this->content->text  .= '<p>Topics Started: '.$newTopics.'</p>';
				//$this->content->text  .= '<p>Replies: '.($forumPosts-$newTopics).'</p>';
				$this->content->text  .= block_mystats_forum($newTopics,($forumPosts-$newTopics));
				$this->content->text  .= '</div>';

				//Blog Stats
				$this->content->text  .= '<div id="mystats_blogs" class="mystats_section"><h3>Blogs</h3>';
				$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/blog/index.php?userid='.$userId.'">Blog Posts</a>: '.$blogPosts.'</p>';
				//$this->content->text  .= '<p>Associated with a Course: '.$coursePosts.'</p>';
				//$this->content->text  .= '<p>Associated with an Activity: '.$modPosts.'</p>';
				$this->content->text  .= block_mystats_blog($blogPosts,$coursePosts,$modPosts);
				$this->content->text  .= '</div>';
				//Message Stats
				$this->content->text  .= '<div id="mystats_messages" class="mystats_section"><h3>Messages</h3>';
				$this->content->text  .= '<p>Messages Sent: '.$sentMsg.'</p>';
				$this->content->text  .= '<p>Messages Recieved: '.$rcvdMsg.'</p>';
				$this->content->text  .= '<p>Message Contacts: '.$msgContact.'</p>';
				$this->content->text  .= '</div>';
				//File Stats
				$this->content->text  .= '<div id="mystats_files" class="mystats_section"><h3>Files</h3>';
				$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/user/files.php">Private Files</a>: '.$privateFiles.'</p>';
				$this->content->text  .= '<p>Attached to Posts: '.$attachFiles.'</p>';
				$this->content->text  .= '<p>Submitted for Assignments: '.$subFiles.'</p>';
				$this->content->text  .= '</div>';
				//Quiz Stats
				$this->content->text  .= '<div id="mystats_quizzes" class="mystats_section"><h3>Quizzes</h3>';
				$this->content->text  .= '<p>Quiz Attempts: '.$quizAttempts.'</p>';
				$this->content->text  .= '</div>';
        $this->content->footer = '<div class="clearfix"></div>';
				
				return $this->content;
		}
}