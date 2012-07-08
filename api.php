<?php

if(!isset($_REQUEST['function'])){
	include("api-doc.php");
	exit;
}

include("stats.php");
$response = array();

function cleanup_ints( $value ) {
	if( is_numeric( str_replace( ",", "", $value ) ) )
		return (int) str_replace( ",", "", $value );

	return $value;
}
$parsed_request = array_map( 'cleanup_ints', $_REQUEST );

switch($parsed_request['function']){

	case 'confidence';
		/* verify data: conversions=<samples, 0=<confidence<=1 */
		if($parsed_request['conversions'] == 0 || $parsed_request['samples'] == 0 || $parsed_request['samples'] < $parsed_request['conversions'] || (isset($parsed_request['confidence']) && ($parsed_request['confidence'] <= 0 || $parsed_request['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}

		if(isset($parsed_request['confidence'])){
			$interval = interval($parsed_request['conversions'],$parsed_request['samples'],$parsed_request['confidence']);
		}
		else{
			$interval = interval($parsed_request['conversions'],$parsed_request['samples']);
		}

		$response['results'] = $interval;
		$response['chart'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval['low']*100).'|'.($interval['average']*100).'|'.($interval['high']*100);
		break;

	case 'significance';
		/* verify data: conversions=<samples for control and experiment */
		if($parsed_request['conversions_control'] == 0 || $parsed_request['samples_control'] == 0 || $parsed_request['samples_experiment'] < $parsed_request['conversions_experiment']){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}

		$significance = greater($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment']);

		$response['results'] = $significance;
		$response['chart'] = 'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:'.($significance['experiment']*100);
		break;

	case 'improvement';
		/* verify data: conversions=<samples for control and experiment */
		if($parsed_request['conversions_control'] == 0 || $parsed_request['samples_control'] == 0 || $parsed_request['samples_control'] < $parsed_request['conversions_control'] || $parsed_request['conversions_experiment'] == 0 || $parsed_request['samples_experiment'] == 0 || $parsed_request['samples_experiment'] < $parsed_request['conversions_experiment'] || (isset($parsed_request['confidence']) && ($parsed_request['confidence'] <= 0 || $parsed_request['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}

		if(isset($parsed_request['confidence'])){
			$improvement = imp_pct($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment'],$parsed_request['confidence']);
		}
		else{
			$improvement = imp_pct($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment']);
		}

		$response['results'] = $improvement;
		$response['chart'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($improvement['low']*100+100)/2 .'|'. ($improvement['average']*100+100)/2 .'|'.($improvement['high']*100+100)/2;
		break;

	case 'complete';
		/* verify data: conversions=<samples for control and experiment */
		if($parsed_request['conversions_control'] == 0 || $parsed_request['samples_control'] == 0 || $parsed_request['samples_control'] < $parsed_request['conversions_control'] || $parsed_request['conversions_experiment'] == 0 || $parsed_request['samples_experiment'] == 0 || $parsed_request['samples_experiment'] < $parsed_request['conversions_experiment'] || (isset($parsed_request['confidence']) && ($parsed_request['confidence'] <= 0 || $parsed_request['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}

		/*confidence interval */
		if(isset($parsed_request['confidence'])){
			$interval_control = interval($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['confidence']);
			$interval_experiment = interval($parsed_request['conversions_experiment'],$parsed_request['samples_experiment'],$parsed_request['confidence']);
		}
		else{
			$interval_control = interval($parsed_request['conversions_control'],$parsed_request['samples_control']);
			$interval_experiment = interval($parsed_request['conversions_experiment'],$parsed_request['samples_experiment']);
		}

		$response['confidence']['results']['control'] = $interval_control;
		$response['confidence']['results']['experiment'] = $interval_experiment;
		$response['confidence']['chart']['control'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval_control['low']*100).'|'.($interval_control['average']*100).'|'.($interval_control['high']*100);
		$response['confidence']['chart']['experiment'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval_experiment['low']*100).'|'.($interval_experiment['average']*100).'|'.($interval_experiment['high']*100);


		/*significance*/
		$significance = greater($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment']);

		$response['significance']['results'] = $significance;
		$response['significance']['chart'] = 'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:'.($significance['experiment']*100);

		/*improvement*/
		if(isset($parsed_request['confidence'])){
			$improvement = imp_pct($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment'],$parsed_request['confidence']);
		}
		else{
			$improvement = imp_pct($parsed_request['conversions_control'],$parsed_request['samples_control'],$parsed_request['conversions_experiment'],$parsed_request['samples_experiment']);
		}

		$response['improvement']['results'] = $improvement;
		$response['improvement']['chart'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($improvement['low']*100+100)/2 .'|'. ($improvement['average']*100+100)/2 .'|'.($improvement['high']*100+100)/2;

		break;



	default;
		$response['error'] = array('message' => 'Invalid function');
		break;

}

echo json_encode($response);

?>