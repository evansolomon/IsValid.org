<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>IsValid.org - Quantify the results of A/B tests</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="assets/s.js?ver=20120320"></script>
		<link rel="stylesheet" href="assets/s.css?ver=20110824a">

	</head>
	<body>
		<div id="wrapper">
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
				<div id="api"><a href="api">API Docs</a></div>
			</div>
			<div id="results-wrapper">
				<div class="column left">
				</div>
				<div class="column right">
				</div>
			</div>
		</div>
	</body>
</html>
