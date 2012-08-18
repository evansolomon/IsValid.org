<!DOCTYPE html>
	<head>
		<title>IsValid.org - Quantify the results of A/B tests</title>
		<link rel="stylesheet" href="assets/css/style.css">
		<link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700|Slackey' rel='stylesheet' type='text/css'>
	</head>
	<body>
		<div id="wrapper">
			<hgroup>
				<h1>Is<span>Valid</span></h1>
			</hgroup>
			<div id="action">
				<form name="complete">
							I had <input type="number" name="con_con" id="con_con" autocomplete=off placeholder="10" autofocus required> <label for="con_con" id="con_con_label" data-tooltip="The number of conversions received with your current version">control conversions</label> out of <input type="number" name="con_sam" id="con_sam" autocomplete=off required><label for="con_sam" id="con_sam_label" data-tooltip="The total number of times your current version was shown to users">control samples</label> compared to <input type="number" name="test_con" id="test_con" autocomplete=off required> <label for="test_con" id="test_con_label" data-tooltip="The number of conversions received with your new (test) version">test conversions</label> in <input type="number" name="test_sam" id="test_sam" autocomplete=off required> <label for="test_sam" id="test_sam_label" data-tooltip="The total number of times your new (test) version was shown to users">test samples</label>.
							
							<input type="hidden" name="fx" id="fx" value="complete">
							<button type="submit">Is it Valid?</button>
				</form>
			</div>
			<div id="results">
				<div id="permalink"><span id="linkinfo"></span> <span id="linkembed"></span></div>
				<div class="resulting improvement">
				</div>
				<div class="resulting confidence">
				</div>
			</div>
			
			<div id="end"><a href="https://github.com/evansolomon/IsValid.org/wiki/API">API Documentation</a> | <a href="https://github.com/evansolomon/IsValid.org">Clone on GitHub</div>
		</div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="assets/js/jquery-1.8.0.min.js"><\/script>')</script>
		<script src="assets/js/isvalid.js"></script>
		<script src="assets/js/jquery.fittext.js"></script>
		<script>
		$("h1").fitText();
		</script>
	</body>
</html>
