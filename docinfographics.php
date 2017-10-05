<?php
	include'/accessctrl.php';
	
	//collection of variables passed in URL.
	if($_GET['title'] !=""){
		$title = $_GET['title'];
	}
	
	//Accessing minute database.	
	$query3 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s'",
					mysql_real_escape_string($title)
					);
	$result3 = mysql_query($query3) or die("Error in query: $query3.".mysql_error());	
	
	//generates the infographic of a document's movement.
	header("Content-type: image/gif");
	
	$basePix = ImageCreate(930, 609) or die("Oops! there's a little problem with displaying this image");
	
	$bg_color = ImageColorAllocate($basePix, 224, 255, 255);
	$text_color = ImageColorAllocate($basePix, 0, 0, 0);
	$text_color2 = ImageColorAllocate($basePix, 0, 100, 0);
	
	ImageString($basePix, 4, 20, 18, 'An Infographic of the track of the document titled: '.$title, $text_color);
	
	imageline($basePix, 10, 450,920, 450, $text_color);	
	
	//drawing the x- axis
	for($i = 10; $i <930; $i = $i + 29){
		ImageString($basePix, 4, $i, 450, "|", $text_color);
	}
	
//indicating the integers on the scale
	$i = 10;
	for($n = 0; $n < 32; $n++){
		Imagestring($basePix, 2, $i, 460, $n, $text_color);
		$i = $i + 29;
	}
		
//indicating document reception date points.
	if(mysql_num_rows($result3) > 0 && !empty($title)){
			$k = 1;
			$dispdate = "";
			
			for($j = 0;$j < mysql_num_rows($result3); $j++){
				if($dispdate != mysql_result($result3, $j,'date_of_dis')){
						$k = 1;
				}
				
				$ToEmail = mysql_result($result3, $j,'minute_respondant_email');
				$datepnt = explode("-", mysql_result($result3, $j,'date_of_dis'));
				$datept = $datepnt[0];
				$xcod = 13+($datept*29);
				$ycod = 450-($datept*10);
				Imagefilledellipse($basePix, $xcod, $ycod, 10, 10, $text_color);
				
				$query4 = sprintf("SELECT * FROM users_details WHERE user_Email ='%s'",
							mysql_real_escape_string($ToEmail)
							);
				$result4 = mysql_query($query4);
				$rfullname = mysql_result($result4, 0,'user_surname').', '.mysql_result($result4, 0,'user_other_names');
				
				//Accessing minute database to check for instances of same date.
				$dispdate = mysql_result($result3, $j,'date_of_dis');
				$query5 = sprintf("SELECT * FROM doc_minutes  WHERE doc_title ='%s' AND date_of_dis ='%s'",
								mysql_real_escape_string($title),
								mysql_real_escape_string($dispdate)
								);
				$result5 = mysql_query($query5) or die("Error in query: $query5.".mysql_error());
				
				if(mysql_num_rows($result5) == 1){
					ImageString($basePix, 4,$xcod , $ycod-20, $rfullname, $text_color2);
				}elseif(mysql_num_rows($result5) > 1){
					ImageString($basePix, 4,$xcod , $ycod-(28*$k), $rfullname, $text_color2);
					$k++;
				}
			}
	}	
	Imagegif($basePix);
?>
