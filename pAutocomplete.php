<?php
	include_once'/accessctrl.php';
	
	$mailList = '';
	$useremail = mysqli_result($result, 0,'user_Email');
	$usersOtherNames = mysqli_result($result, 0,'user_other_names');
	$userSurname = mysqli_result($result, 0,'user_surname');
	$termToFind = '';
	$jsArr = array();
	$jsDisp = array();
	$jsWrite = ''; //array();
	$nameList = '';
	global $mailList, $jsArr, $jsDisp;
	
	//if(isset($_GET['term'])){
		//$termToFind = $_GET['term'];
	//}
	
	if(isset($_REQUEST['term'])){
		$termToFind = trim($_REQUEST['term']);
	}
	
	//Handling recipient angle
	$query8 = sprintf("SELECT * FROM contacts_activity WHERE c_invite_receip_email = '%s'  AND c_invite_status = '%s' ORDER BY contact_Act_id DESC",
					mysqli_real_escape_string($dblink, $useremail),
					mysqli_real_escape_string($dblink, "approved")
					);
										
	$result8 = mysqli_query($dblink, $query8) or die("Error in query: $query8.".mysqli_error($dblink));
										
	if(mysqli_num_rows($result8) > 0){
		while($row = mysqli_fetch_object($result8)){
			$query9 = sprintf("SELECT * FROM users_details WHERE user_Email = '%s'", 
								mysqli_real_escape_string($dblink, $row->c_invite_init_email)
							);
			$result9 = mysqli_query($dblink, $query9) or die("Error in query: $query9.".mysqli_error($dblink));
												
			if(mysqli_num_rows($result9) > 0){
				while($row = mysqli_fetch_assoc($result9)){
					//$mailList .= $row->user_Email;
					//echo $row->user_other_names.' '.$row->user_surname."\n";
					$jsArr['id'] = $row['user_ID'];
					$jsArr['text'] = $row['user_other_names'].' '.$row['user_surname'];
					array_push($jsDisp, $jsArr);
				}
			}
		}
	}									
	
	//Handling from contact initiator angle.
	$query8 = sprintf("SELECT * FROM contacts_activity WHERE c_invite_init_email = '%s'  AND c_invite_status = '%s' ORDER BY contact_Act_id DESC",
							mysqli_real_escape_string($dblink, $useremail),
							mysqli_real_escape_string($dblink, "approved")
						);
										
	$result8 = mysqli_query($dblink, $query8) or die("Error in query: $query8.".mysqli_error($dblink));
										
	if(mysqli_num_rows($result8) > 0){
		while($row = mysqli_fetch_object($result8)){
			$query9 = sprintf("SELECT * FROM users_details WHERE user_Email = '%s'", 
								mysqli_real_escape_string($dblink, $row->c_invite_receip_email)
							);
			$result9 = mysqli_query($dblink, $query9) or die("Error in query: $query9.".mysqli_error($dblink));
												
			if(mysqli_num_rows($result9) > 0){
				while($row = mysqli_fetch_assoc($result9)){
					//$mailList .= $row->user_Email;
					//echo $row->user_other_names.' '.$row->user_surname."\n";
					$jsArr['id'] = $row['user_ID'];
					$jsArr['text'] = $row['user_other_names'].' '.$row['user_surname'];
					array_push($jsDisp, $jsArr);
				}
			}
		}
	}
	
	$query10 = sprintf("SELECT * FROM users_details WHERE user_Email <> '%s' AND user_other_names <> '%s' AND user_surname <> '%s'", 
						mysqli_real_escape_string($dblink, $useremail),
						mysqli_real_escape_string($dblink, $usersOtherNames),
						mysqli_real_escape_string($dblink, $userSurname)
					);
	$result10 = mysqli_query($dblink, $query10) or die("Error in query: $query10.".mysqli_error($dblink));
												
	if(mysqli_num_rows($result10) > 0){
		while($row = mysqli_fetch_assoc($result10)){
			//$mailList .= $row->user_Email;
			//echo $row->user_other_names.' '.$row->user_surname."\n";
			$jsArr['id'] = $row['user_ID'];
			$jsArr['text'] = $row['user_other_names'].' '.$row['user_surname'];
			array_push($jsDisp, $jsArr);
		}
	}
	
	//preping final autocomplete list based on user input.
	/*function listfilter ($nameList){
		global $termToFind;
		return stripos($nameList, $termToFind) !== false;
	}*/
	//print_r($jsDisp);
	
	$jsWrite = json_encode($jsDisp);
	//$jsWrite = json_encode(array_values(array_filter($jsDisp, "listfilter")));
	//print $jsWrite;
?>