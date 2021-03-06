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
 * @copyright  2012 onwards Nathan Robbins (https://github.com/nrobbins)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mystats extends block_base {

    // Initialize the block.
    function init() {
        $this->title = get_string('pluginname', 'block_mystats');
    }

    function has_config() {return true;}

    public function specialization() {
        global $CFG;
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        } else {
            $this->title = get_string('pluginname', 'block_mystats');
        }
        if (!empty($this->config->forum)){
            $this->userShowForum = $this->config->forum;
        } else {
            $this->userShowForum = 1;
        }
        if (!empty($this->config->blog)){
            $this->userShowBlog = $this->config->blog;
        } else {
            $this->userShowBlog = 1;
        }
        if (!empty($this->config->quiz)){
            $this->userShowQuiz = $this->config->quiz;
        } else {
            $this->userShowQuiz = 1;
        }
        if (!empty($this->config->lesson)){
            $this->userShowLesson = $this->config->lesson;
        } else {
            $this->userShowLesson = 1;
        }
        if (!empty($this->config->assignment)){
            $this->userShowAssignment = $this->config->assignment;
        } else {
            $this->userShowAssignment = 1;
        }
        if (!empty($this->config->msg)){
            $this->userShowMsg = $this->config->msg;
        } else {
            $this->userShowMsg = 1;
        }
        if (!empty($this->config->file)){
            $this->userShowFile = $this->config->file;
        } else {
            $this->userShowFile = 1;
        }
        if (empty($this->config->glossary)){
            $this->userShowGlossary = 1;
        } else {
            $this->userShowGlossary = $this->config->glossary;
        }
        if (empty($this->config->charts)){
            $this->userShowCharts = 1;
        } else {
            $this->userShowCharts = $this->config->charts;
        }
    }
    function applicable_formats() {
        if(get_config('mystats', 'allow_profile_page')){
            return array(
            'my-index' => true,
            'user-profile' => true,
            );
        } else {
            return array(
            'my-index' => true,
            );
        }
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
        if(isset($this->config)){
            $this->userShowCharts = $this->config->charts;
            $this->userShowForum = $this->config->forum;
            $this->userShowBlog = $this->config->blog;
            $this->userShowQuiz = $this->config->quiz;
            $this->userShowLesson = $this->config->lesson;
            $this->userShowAssignment = $this->config->assignment;
            $this->userShowMsg = $this->config->msg;
            $this->userShowFile = $this->config->file;
            $this->userShowGlossary = $this->config->glossary;
        }
        $userConfig = get_config('mystats','allow_user_config');
        if(get_config('mystats','show_charts')&&(($userConfig)&&($this->userShowCharts))||(!$userConfig)){
            $showCharts = true;
        } else {
            $showCharts = false;
        }
        $showForum = get_config('mystats', 'show_stats_forum');
        $showBlog = get_config('mystats', 'show_stats_blog');
        $showQuiz = get_config('mystats', 'show_stats_quiz');
        $showLesson = get_config('mystats', 'show_stats_lesson');
        $showAssignment = get_config('mystats', 'show_stats_assignment');
        $showMsg = get_config('mystats', 'show_stats_msg');
        $showFile = get_config('mystats', 'show_stats_file');
        $showGlossary = get_config('mystats', 'show_stats_glossary');
                                
        $this->content = new stdClass;
        $this->content->text  = '';
        
        /**
         *Forum Stats
         */
        if($showForum){
            if((($userConfig)&&($this->userShowForum))||(!$userConfig)){
                // Get stats.
                $myForumPosts = $DB->get_records_sql('SELECT * FROM {forum_posts} WHERE userid = ?', array($userId));
                $forumPosts = 0;
                $newTopics = 0;
                $forumPostId = 0;
                $forumPostModified = 0;
                foreach($myForumPosts as $post=>$info){
                    $forumPosts++;
                    if($info->parent == 0){
                        $newTopics++;
                    }
                    if($info->modified > $forumPostModified){
                        $forumPostModified = $info->modified;
                        $forumPostId = $info->id;
                        $latestForumPost = $info->subject;
                        $forumDiscussion = $info->discussion;
                    }
                }
                $forumReplies = ($forumPosts-$newTopics);
                $forumStats = array($newTopics, $forumReplies);

                // Output stats.
                $this->content->text  .= '<div id="mystats_forums" class="mystats_section"><h3>'.get_string('forums', 'block_mystats').'</h3>';
                if($forumPosts > 0){
                    $this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/mod/forum/user.php?id='.$userId.'">'.get_string('forumposts', 'block_mystats').'</a>: '.$forumPosts.'</p>';
                    if($private){
                        $this->content->text  .= '<p>'.get_string('latestpost', 'block_mystats').': <a href="'.$CFG->wwwroot.'/mod/forum/discuss.php?d='.$forumDiscussion.'">'.$latestForumPost.'</a></p>';
                    }
                } else {
                    $this->content->text  .= '<p>'.get_string('noposts', 'block_mystats').'</p>';
                }
                if($showCharts){
                    $this->content->text  .= block_mystats_forum_chart($newTopics, $forumReplies, get_string('forumtopics', 'block_mystats'), get_string('forumreplies', 'block_mystats'), $userId);
                } else {
                    $this->content->text  .= '<p>'.get_string('forumtopics', 'block_mystats').': '.$newTopics.'</p>';
                    $this->content->text  .= '<p>'.get_string('forumreplies', 'block_mystats').': '.$forumReplies.'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }

        /**
         *Blog Stats
         */

        if($showBlog){
            if((($userConfig)&&($this->userShowBlog))||(!$userConfig)){
                // Get stats.
                $myBlogPosts = $DB->get_records_sql('SELECT * FROM {post} WHERE userid = ?', array($userId));
                $blogPosts = 0;
                $coursePosts = 0;
                $modPosts = 0;
                $blogPostId = 0;
                $blogPostModified = 0;
                foreach($myBlogPosts as $post=>$info){
                    $blogPosts++;
                    if($info->courseid != 0){
                        $coursePosts++;
                    }
                    if($info->moduleid != 0){
                        $modPosts++;
                    }
                    if($info->lastmodified > $blogPostModified){
                        $blogPostModified = $info->lastmodified;
                        $blogPostId = $info->id;
                        $latestBlogPost = $info->subject;
                    }
                }

                // Output stats.
                $this->content->text  .= '<div id="mystats_blogs" class="mystats_section"><h3>'.get_string('blogs', 'block_mystats').'</h3>';
                if($blogPosts > 0){
                    $this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/blog/index.php?userid='.$userId.'">'.get_string('blogposts', 'block_mystats').'</a>: '.$blogPosts.'</p>';
                    if($private){
                        $this->content->text  .= '<p>'.get_string('latestpost', 'block_mystats').': <a href="'.$CFG->wwwroot.'/blog/index.php?entryid='.$blogPostId.'">'.$latestBlogPost.'</a></p>';
                    }
                } else {
                    $this->content->text  .= '<p>'.get_string('noposts', 'block_mystats').'</p>';
                }
                if($showCharts){
                    $this->content->text  .= block_mystats_blog_chart($blogPosts, $coursePosts, $modPosts, get_string('blogposts', 'block_mystats'),get_string('blogcourse', 'block_mystats'), get_string('blogactivity', 'block_mystats'), $userId);
                } else {
                    $this->content->text  .= '<p>'.get_string('blogcourse', 'block_mystats').': '.$coursePosts.'</p>';
                    $this->content->text  .= '<p>'.get_string('blogactivity', 'block_mystats').': '.$modPosts.'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }

        /**
         *Quiz Stats
         */

        if($showQuiz&&$private){ // Do not show quiz grades outside the My Courses page.
            if((($userConfig)&&($this->userShowQuiz))||(!$userConfig)){
                // Get stats.
                $quizScores = $DB->get_records_sql('SELECT grade FROM {quiz_grades} WHERE userid = ?', array($userId));
                $quizAverage = block_mystats_avg($quizScores);
                $quizHighest = block_mystats_highscore($quizScores);
                $quizAttempts = $DB->get_records_sql('SELECT * FROM {quiz_attempts} WHERE userid = ?', array($userId));
                $quizAttemptsNumber = 0;
                $quizAttemptId = 0;
                $quizAttemptModified = 0;
                foreach($quizAttempts as $id => $info){
                    $quizAttemptsNumber++;
                    if($info->timemodified > $quizAttemptModified){
                        $quizAttemptModified = $info->timemodified;
                        $quizAttemptId = $info->quiz;
                    }
                }
                if($quizAttemptId > 0){
                    // There is probably a better way to get this info...
                    $lastQuizAttempt = $DB->get_record_sql('SELECT name, course FROM {quiz} WHERE id = ?', array($quizAttemptId));
                    $quizCM = $DB->get_record_sql('SELECT id FROM {modules} WHERE name = ?',array('quiz'));
                    $quizId = $DB->get_record_sql('SELECT id FROM {course_modules} WHERE module = ? AND instance = ?',array($quizCM->id, $quizAttemptId));
                }

                // Output stats.
                $this->content->text  .= '<div id="mystats_quizzes" class="mystats_section"><h3>'.get_string('quizzes', 'block_mystats').'</h3>';

                if($quizAttemptId <= 0){
                    $this->content->text  .='<p>'.get_string('noattemptsrecorded',  'block_mystats').'</p><p>&nbsp;</p>';
                } else {
                    $this->content->text  .= '<p>'.get_string('quizattempt', 'block_mystats').': '.$quizAttemptsNumber.'</p>';
                    $this->content->text  .='<p>'.get_string('lastattempt', 'block_mystats').': <a href="'.$CFG->wwwroot.'/mod/quiz/view.php?id='.$quizId->id.'">'.$lastQuizAttempt->name.'</a></p>';
                }
                if($showCharts){
                    $this->content->text  .= block_mystats_quiz_chart($quizAverage, $quizHighest, get_string('quizavgscore', 'block_mystats'), get_string('quizhighscore', 'block_mystats'), get_string('quizscores', 'block_mystats'), get_string('scorepercent', 'block_mystats'), $userId);
                } else {
                    $this->content->text  .= '<p>'.get_string('quizavgscore', 'block_mystats').': '.$quizAverage.'</p>';
                    $this->content->text  .= '<p>'.get_string('quizhighscore', 'block_mystats').': '.$quizHighest.'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }
        
        /**
         *Lesson Stats
         */
        if($showLesson&&$private){ // Do not show lesson grades outside the My Courses page.
            if((($userConfig)&&($this->userShowLesson))||(!$userConfig)){
                // Get stats.
                $lessonScores = $DB->get_records_sql('SELECT grade FROM {lesson_grades} WHERE userid = ?', array($userId));
                $lessonAverage = block_mystats_avg($lessonScores);
                $lessonHighest = block_mystats_highscore($lessonScores);
                $lessonAttempts = $DB->get_records_sql('SELECT * FROM {lesson_attempts} WHERE userid = ?', array($userId));
                
                $lessonAttemptsNumber = 0;
                $lessonAttemptSeen = 0;
                foreach($lessonAttempts as $id=>$info){
                    $lessonAttemptsNumber++;
                    if($info->timeseen > $lessonAttemptSeen){
                        $lessonAttemptSeen = $info->timeseen;
                        $lessonAttemptId = $info->lessonid;
                    }
                }
                if($lessonAttemptsNumber > 0){
                    $lastLessonAttempt = $DB->get_record_sql('SELECT name, course FROM {lesson} WHERE id = ?', array($lessonAttemptId));
                    $lessonCM = $DB->get_record_sql('SELECT id FROM {modules} WHERE name = ?',array('lesson'));
                    $lessonId = $DB->get_record_sql('SELECT id FROM {course_modules} WHERE module = ? AND instance = ?', array($lessonCM->id, $lessonAttemptId));
                }

                // Output stats.
                $this->content->text  .= '<div id="mystats_lessons" class="mystats_section"><h3>'.get_string('lessons', 'block_mystats').'</h3>';
                if($lessonAttemptsNumber > 0){
                    $this->content->text  .= '<p>'.get_string('lessonattempt', 'block_mystats').': '.$lessonAttemptsNumber.'</p>';
                    $this->content->text  .='<p>'.get_string('lastattempt', 'block_mystats').': <a href="'.$CFG->wwwroot.'/mod/lesson/view.php?id='.$lessonId->id.'">'.$lastLessonAttempt->name.'</a></p>';
                } else {
                    $this->content->text  .='<p>'.get_string('noattemptsrecorded', 'block_mystats').'</p><p>&nbsp;</p>';
                }
                if($showCharts){
                    $this->content->text  .= block_mystats_quiz_chart($lessonAverage, $lessonHighest, get_string('quizavgscore', 'block_mystats'), get_string('quizhighscore', 'block_mystats'), get_string('lessonscores', 'block_mystats'), get_string('scorepercent', 'block_mystats'), $userId);
                } else {
                    $this->content->text  .= '<p>'.get_string('quizavgscore', 'block_mystats').': '.$lessonAverage.'</p>';
                    $this->content->text  .= '<p>'.get_string('quizhighscore', 'block_mystats').': '.$lessonHighest.'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }
        
        /**
         *Assignment Stats
         */

        if($showAssignment&&$private){ // Do not show assignment grades outside the My Courses page.
            if((($userConfig)&&($this->userShowAssignment))||(!$userConfig)){
                // Get stats.
                $assignmentAttempts = count($DB->get_records_sql('SELECT * FROM {assign_submission} WHERE userid = ?', array($userId)));
                $assignmentScores = $DB->get_records_sql('SELECT grade FROM {assign_grades} WHERE userid = ?', array($userId));
                $assignmentAverage = block_mystats_avg($assignmentScores);
                $assignmentHighest = block_mystats_highscore($assignmentScores);

                // Output stats.
                $this->content->text  .= '<div id="mystats_assignments" class="mystats_section"><h3>'.get_string('assignments', 'block_mystats').'</h3>';
                $this->content->text  .= '<p>'.get_string('assignmentattempt', 'block_mystats').': '.$assignmentAttempts.'</p><p>&nbsp;</p>';
                if($showCharts){
                    $this->content->text  .= block_mystats_quiz_chart($assignmentAverage, $assignmentHighest, get_string('quizavgscore', 'block_mystats'), get_string('quizhighscore', 'block_mystats'), get_string('assignmentscores', 'block_mystats'), get_string('scorepercent', 'block_mystats'), $userId);
                } else {
                    $this->content->text  .= '<p>'.get_string('quizavgscore', 'block_mystats').': '.$assignmentAverage.'</p>';
                    $this->content->text  .= '<p>'.get_string('quizhighscore', 'block_mystats').': '.$assignmentHighest.'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }
        
        /**
         *Message Stats
         */

        if($showMsg){
            if((($userConfig)&&($this->userShowMsg))||(!$userConfig)){
                // Get stats.
                $msgContact = count($DB->get_records_SQL('SELECT * FROM {message_contacts} WHERE userid = ?', array($userId)));
                $sentMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridfrom = ?', array($userId)));
                $rcvdMsg = count($DB->get_records_sql('SELECT * FROM {message} WHERE useridto = ?', array($userId)));

                // Output stats.
                $this->content->text  .= '<div id="mystats_messages" class="mystats_section"><h3>'.get_string('messages', 'block_mystats').'</h3>';
                $this->content->text  .= '<p>'.get_string('messagesent', 'block_mystats').': '.$sentMsg.'</p>';
                $this->content->text  .= '<p>'.get_string('messagereceived', 'block_mystats').': '.$rcvdMsg.'</p>';
                $this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/message/index.php">'.get_string('messagecontacts', 'block_mystats').'</a>: '.$msgContact.'</p>';
                $this->content->text  .= '</div>';
            }
        }
        
        /**
         *File Stats
         */

        if($showFile){
            if((($userConfig)&&($this->userShowFile))||(!$userConfig)){
                // Get stats.
                $privateFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'private', 'NULL')));
                $subFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'submission_files', 'NULL')));
                $attachFiles = count($DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea = ? AND mimetype != ?', array($userId, 'attachment', 'NULL')));
                $files = $DB->get_records_sql('SELECT * FROM {files} WHERE userid = ? AND filearea != ? AND mimetype !=?', array($userId, 'draft', 'NULL'));
                $totalSize = 0;
                $avgSize = 0;
                $fileNumber = 0;
                $largestFile = 0;
                $largestFileName = get_string('nofiles', 'block_mystats');
                foreach($files as $id => $info){
                        if($info->filesize > $largestFile){
                                $largestFile = $info->filesize;
                                $largestFileName = $info->filename;
                        }
                        $totalSize += $info->filesize;
                        $fileNumber++;
                }
                if($fileNumber>0){
                        $avgSize = $totalSize / $fileNumber;
                }
                
                // Output stats.
                $this->content->text  .= '<div id="mystats_files" class="mystats_section"><h3>'.get_string('files', 'block_mystats').'</h3>';
                $this->content->text  .= '<p><strong>'.get_string('totalfiles', 'block_mystats').': '.$fileNumber.'</strong></p>';
                $this->content->text  .= '<p><a href="'.$CFG->wwwroot.'/user/files.php">'.get_string('fileprivate', 'block_mystats').'</a>: '.$privateFiles.'</p>';
                $this->content->text  .= '<p>'.get_string('fileattached', 'block_mystats').': '.$attachFiles.'</p>';
                $this->content->text  .= '<p>'.get_string('filesubmitted', 'block_mystats').': '.$subFiles.'</p>';
                $this->content->text  .= '<p>'.get_string('filetotalsize', 'block_mystats').': '.$totalSize.' '.get_string('bytes', 'block_mystats').'</p>';
                $this->content->text  .= '<p>'.get_string('fileavgsize', 'block_mystats').': '.$avgSize.' '.get_string('bytes', 'block_mystats').'</p>';
                if($private){
                    $this->content->text  .= '<p>'.get_string('largestfile', 'block_mystats').': '.$largestFileName.' - '.$largestFile.' '.get_string('bytes', 'block_mystats').'.</p>';
                }
                $this->content->text  .= '</div>';
            }
        }
        
        /**
         *Glossary Stats
         */
        if($showGlossary){
            if((($userConfig)&&($this->userShowGlossary))||(!$userConfig)){
                // Get stats.
                $glossaryEntries = $DB->get_records_sql('SELECT * FROM {glossary_entries} WHERE userid = ?', array($userId));
                $glossaryTotal = count($glossaryEntries);
                $glossaryApproved = 0;
                $gloss_entry_time = 0;
                if($glossaryTotal > 0){
                    foreach($glossaryEntries as $entry=>$data){
                        if($data->approved == 1){
                            $glossaryApproved++;
                        }
                        if($data->timemodified > $gloss_entry_time){
                            $gloss_entry_time = $data->timemodified;
                            $gloss_id = $data->glossaryid;
                            $gloss_concept = $data->concept;
                        }
                    }
                }
                // Output stats.
                $this->content->text  .= '<div id="mystats_glossary" class="mystats_section"><h3>'.get_string('glossary', 'block_mystats').'</h3>';
                if($glossaryTotal > 0){
                    $this->content->text  .= '<p>'.get_string('glossaryentries', 'block_mystats').': '.$glossaryTotal.'</p>';            
                    $this->content->text  .= '<p>'.get_string('glossaryapproved', 'block_mystats').': '.$glossaryApproved.'</p>';
                    $this->content->text  .= '<p>'.get_string('latestglossary', 'block_mystats').': '.$gloss_concept.'</p>';
                } else {
                    $this->content->text  .= '<p>'.get_string('noglossary', 'block_mystats').'</p>';
                }
                $this->content->text  .= '</div>';
            }
        }

        $this->content->footer = '<div class="clearfix"></div>';
        return $this->content;
    }
}