<?php
/*
	if( !session_id() )
		session_start();
	if( isset( $_GET ) ) {
		$_SESSION[]['queries'] = $_GET[ 'permalink' ] == 'true' ? $_GET : 0;
	}
*/
	$jq = "http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js";
	if($_SERVER['SERVER_NAME'] == 'localhost'){
		$jq = "assets/jquery-1.4.2.min.js";
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>IsValid.org - Quantify the results of A/B tests</title>
		<script src="<? echo $jq; ?>"></script>
		<script src="assets/s.js?ver=20120330b"></script>
		<link rel="stylesheet" href="assets/s.css?ver=20110824a">
		
	</head>
	<body>		
	
<!-- 
		<?php
/* 		$_SESSION['queries'] ? var_dump($_SESSION['queries']) : 0; */
		
		?>
		
-->
		<div id="action">
			<form name="complete">
					<div>
						<label for="con_con" id="con_con_label">Control Conversions</label>
						<input type="text" name="con_con" id="con_con" autocomplete=off>
					</div>
					<div>
						<label for="con_sam" id="con_sam_label">Control Samples</label>  
						<input type="text" name="con_sam" id="con_sam" autocomplete=off>
					</div>
					<div>
						<label for="test_con" id="test_con_label">Test Conversions</label>
						<input type="text" name="test_con" id="test_con" autocomplete=off>
					</div>
					<div>
						<label for="test_sam" id="test_sam_label">Test Samples</label>  
						<input type="text" name="test_sam" id="test_sam" autocomplete=off>
					</div>
					<div>
						<input type="hidden" name="fx" id="fx" value="complete">
						<input type="submit" value="Submit" class="button" id="submit_btn" />
					</div>
			</form>
			<br>
			<div id="api"><a href="v2/api">API Docs</a></div>
		</div>
		<div id="response_left">
		</div>
		<div id="response_right">
		</div>
	</body>
</html>
