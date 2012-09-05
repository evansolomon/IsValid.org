function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

$(document).ready(function() {
	$("#con_con").focus();

	$("button").click(function() {
		var con_con = $("input#con_con").val();
		var con_sam = $("input#con_sam").val();
		var test_con = $("input#test_con").val();
		var test_sam = $("input#test_sam").val();
		var fx = $("input#fx").val();
		var dataString = 'conversions_control='+ con_con + '&samples_control=' + con_sam + '&conversions_experiment='+ test_con + '&samples_experiment=' + test_sam + '&function=' + fx;

		$.getJSON("api?" + dataString, function(stat_results){
			//clear g field
			$("span#linkembed").html("");
			$("div.resulting").html("");

			//check for errors
			if(stat_results.error){
				return false;
			}

			//add text input with query args, then autofocus it
			var permalinkString = dataString.replace("conversions_control","cc").replace("samples_control","sc").replace("conversions_experiment","ce").replace("samples_experiment","se").replace("function","fx");
			$('<input class="permalink" value="http://'+location.host+location.pathname+'?'+permalinkString+'&permalink=true" type="text">').appendTo("span#linkembed").show("#permalink,span#linkembed").focus();
				$("input[type=text].permalink").focus(function(){
					$(this).select();
				})
				.mouseup(function(e){
					e.preventDefault();
				});
				$("#linkinfo").html("Permalink to this test:");

			//insert confidence charts
			var control_confidence = "<div class='chart confidence'><h2>Control performance</h2><img src='"+stat_results.confidence.chart.control+"' class='chart-image' alt='"+roundNumber(stat_results.confidence.results.control.high * 100,1)+"%'/><div class='num'>"+roundNumber(stat_results.confidence.results.control.low * 100,1)+" - "+roundNumber(stat_results.confidence.results.control.high * 100,1)+"%</div></div>";
			var experiment_confidence = "<div class='chart confidence'><h2>Test performance</h2><img src='"+stat_results.confidence.chart.experiment+"' class='chart-image' alt='"+roundNumber(stat_results.confidence.results.experiment.high * 100,1)+"%' /><div class='num'>"+roundNumber(stat_results.confidence.results.experiment.low * 100,1)+" - "+roundNumber(stat_results.confidence.results.experiment.high * 100,1)+"%</div></div>";

			$(control_confidence + experiment_confidence).appendTo(".confidence");

			//insert significance and improvement charts
			var significance = "<div class='chart significance'><h2>Chance of outperformance</h2><img src='"+stat_results.significance.chart+"' class='chart-image' alt='"+roundNumber(stat_results.significance.results.experiment * 100,1)+"%' /><div class='num'>"+roundNumber(stat_results.significance.results.experiment * 100,1)+"%</div></div>";
			var improvement = "<div class='chart improvement'><h2>Likely improvement</h2><img src='"+stat_results.improvement.chart+"' class='chart-image' alt='"+roundNumber(stat_results.improvement.results.high * 100,1)+"%'/><div class='num'>"+roundNumber(stat_results.improvement.results.low * 100,1)+" - "+roundNumber(stat_results.improvement.results.high * 100,1)+"%</div></div>";

			$(significance + improvement).appendTo(".improvement")
		});

		return false;
	});

	//get results on permalink pages
	function getParameter(paramName) {
		var searchString = window.location.search.substring(1),
			i, val, params = searchString.split("&");

		for (i=0;i<params.length;i++) {
			val = params[i].split("=");
			if (val[0] == paramName) {
				return unescape(val[1]);
			}
		}
		return null;
	}

	if(getParameter("permalink") == "true"){
		$("input#con_con").val(getParameter("cc"));
		$("input#con_sam").val(getParameter("sc"));
		$("input#test_con").val(getParameter("ce"));
		$("input#test_sam").val(getParameter("se"));
		$(".button").click();
	}
});
