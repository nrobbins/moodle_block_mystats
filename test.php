<?php
echo 'one';
/* pChart library inclusions */
include("pChart2.1.3/class/pData.class.php");
include("pChart2.1.3/class/pDraw.class.php");
include("pChart2.1.3/class/pPie.class.php");
include("pChart2.1.3/class/pImage.class.php");
 
/* pData object creation */
$MyData = new pData();   
 
/* Data definition */
$MyData->addPoints(array(5,3),"Value");  
 
/* Labels definition */
$MyData->addPoints(array("Replies","Topics"),"Legend");
$MyData->setAbscissa("Legend");
 
/* Create the pChart object */
$myPicture = new pImage(300,150,$MyData);
 
/* Draw a gradient background */
//$myPicture->drawGradientArea(0,0,300,300,DIRECTION_HORIZONTAL,array("StartR"=>220,"StartG"=>220,"StartB"=>220,"EndR"=>180,"EndG"=>180,"EndB"=>180,"Alpha"=>100));
 
/* Add a border to the picture */
//$myPicture->drawRectangle(0,0,299,149,array("R"=>0,"G"=>0,"B"=>0));
 
/* Create the pPie object */ 
$PieChart = new pPie($myPicture,$MyData);
 
/* Enable shadow computing */ 
$myPicture->setShadow(FALSE);
 
/* Set the default font properties */ 
$myPicture->setFontProperties(array("FontName"=>"pChart2.1.3/fonts/Forgotte.ttf","FontSize"=>10,"R"=>80,"G"=>80,"B"=>80));
 
/* Draw a splitted pie chart */ 
$PieChart->draw3DPie(150,100,array("Radius"=>80,"DrawLabels"=>TRUE,"DataGapAngle"=>10,"DataGapRadius"=>6,"Border"=>TRUE));
 
/* Render the picture (choose the best way) */
$myPicture->Render("pie.png");
echo '<img src="pie.png">';
?>