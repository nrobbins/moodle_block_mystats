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
				
				$location = $this->page->bodyid;
				if($location == 'page-my-index'){
					$userId = $USER->id;
					$private = true;
				} else {
					$userId = $_GET['id'];
					$private = false;
				}
				
				$userConfig = get_config('mystats','allow_user_config');
				$showForum = get_config('mystats','show_stats_forum');
				$showBlog = get_config('mystats','show_stats_blog');
				$showMsg = get_config('mystats','show_stats_msg');
				$showFile = get_config('mystats','show_stats_file');
				$showQuiz = get_config('mystats','show_stats_quiz');
				
        $this->content = new stdClass;
				$this->content->text  = '';
				//$this->content->text .= print_r($this->page->bodyid,true);
				/**
				 *Forum Stats
				 */
				
				if($showForum){
					//get records
					$forumPosts = count($DB->get_records_sql('SELECT * FROM {forum_posts} WHERE userid = ?', array($userId)));
					$newTopics = count($DB->get_records_sql('SELECT * FROM {forum_posts} WHERE userid = ? AND parent=?', array($userId,0)));
					$forumReplies = ($forumPosts-$newTopics);
					$forumStats = array($newTopics,$forumReplies);

					//output stats
					$this->content->text  .= '<div id="mystats_forums" class="mystats_section"><h3>'.get_string('forums','block_mystats').'</h3>';
					$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/mod/forum/user.php?id='.$userId.'">'.get_string('forumposts','block_mystats').'</a>: '.$forumPosts.'</p>';
					$this->content->text  .= block_mystats_forum_chart($newTopics,$forumReplies,get_string('forumtopics','block_mystats'),get_string('forumreplies','block_mystats'));
					$this->content->text  .= '</div>';
				}

				/**
				 *Blog Stats
				 */

				if($showBlog){
				//get records
					$blogPosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ?', array($userId)));
					$coursePosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ? AND courseid != ?', array($userId,0)));
					$modPosts = count($DB->get_records_sql('SELECT * FROM {post} WHERE userid = ? AND moduleid != ?', array($userId,0)));

					//output stats
					$this->content->text  .= '<div id="mystats_blogs" class="mystats_section"><h3>'.get_string('blogs','block_mystats').'</h3>';
					$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/blog/index.php?userid='.$userId.'">'.get_string('blogposts','block_mystats').'</a>: '.$blogPosts.'</p>';
					$this->content->text  .= block_mystats_blog_chart($blogPosts,$coursePosts,$modPosts,get_string('blogposts','block_mystats'),get_string('blogcourse','block_mystats'),get_string('blogactivity','block_mystats'));
					$this->content->text  .= '</div>';
				}
				
				/**
				 *Quiz Stats
				 */

				if($showQuiz&&$private){ //Do not show quiz grades outside the My Courses page
				//get records
				$quizAttempts = count($DB->get_records_sql('SELECT * FROM {quiz_attempts} WHERE userid = ?', array($userId)));
				$quizScores = $DB->get_records_sql('SELECT grade FROM {quiz_grades} WHERE userid = ?', array($userId));
				$quizAverage = block_mystats_avg($quizScores);
				$quizHighest = block_mystats_highscore($quizScores);

				//output stats
				$this->content->text  .= '<div id="mystats_quizzes" class="mystats_section"><h3>'.get_string('quizzes','block_mystats').'</h3>';
				$this->content->text  .= '<p>'.get_string('quizattempt','block_mystats').': '.$quizAttempts.'</p>';
				//$this->content->text  .= '<p>'.get_string('quizavgscore','block_mystats').': '.$quizAverage.'</p>';
				//$this->content->text  .= '<p>'.get_string('quizhighscore','block_mystats').': '.$quizHighest.'</p>';
				$this->content->text  .= block_mystats_quiz_chart($quizAverage,$quizHighest,get_string('quizavgscore','block_mystats'),get_string('quizhighscore','block_mystats'),get_string('quizscores','block_mystats'),get_string('scorepercent','block_mystats'));
				$this->content->text  .= '</div>';
				}
				
				/**
				 *Message Stats
				 */

				if($showMsg){
					//get records
					$msgContact = count($DB->get_records_SQL('SELECT * FROM {message_contacts} WHERE userid = ?', array($userId)));
					$sentMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridfrom = ?', array($userId)));
					$rcvdMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridto = ?', array($userId)));

					//output stats
					$this->content->text  .= '<div id="mystats_messages" class="mystats_section"><h3>'.get_string('messages','block_mystats').'</h3>';
					$this->content->text  .= '<p>'.get_string('messagesent','block_mystats').': '.$sentMsg.'</p>';
					$this->content->text  .= '<p>'.get_string('messagereceived','block_mystats').': '.$rcvdMsg.'</p>';
					$this->content->text  .= '<p>'.get_string('messagecontacts','block_mystats').': '.$msgContact.'</p>';
					$this->content->text  .= '</div>';
				}
				
				/**
				 *File Stats
				 */

				if($showFile){
					//get records
					$privateFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'private', 'NULL')));
					$subFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'submission_files', 'NULL')));
					$attachFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'attachment', 'NULL')));

					//output stats
					$this->content->text  .= '<div id="mystats_files" class="mystats_section"><h3>Files</h3>';
					$this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/user/files.php">Private Files</a>: '.$privateFiles.'</p>';
					$this->content->text  .= '<p>Attached to Posts: '.$attachFiles.'</p>';
					$this->content->text  .= '<p>Submitted for Assignments: '.$subFiles.'</p>';
					$this->content->text  .= '</div>';
				}
				
				
        $this->content->footer = '<div class="clearfix"></div>';
				return $this->content;
		}
}