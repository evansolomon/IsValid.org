function roundNumber(num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

$(document).ready(function() {
	$("#con_con").focus();
	
	$(".button").click(function() {
		var con_con = $("input#con_con").val();
		var con_sam = $("input#con_sam").val();
		var test_con = $("input#test_con").val();
		var test_sam = $("input#test_sam").val();
		var fx = $("input#fx").val();
		var dataString = 'conversions_control='+ con_con + '&samples_control=' + con_sam + '&conversions_experiment='+ test_con + '&samples_experiment=' + test_sam + '&function=' + fx;

		$.getJSON("api?" + dataString, function(stat_results){
			//clear g field
			$("#response_left").html("");
			$("#response_right").html("");
			
			//check for errors
			if(stat_results.error){
				return false;
			}

			//add text input with query args, then autofocus it
			var permalinkString = dataString.replace("conversions_control","cc").replace("samples_control","sc").replace("conversions_experiment","ce").replace("samples_experiment","se").replace("function","fx");
			$('<input class="permalink" value="http://'+location.host+location.pathname+'?'+permalinkString+'&permalink=true" type="text">').prependTo("#action").focus(); 
				$("input[type=text].permalink").focus(function(){
					$(this).select();
				})
				.mouseup(function(e){
					e.preventDefault();
				});
			
			//insert carousel div with unordered list
			$("<div id='carousel_left'></div>").appendTo("#response_left");
			$("<div id='carousel_right'></div>").appendTo("#response_right");
			
			
			//insert confidence charts			
			$("<div class='chart confidence'><a href='#'><img src='"+stat_results.confidence.chart.control+"' class='carousel-image' width='500' height='250' /></a><div class='num'>"+roundNumber(stat_results.confidence.results.control.low,2)+" - "+roundNumber(stat_results.confidence.results.control.high,2)+"</div><div class='cat'>Control performance</div></div>").appendTo("#carousel_left");
			$("<div class='chart confidence'><a href='#'><img src='"+stat_results.confidence.chart.experiment+"' class='carousel-image' width='500' height='250' /></a><div class='num'>"+roundNumber(stat_results.confidence.results.experiment.low,2)+" - "+roundNumber(stat_results.confidence.results.experiment.high,2)+"</div><div class='cat'>Test performance</div></div>").appendTo("#carousel_left");
			
			//insert significance chart
			$("<div class='chart significance'><a href='#'><img src='"+stat_results.significance.chart+"' class='carousel-image' width='500' height='250' /></a><div class='num'>"+roundNumber(stat_results.significance.results.experiment,2)+"</div><div class='cat'>Chance of outperformance</div></div>").appendTo("#carousel_right");
			
			//insert improvement chart
			$("<div class='chart improvement'><a href='#'><img src='"+stat_results.improvement.chart+"' class='carousel-image' width='500' height='250' /></a><div class='num'>"+roundNumber(stat_results.improvement.results.low,2)+" - "+roundNumber(stat_results.improvement.results.high,2)+"</div><div class='cat'>Likely improvement</div></div>").appendTo("#carousel_right");
						
		});

		return false;
	});
	
	//get results on permalink pages
	if(window.location.getParameter("permalink") == "true"){
		$("input#con_con").val(window.location.getParameter("cc"));
		$("input#con_sam").val(window.location.getParameter("sc"));
		$("input#test_con").val(window.location.getParameter("ce"));
		$("input#test_sam").val(window.location.getParameter("se"));
		$(".button").click();
	}
});