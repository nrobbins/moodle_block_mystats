<?php
/* pChart library inclusions */
include("pChart2.1.3/class/pData.class.php");
include("pChart2.1.3/class/pDraw.class.php");
include("pChart2.1.3/class/pPie.class.php");
include("pChart2.1.3/class/pImage.class.php");
/**
 *General Functions
 */
function block_mystats_avg($scores){

}
function block_mystats_highscore($scores){

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
	$myBlogPicture->setGraphArea(50,50,325,190);
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
class stat_group {
	
}