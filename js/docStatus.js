//Script for setting the status of documents

function setStatus(){

	var status = 'opened';
	for(var i; i < document.all.docStatus.length; i++){
		if(document.all.docStatus[i].checked){
			var status = document.all.docStatus[i].value;
		}
		
		//document.getElementById("statusArea").innerHTML += "<?php $sql = 'UPDATE doc_files_details SET doc_status ="+ status +" WHERE doc_title ='. $docTitle; mysql_query($sql) or die('Error in query: $sql.'.mysql_error());?>";
		//break;
		
		self.location = "docpage.php?status="+status;
	}
}