function roundNumber(num, dec) {
	return Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
}

function getQueryString(con_con, con_sam, test_con, test_sam) {
	return 'conversions_control='+
		con_con+
		'&samples_control='+
		con_sam+
		'&conversions_experiment='+
		test_con+
		'&samples_experiment='+
		test_sam;
}

function getPermalink(con_con, con_sam, test_con, test_sam) {
	var queryString = getQueryString(con_con, con_sam, test_con, test_sam);
	return queryString.
		replace("conversions_control","cc").
		replace("samples_control","sc").
		replace("conversions_experiment","ce").
		replace("samples_experiment","se");
}

function displayPermalink(con_con, con_sam, test_con, test_sam) {
	var permalinkString = getPermalink(con_con, con_sam, test_con, test_sam);
	$('<input class="permalink" value="http://'+
		location.host+
		location.pathname+
		'?'+
		permalinkString+
		'" type="text">').
	prependTo("#action").focus();

	// Focus on click
	$("input[type=text].permalink").focus(function(){
		$(this).select();
	}).mouseup(function(e){
		e.preventDefault();
	});
}

function isPermalinkPage() {
	if(! getParameter("cc"))
		return false;
	if(! getParameter("sc"))
		return false;
	if(! getParameter("ce"))
		return false;
	if(! getParameter("se"))
		return false;

	return true;
}

function updateCharts(stat_results) {
	// Insert confidence charts
	var control_confidence = "<div class='chart confidence'><img src='"+
		stat_results.confidence.chart.control+
		"' class='chart-image' /><div class='num'>"+
		roundNumber(stat_results.confidence.results.control.low * 100,1)+
		" - "+
		roundNumber(stat_results.confidence.results.control.high * 100,1)+
		"%</div><div class='cat'>Control performance</div></div>";

	var experiment_confidence = "<div class='chart confidence'><img src='"+
		stat_results.confidence.chart.experiment+
		"' class='chart-image' /><div class='num'>"+
		roundNumber(stat_results.confidence.results.experiment.low * 100,1)+
		" - "+
		roundNumber(stat_results.confidence.results.experiment.high * 100,1)+
		"%</div><div class='cat'>Test performance</div></div>";

	$(control_confidence + experiment_confidence).appendTo(".column.left");

	// Insert significance and improvement charts
	var significance = "<div class='chart significance'><img src='"+
		stat_results.significance.chart+
		"' class='chart-image' /><div class='num'>"+
		roundNumber(stat_results.significance.results.experiment * 100,1)+
		"%</div><div class='cat'>Chance of outperformance</div></div>";

	var improvement = "<div class='chart improvement'><img src='"+
		stat_results.improvement.chart+
		"' class='chart-image' /><div class='num'>"+
		roundNumber(stat_results.improvement.results.low * 100,1)+
		" - "+
		roundNumber(stat_results.improvement.results.high * 100,1)+
		"%</div><div class='cat'>Likely improvement</div></div>";

	$(significance + improvement).appendTo(".column.right");
}

function cacheLastQuery(queryString) {
	cacheLastQuery.cache = queryString;
}

function getLastQuery() {
	if(typeof cacheLastQuery.cache == 'undefined')
		return false;

	return cacheLastQuery.cache;
}

function getResults(con_con, con_sam, test_con, test_sam) {
	var queryString = getQueryString(con_con, con_sam, test_con, test_sam);

	// Don't run the same query twice in a row
	if(queryString == getLastQuery())
		return false;

	cacheLastQuery(queryString);

	return queryAPI(con_con, con_sam, test_con, test_sam).done(function(stat_results) {
		$(".column").empty();

		// Check for errors
		if(stat_results.error)
			return false;

		updateCharts(stat_results);
		displayPermalink(con_con, con_sam, test_con, test_sam);
	});
}

function queryAPI(con_con, con_sam, test_con, test_sam) {
	var queryString = getQueryString(con_con, con_sam, test_con, test_sam);
	return $.getJSON("api?" + queryString);
}

function getParameter(paramName) {
	var searchString = window.location.search.substring(1),
		i, val, params = searchString.split("&");

	for (i=0;i<params.length;i++) {
		val = params[i].split("=");

		if (val[0] == paramName)
			return unescape(val[1]);
	}
	return null;
}

function isFormComplete() {
	if(! $("input#con_con").val())
		return false;
	if(! $("input#con_sam").val())
		return false;
	if(! $("input#test_con").val())
		return false;
	if(! $("input#test_sam").val())
		return false;

	return true;
}

$(function() {
	// Focus on the first input
	$("form :input:visible:first").first().focus();

	// Listen for form submit
	$("form").on('submit', function(event){
		event.preventDefault();

		// Don't submit incomplete forms
		if(! isFormComplete())
			return false;

		getResults(
			$("input#con_con").val(),
			$("input#con_sam").val(),
			$("input#test_con").val(),
			$("input#test_sam").val()
		);
	});

	// Auto-load results on permalink pages
	if(isPermalinkPage()){
		$("input#con_con").val(getParameter("cc"));
		$("input#con_sam").val(getParameter("sc"));
		$("input#test_con").val(getParameter("ce"));
		$("input#test_sam").val(getParameter("se"));

		getResults(
			getParameter("cc"),
			getParameter("sc"),
			getParameter("ce"),
			getParameter("se")
		);
	}

	// Auto-submit the form
	var keyup_timer;
	$("form input[type=text]").keyup(function(){
		clearTimeout(keyup_timer);
		keyup_timer = setTimeout(function(){
			$('form').submit();
		}, 800);
	});
});
