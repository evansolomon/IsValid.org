<?php

function print_header( $embed = false ) {
?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>IsValid | Quantify the results of A/B tests</title>

		<!--[if lt IE 9]>
			<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	<?php if ( $embed ): ?>
		<link href="static/embed.css" rel="stylesheet">
	<?php else: ?>
		<link href="static/styles.css" rel="stylesheet">
	<?php endif; ?>

		<!-- Local -->
		<script src="static/scripts.min.js"></script>

		<!-- Fonts -->
		<script type="text/javascript" src="//use.typekit.net/ivm8epx.js"></script>
		<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	</head>
<?php
}
