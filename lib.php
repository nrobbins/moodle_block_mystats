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
 *
 * @package    block
 * @subpackage mystats
 * @copyright  2012 onwards Nathan Robbins (https://github.com/nrobbins)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/* pChart library inclusions */
include("pChart2.1.3/class/pData.class.php");
include("pChart2.1.3/class/pDraw.class.php");
include("pChart2.1.3/class/pPie.class.php");
include("pChart2.1.3/class/pImage.class.php");
/**
 *General Functions
 */
function block_mystats_avg($scores){
	$numberScores = count($scores);
	$totalScores = 0;
	if($numberScores > 0){
		foreach($scores as $score => $data){
			$totalScores += $score;
		}
		$avgScore = $totalScores / $numberScores;
		return $avgScore.'%';
	} else {
		return get_string('nogrades','block_mystats');
	}
}
function block_mystats_highscore($scores){
	$highScore = 0;
	if(count($scores)>0){
		foreach($scores as $score => $data){
			if($score>$highScore){
				$highScore = $score;
			}
		}
		return $highScore.'%';
	} else {
		return get_string('nogrades','block_mystats');
	}
}
/**
 *Forum Functions
 */
function block_mystats_forum_chart($allTopics=0,$allReplies=0,$allTopicsString="New Topics",$allRepliesString="Replies"){
	/* pData object creation */
	$MyForumData = new pData();   
	/* Data definition */
	$MyForumData->addPoints(array($allReplies,$allTopics),"Value");  
	/* Labels definition */
	$MyForumData->addPoints(array($allRepliesString.': '.$allReplies,$allTopicsString.': '.$allTopics),"Legend");
	$MyForumData->setAbscissa("Legend");
	/* Create the pChart object */
	$myForumPicture = new pImage(350,150,$MyForumData);
	/* Create the pPie object */ 
	$forumPieChart = new pPie($myForumPicture,$MyForumData);
	/* Enable shadow computing */ 
	$myForumPicture->setShadow(FALSE);
	/* Set the default font properties */ 
	$myForumPicture->setFontProperties(array("FontName"=>"../blocks/mystats/pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>13,"R"=>80,"G"=>80,"B"=>80));
	/* Draw a splitted pie chart */ 
	$forumPieChart->draw3DPie(175,100,array("Radius"=>80,"DrawLabels"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));
	/* Render the picture */
	$myForumPicture->Render("forum.png");
	return '<img src="forum.png" alt="'.$allTopicsString.': '.$allTopics.', '.$allRepliesString.': '.$allReplies.'">';
}
/**
 *Blog Functions
 */
function block_mystats_blog_chart($allPosts=0,$assocCourse=0,$assocMod=0,$allPostsString,$assocCourseString,$assocModString){
	$myBlogData = new pData();
	$myBlogData->addPoints(array($allPosts,$assocCourse,$assocMod),"Serie1");
	$myBlogData->setSerieDescription("Serie1","Posts");
	$myBlogData->setSerieOnAxis("Serie1",0);

	$myBlogData->addPoints(array($allPostsString,$assocCourseString,$assocModString),"Absissa");
	$myBlogData->setAbscissa("Absissa");

	$myBlogData->setAxisPosition(0,AXIS_POSITION_LEFT);
	$myBlogData->setAxisName(0,"Posts");
	$myBlogData->setAxisUnit(0,"");

	$myBlogPicture = new pImage(350,230,$myBlogData);
	$Settings = array("R"=>255, "G"=>255, "B"=>255);
	$myBlogPicture->drawFilledRectangle(0,0,350,230,$Settings);

	$myBlogPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

	$myBlogPicture->setFontProperties(array("FontName"=>"../blocks/mystats/pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>14));
	$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
	, "R"=>68, "G"=>68, "B"=>68);
	$myBlogPicture->drawText(175,25,"Blog Posts",$TextSettings);

	$myBlogPicture->setShadow(FALSE);
	$myBlogPicture->setGraphArea(25,50,325,190);
	$myBlogPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../blocks/mystats/pChart2.1.3/fonts/pf_arma_five.ttf","FontSize"=>6));

	$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
	, "Mode"=>SCALE_MODE_START0
	, "LabelingMethod"=>LABELING_ALL
	, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
	$myBlogPicture->drawScale($Settings);

	$myBlogPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

	$blogConfig = array("DisplayValues"=>1, "Gradient"=>1);
	$myBlogPicture->drawBarChart($blogConfig);
	$myBlogPicture->Render("blog.png");
	return '<img src="blog.png" alt="'.$allPostsString.': '.$allPosts.', '.$assocCourseString.': '.$assocCourse.', '.$assocModString.': '.$assocMod.'">';
}
function block_mystats_quiz_chart($avg,$high,$strAvg,$strHigh,$strTitle,$strScale){
	$myQuizData = new pData();
	$myQuizData->addPoints(array($avg),"Serie1");
	$myQuizData->setSerieDescription("Serie1",$strAvg);
	$myQuizData->setSerieOnAxis("Serie1",0);

	$myQuizData->addPoints(array($high),"Serie2");
	$myQuizData->setSerieDescription("Serie2",$strHigh);
	$myQuizData->setSerieOnAxis("Serie2",0);

	$myQuizData->addPoints(array(" "),"Absissa");
	$myQuizData->setAbscissa("Absissa");

	$myQuizData->setAxisPosition(0,AXIS_POSITION_LEFT);
	$myQuizData->setAxisName(0,$strScale);
	$myQuizData->setAxisUnit(0,"");

	$myQuizPicture = new pImage(350,230,$myQuizData);
	$quizSettings = array("R"=>255, "G"=>255, "B"=>255);
	$myQuizPicture->drawFilledRectangle(0,0,350,230,$quizSettings);

	$myQuizPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

	$myQuizPicture->setFontProperties(array("FontName"=>"../blocks/mystats/pChart2.1.3/fonts/GeosansLight.ttf","FontSize"=>14));
	$TextSettings = array("Align"=>TEXT_ALIGN_TOPLEFT
	, "R"=>0, "G"=>0, "B"=>0);
	$myQuizPicture->drawText(25,25,$strTitle,$TextSettings);

	$myQuizPicture->setShadow(FALSE);
	$myQuizPicture->setGraphArea(25,70,325,210);
	$myQuizPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"../blocks/mystats/pChart2.1.3/fonts/pf_arma_five.ttf","FontSize"=>8));

	$AxisBoundaries = array(0=>array("Min"=>0,"Max"=>110),1=>array("Min"=>00,"Max"=>1));
	$quizSettings = array("Pos"=>SCALE_POS_TOPBOTTOM
	, "Mode"=>SCALE_MODE_MANUAL
	, "ManualScale"=>$AxisBoundaries
	, "LabelingMethod"=>LABELING_ALL
	, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
	$myQuizPicture->drawScale($quizSettings);

	$myQuizPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

	$quizConfig = array("DisplayValues"=>1, "Rounded"=>1, "AroundZero"=>1);
	$myQuizPicture->drawBarChart($quizConfig);

	$quizConfig = array("R"=>0, "G"=>0, "B"=>0, "Alpha"=>50, "AxisID"=>0, "Ticks"=>4, "Caption"=>"Threshold");
	$myQuizPicture->drawThreshold(0,$quizConfig);

	$quizConfig = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"../blocks/mystats/pChart2.1.3/fonts/pf_arma_five.ttf", "FontSize"=>8, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_ROUND
	, "Mode"=>LEGEND_VERTICAL
	, "Family"=>LEGEND_FAMILY_CIRCLE
	);
	$myQuizPicture->drawLegend(240,16,$quizConfig);
	$myQuizPicture->Render("$strTitle.png");
	return '<img src="'.$strTitle.'.png" alt="'.$strAvg.': '.$avg.', '.$strHigh.': '.$high.'">';
}
class stat_group {
	//output here
}