<?php

class API_Request {
	private $response       = array();
	private $request        = null;
	private $parsed_request = null;

	/**
	 * If $request is specified it should be an array with correctly named keys
	 *
	 * cc = conversions (control)
	 * sc = saples (control)
	 * ce = converions (experiment)
	 * se = samples (experiment)
	 */
	function __construct( $request = false ) {
		include( dirname( __FILE__ ) . "/stats.php");

		$this->request = ( $request ) ? $request : $_REQUEST;
		$this->parse_request();
		if ( $this->validate_request() )
			$this->process_request();

		$this->respond();
	}

	private function parse_request() {
		$this->parsed_request = array_map( array( $this, 'cleanup_ints' ), $this->request );
		return $this->parsed_request;
	}

	private function cleanup_ints( $value ) {
		if ( is_numeric( str_replace( ",", "", $value ) ) && ( $value != (int) str_replace( ",", "", $value ) ) )
			return (int) str_replace( ",", "", $value );

		return $value;
	}

	private function validate_request() {
		if ( ! is_array( $this->parsed_request ) )
			return ! $this->respond_with_error( 'Your parsed request was not an array.' );

		// Default to 'complete' function if one isn't specified
		if( ! isset( $this->parsed_request['function'] ) )
			$this->parsed_request['function'] = 'complete';

		switch ( $this->parsed_request['function'] ):
			case 'confidence':
				$required_args = array( 'conversions', 'samples' );
				break;

			case 'significance':
			case 'improvement':
			case 'complete':
				$required_args = array( 'conversions_control', 'samples_control', 'conversions_experiment', 'samples_experiment' );
				break;

			default:
				return ! $this->respond_with_error( "Your request specified an invalid function name." );
		endswitch;

		foreach ( $required_args as $arg ) {
			if ( ! isset( $this->parsed_request[$arg] ) )
				return ! $this->respond_with_error( "You didn't include the required arguments for this function: " . $this->parsed_request['function'] );
		}

		return true;
	}

	public function process_request() {
		switch ( $this->parsed_request['function'] ):
			case 'confidence':
				$this->confidence();
				break;
			case 'significance':
				$this->significance();
				break;
			case 'improvement':
				$this->improvement();
				break;
			case 'complete':
				$this->complete();
				break;
			default:
				$this->respond_with_error( 'You requested an non-existent function.' );
				break;
		endswitch;
	}

	private function respond_with_error( $message ) {
		$this->response['error'] = array( 'message' => $message, 'debug' => $this->error_debug_msg() );

		return isset( $this->response['error'] );
	}

	private function error_debug_msg() {
		$backtrace = debug_backtrace();

		$file = $_SERVER['PHP_SELF'];
		$line = $backtrace[1]['line'];

		return array(
			'file'   => $file,
			'line'   => $line,
			'source' => sprintf(
				'https://github.com/evansolomon/IsValid.org/blob/master%s#L%d',
				$file,
				$line
			),
		);

		$this->respond();
		die();
	}

	private function respond() {
		header( 'Content-type: application/json' );
		echo json_encode( $this->response );
	}

	private function get_params( $args = array(), $default = false ) {
		$params = array();

		foreach ( $args as $arg )
			$params[$arg] = ( isset( $this->parsed_request[$arg] ) ) ? $this->parsed_request[$arg] : $default;

		return $params;
	}

	private function confidence() {
		$params = $this->get_params( array( 'conversions', 'samples', 'confidence' ) );
		$interval = interval( $params['conversions'], $params['samples'], $params['confidence'] );

		if ( ! $interval ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		$this->response['results'] = $interval;
		$this->response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval['low'] * 100,
			$interval['average'] * 100,
			$interval['high'] * 100
		);
	}

	private function significance() {
		$params = $this->get_params( array( 'conversions_control', 'samples_control', 'conversions_experiment', 'samples_experiment' ) );
		$significance = greater(
			$params['conversions_control'],
			$params['samples_control'],
			$params['conversions_experiment'],
			$params['samples_experiment']
		);

		if ( ! $significance ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		$this->response['results'] = $significance;
		$this->response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:%f',
			$significance['experiment'] * 100
		);
	}

	function improvement() {
		$params = $this->get_params( array( 'conversions_control', 'samples_control', 'conversions_experiment', 'samples_experiment', 'confidence' ) );
		$improvement = imp_pct(
			$params['conversions_control'],
			$params['samples_control'],
			$params['conversions_experiment'],
			$params['samples_experiment'],
			$params['confidence']
		);

		if ( ! $improvement ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		$this->response['results'] = $improvement;
		$this->response['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			( $improvement['low'] * 100 + 100 ) / 2,
			( $improvement['average'] * 100 + 100 ) / 2,
			( $improvement['high'] * 100 + 100 ) / 2
		);
	}

	function complete() {
		$params = $this->get_params( array( 'conversions_control', 'samples_control', 'conversions_experiment', 'samples_experiment', 'confidence' ) );

		/* confidence interval */
		$interval_control    = interval( $params['conversions_control'], $params['samples_control'], $params['confidence'] );
		$interval_experiment = interval( $params['conversions_experiment'], $params['samples_experiment'], $params['confidence'] );

		if ( ! $interval_control || ! $interval_experiment ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		/* significance */
		$significance = greater(
			$params['conversions_control'],
			$params['samples_control'],
			$params['conversions_experiment'],
			$params['samples_experiment']
		);

		if ( ! $significance ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		/* improvement */
		$improvement = imp_pct(
			$params['conversions_control'],
			$params['samples_control'],
			$params['conversions_experiment'],
			$params['samples_experiment'],
			$params['confidence']
		);

		if ( ! $improvement ) {
			$this->respond_with_error( "Your results couldn't be calculated, make sure you didn't mix up samples and conversions." );
			return;
		}

		/* If we make it here, everything looks good */

		/* confidence interval */
		$this->response['confidence']['results']['control']    = $interval_control;
		$this->response['confidence']['results']['experiment'] = $interval_experiment;

		$this->response['confidence']['chart']['control'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval_control['low'] * 100,
			$interval_control['average'] * 100,
			$interval_control['high'] * 100
		);
		$this->response['confidence']['chart']['experiment'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			$interval_experiment['low'] * 100,
			$interval_experiment['average'] * 100,
			$interval_experiment['high'] * 100
		);

		/* significance */
		$this->response['significance']['results'] = $significance;
		$this->response['significance']['chart'] = sprintf(
			'http://chart.apis.google.com/chart?chxl=0:|&chxt=y&chs=500x250&chls=2|0&cht=gm&chd=t:%f',
			$significance['experiment'] * 100
		);

		/* improvement */
		$this->response['improvement']['results'] = $improvement;
		$this->response['improvement']['chart'] = sprintf(
			'http://chart.apis.google.com/chart?&chxl=0:||1:||&chxt=x,y&chs=500x250&chls=1,5,5|1|1,5,5|0|0|0&cht=gm&chd=t:%f|%f|%f',
			( $improvement['low'] * 100 + 100 ) /2,
			( $improvement['average'] * 100 + 100 ) / 2,
			( $improvement['high'] * 100 + 100 ) / 2
		);
	}

}

new API_Request;
