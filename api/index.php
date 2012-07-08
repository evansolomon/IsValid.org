<?php

include( dirname( __FILE__ ) . "/../stats.php");
$response = array();

function cleanup_ints( $value ) {
	if( is_int( str_replace( ",", "", $value ) ) )
		return (int) str_replace( ",", "", $value );

	return $value;
}
$parsed_request = array_map( 'cleanup_ints', $_REQUEST );

function error_debug_msg() {
	$backtrace = debug_backtrace();

	$file = pathinfo( $backtrace[0]['file'], PATHINFO_BASENAME );
	$line = $backtrace[0]['line'];

	return array(
		'file'   => $file,
		'line'   => $line,
		'source' => sprintf(
			'https://github.com/evansolomon/IsValid.org/blob/master/%s#L%d',
			$file,
			$line
		),
	);
}

switch( $parsed_request['function'] ) {

	case 'confidence';
		if( isset( $parsed_request['confidence'] ) )
			$interval = interval( $parsed_request['conversions'], $parsed_request['samples'], $parsed_request['confidence'] );
		else
			$interval = interval( $parsed_request['conversions'], $parsed_request['samples'] );

		if( !$interval ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		$response['results'] = $interval;
		$response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval['low'] * 100,
			$interval['average'] * 100,
			$interval['high'] * 100
		);
		break;

	case 'significance';
		$significance = greater( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'] );

		if( !$significance ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		$response['results'] = $significance;
		$response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:%f',
			$significance['experiment'] * 100
		);
		break;

	case 'improvement';

		if( isset( $parsed_request['confidence'] ) )
			$improvement = imp_pct( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'], $parsed_request['confidence'] );
		else
			$improvement = imp_pct( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'] );

		if( !$improvement ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		$response['results'] = $improvement;
		$response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			( $improvement['low'] * 100 + 100 ) / 2,
			( $improvement['average'] * 100 + 100 ) / 2,
			( $improvement['high'] * 100 + 100 ) / 2
		);
		break;

	case 'complete';
		/* confidence interval */
		if( isset( $parsed_request['confidence'] ) ){
			$interval_control = interval( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['confidence'] );
			$interval_experiment = interval( $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'], $parsed_request['confidence'] );
		}
		else{
			$interval_control = interval( $parsed_request['conversions_control'], $parsed_request['samples_control'] );
			$interval_experiment = interval( $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'] );
		}

		if( !$interval_control || !$interval_experiment ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		/* significance */
		$significance = greater( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'] );

		if( !$significance ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		/* improvement */
		if( isset( $parsed_request['confidence'] ) )
			$improvement = imp_pct( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'], $parsed_request['confidence'] );
		else
			$improvement = imp_pct( $parsed_request['conversions_control'], $parsed_request['samples_control'], $parsed_request['conversions_experiment'], $parsed_request['samples_experiment'] );

		if( !$improvement ) {
			$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
			break;
		}

		/* If we make it here, everything looks good */

		/* confidence interval */
		$response['confidence']['results']['control']    = $interval_control;
		$response['confidence']['results']['experiment'] = $interval_experiment;

		$response['confidence']['chart']['control'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval_control['low'] * 100,
			$interval_control['average'] * 100,
			$interval_control['high'] * 100
		);
		$response['confidence']['chart']['experiment'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval_experiment['low'] * 100,
			$interval_experiment['average'] * 100,
			$interval_experiment['high'] * 100
		);

		/* significance */
		$response['significance']['results'] = $significance;
		$response['significance']['chart'] = sprintf(
			'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:%f',
			$significance['experiment'] * 100
		);

		/* improvement */
		$response['improvement']['results'] = $improvement;
		$response['improvement']['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			( $improvement['low'] * 100 + 100 ) /2,
			( $improvement['average'] * 100 + 100 ) / 2,
			( $improvement['high'] * 100 + 100 ) / 2
		);

		break;



	default;
		$response['error'] = array( 'message' => 'Invalid data', 'debug' => error_debug_msg() );
		break;

}

echo json_encode($response);

?>