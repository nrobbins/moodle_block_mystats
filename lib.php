<?php
/* pChart library inclusions */
include("pChart2.1.3/class/pData.class.php");
include("pChart2.1.3/class/pDraw.class.php");
include("pChart2.1.3/class/pPie.class.php");
include("pChart2.1.3/class/pImage.class.php");
function block_mystats_forum($allTopics=0,$allReplies=0,$allTopicsString="Topics",$allRepliesString="Replies"){
	/* pData object creation */
	$MyData = new pData();   
	/* Data definition */
	$MyData->addPoints(array($allReplies,$allTopics),"Value");  
	/* Labels definition */
	$MyData->addPoints(array($allRepliesString.': '.$allReplies,$allTopicsString.': '.$allTopics),"Legend");
	$MyData->setAbscissa("Legend");
	/* Create the pChart object */
	$myPicture = new pImage(300,150,$MyData);
	/* Create the pPie object */ 
	$PieChart = new pPie($myPicture,$MyData);
	/* Enable shadow computing */ 
	$myPicture->setShadow(FALSE);
	/* Set the default font properties */ 
	$myPicture->setFontProperties(array("FontName"=>"../blocks/mystats/pChart2.1.3/fonts/GeosansLight.ttf","FontSize"=>12,"R"=>80,"G"=>80,"B"=>80));
	/* Draw a splitted pie chart */ 
	$PieChart->draw3DPie(150,100,array("Radius"=>80,"DrawLabels"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));
	/* Render the picture */
	$myPicture->Render("forum.png");
	return '<img src="forum.png">';
}
function block_mystats_blog($allPosts=0,$assocCourse=0,$assocMod=0,$allPostsString="Blog Posts",$assocCourseString="Associated with Courses",$assocModString="Associated with Activities"){

}