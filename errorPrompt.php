<?php

	function errorCall($msg){
?>

<html>
	<head>
		<script language = "JavaScript">
			<!--
				alert("<? = $msg?>");
				history.back();
			//-->
		</script>
	</head>
	<body></body>
</html>

<?php
	exit;
	}
?>