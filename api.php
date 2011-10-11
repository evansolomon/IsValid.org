<?php

if(!isset($_REQUEST['function'])){
	include("api-doc.php");
	exit;
}

include("stats.php");
$response = array();

switch($_REQUEST['function']){

	case 'confidence';
		/* verify data: conversions=<samples, 0=<confidence<=1 */
		if($_REQUEST['conversions'] == 0 || $_REQUEST['samples'] == 0 || $_REQUEST['samples'] < $_REQUEST['conversions'] || (isset($_REQUEST['confidence']) && ($_REQUEST['confidence'] <= 0 || $_REQUEST['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}
		
		if(isset($_REQUEST['confidence'])){
			$interval = interval($_REQUEST['conversions'],$_REQUEST['samples'],$_REQUEST['confidence']);
		}
		else{
			$interval = interval($_REQUEST['conversions'],$_REQUEST['samples']);
		}
		
		$response['results'] = $interval;
		$response['chart'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval['low']*100).'|'.($interval['average']*100).'|'.($interval['high']*100);
		break;
	
	case 'significance';
		/* verify data: conversions=<samples for control and experiment */
		if($_REQUEST['conversions_control'] == 0 || $_REQUEST['samples_control'] == 0 || $_REQUEST['samples_experiment'] < $_REQUEST['conversions_experiment']){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}

		$significance = greater($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment']);
		
		$response['results'] = $significance;
		$response['chart'] = 'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:'.($significance['experiment']*100);
		break;
		
	case 'improvement';
		/* verify data: conversions=<samples for control and experiment */
		if($_REQUEST['conversions_control'] == 0 || $_REQUEST['samples_control'] == 0 || $_REQUEST['samples_control'] < $_REQUEST['conversions_control'] || $_REQUEST['conversions_experiment'] == 0 || $_REQUEST['samples_experiment'] == 0 || $_REQUEST['samples_experiment'] < $_REQUEST['conversions_experiment'] || (isset($_REQUEST['confidence']) && ($_REQUEST['confidence'] <= 0 || $_REQUEST['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}
		
		if(isset($_REQUEST['confidence'])){
			$improvement = imp_pct($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment'],$_REQUEST['confidence']);
		}
		else{
			$improvement = imp_pct($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment']);
		}
		
		$response['results'] = $improvement;
		$response['chart'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($improvement['low']*100+100)/2 .'|'. ($improvement['average']*100+100)/2 .'|'.($improvement['high']*100+100)/2;
		break;
	
	case 'complete';
		/* verify data: conversions=<samples for control and experiment */
		if($_REQUEST['conversions_control'] == 0 || $_REQUEST['samples_control'] == 0 || $_REQUEST['samples_control'] < $_REQUEST['conversions_control'] || $_REQUEST['conversions_experiment'] == 0 || $_REQUEST['samples_experiment'] == 0 || $_REQUEST['samples_experiment'] < $_REQUEST['conversions_experiment'] || (isset($_REQUEST['confidence']) && ($_REQUEST['confidence'] <= 0 || $_REQUEST['confidence'] >= 1))){
			$response['error'] = array('message' => 'Invalid data');
			break;
		}	
		
		/*confidence interval */
		if(isset($_REQUEST['confidence'])){
			$interval_control = interval($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['confidence']);
			$interval_experiment = interval($_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment'],$_REQUEST['confidence']);
		}
		else{
			$interval_control = interval($_REQUEST['conversions_control'],$_REQUEST['samples_control']);
			$interval_experiment = interval($_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment']);
		}

		$response['confidence']['results']['control'] = $interval_control;
		$response['confidence']['results']['experiment'] = $interval_experiment;
		$response['confidence']['chart']['control'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval_control['low']*100).'|'.($interval_control['average']*100).'|'.($interval_control['high']*100);
		$response['confidence']['chart']['experiment'] = 'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:'.($interval_experiment['low']*100).'|'.($interval_experiment['average']*100).'|'.($interval_experiment['high']*100);

		
		/*significance*/
		$significance = greater($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment']);
		
		$response['significance']['results'] = $significance;
		$response['significance']['chart'] = 'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:'.($significance['experiment']*100);
		
		/*improvement*/
		if(isset($_REQUEST['confidence'])){
			$improvement = imp_pct($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment'],$_REQUEST['confidence']);
		}
		else{
			$improvement = imp_pct($_REQUEST['conversions_control'],$_REQUEST['samples_control'],$_REQUEST['conversions_experiment'],$_REQUEST['samples_experiment']);
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