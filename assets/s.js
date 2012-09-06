function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function getQueryString(con_con, con_sam, test_con, test_sam, fx) {
	return 'conversions_control='+ con_con + '&samples_control=' + con_sam + '&conversions_experiment='+ test_con + '&samples_experiment=' + test_sam + '&function=' + fx;
}

function getPermalink(con_con, con_sam, test_con, test_sam, fx) {
	var queryString = getQueryString(con_con, con_sam, test_con, test_sam, fx);

	//add text input with query args, then autofocus it
	return queryString.replace("conversions_control","cc").replace("samples_control","sc").replace("conversions_experiment","ce").replace("samples_experiment","se").replace("function","fx");
}

function displayPermalink(permalinkString) {
	$('<input class="permalink" value="http://'+location.host+location.pathname+'?'+permalinkString+'&permalink=true" type="text">').prependTo("#action").focus();

	$("input[type=text].permalink").focus(function(){
		$(this).select();
	}).mouseup(function(e){
		e.preventDefault();
	});
}


function queryAPI(con_con, con_sam, test_con, test_sam, fx) {
	var queryString = getQueryString(con_con, con_sam, test_con, test_sam, fx);

	$.getJSON("api?" + queryString, function(stat_results){
		//clear g field
		$(".column").html("");

		//check for errors
		if(stat_results.error)
			return false;

		displayPermalink(con_con, con_sam, test_con, test_sam, fx);

		//insert confidence charts
		var control_confidence = "<div class='chart confidence'><img src='"+stat_results.confidence.chart.control+"' class='chart-image' /><div class='num'>"+roundNumber(stat_results.confidence.results.control.low * 100,1)+" - "+roundNumber(stat_results.confidence.results.control.high * 100,1)+"%</div><div class='cat'>Control performance</div></div>";
		var experiment_confidence = "<div class='chart confidence'><img src='"+stat_results.confidence.chart.experiment+"' class='chart-image' /><div class='num'>"+roundNumber(stat_results.confidence.results.experiment.low * 100,1)+" - "+roundNumber(stat_results.confidence.results.experiment.high * 100,1)+"%</div><div class='cat'>Test performance</div></div>";

		$(control_confidence + experiment_confidence).appendTo(".column.left");

		//insert significance and improvement charts
		var significance = "<div class='chart significance'><img src='"+stat_results.significance.chart+"' class='chart-image' /><div class='num'>"+roundNumber(stat_results.significance.results.experiment * 100,1)+"%</div><div class='cat'>Chance of outperformance</div></div>";
		var improvement = "<div class='chart improvement'><img src='"+stat_results.improvement.chart+"' class='chart-image' /><div class='num'>"+roundNumber(stat_results.improvement.results.low * 100,1)+" - "+roundNumber(stat_results.improvement.results.high * 100,1)+"%</div><div class='cat'>Likely improvement</div></div>";

		$(significance + improvement).appendTo(".column.right");
	});

	return false;
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

$(function() {
	$("form :input:visible:first").first().focus();

	$("form").on('submit', function(event){
		event.preventDefault();
		queryAPI(
			$("input#con_con").val(),
			$("input#con_sam").val(),
			$("input#test_con").val(),
			$("input#test_sam").val(),
			$("input#fx").val()
		);
	});

	//get results on permalink pages
	if(getParameter("permalink") == "true"){
		$("input#con_con").val(getParameter("cc"));
		$("input#con_sam").val(getParameter("sc"));
		$("input#test_con").val(getParameter("ce"));
		$("input#test_sam").val(getParameter("se"));
		queryAPI(
			getParameter("cc"),
			getParameter("sc"),
			getParameter("ce"),
			getParameter("se"),
			getParameter("fx")
		);
	}
});
