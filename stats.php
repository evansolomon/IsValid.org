<?php

/**
 * Based on http://stackoverflow.com/questions/457408/is-there-an-easily-available-implementation-of-erf-for-python
*/
function erf( $x ) {
	$sign = ( $x < 0 ) ? -1 : 1;
  $x = abs( $x );

	//constants
	$a1 =  0.254829592;
	$a2 = -0.284496736;
	$a3 =  1.421413741;
	$a4 = -1.453152027;
	$a5 =  1.061405429;
	$p  =  0.3275911;

	//A&S formula 7.1.26
	$t = 1 / ( 1 + ( $p * $x ) );
	$y = 1 - ( ( ( ( ( $a5 * $t + $a4 ) *$t ) + $a3 ) *$t + $a2 ) *$t + $a1 ) * $t * exp( $x * -$x );

  return $sign * $y;
}

function erfinv( $x ) {
	$sign = ( $x < 0 ) ? -1 : 1;
	$x = $x * $sign;

	$low  = 0;
	$high = 0;

	while( erf( $high ) < $x ) {
		$low = $high;
		$high += 8;
	}

	$low = array( $low, erf( $low ) );
	$high = array( $high, erf( $high ) );

	while( ( $high[0] - $low[0] ) > 0.0001 ) {
		$test = $low[0] + ( $high[0] - $low[0] ) / 2;
		$test = array( $test, erf( $test ) );

		if( $test[1] < $x )
			$low = $test;
		else
			$high = $test;
	}

	return $sign * $high[0];
}

function sigma( $mu, $conversions, $samples ) {
	return sqrt( ( ( pow( 1 - $mu, 2 ) ) * $conversions + pow( $mu, 2 ) * ( $samples - $conversions ) ) / $samples );
}

//get confidence interval for conversion rate
function interval( $conversions, $samples, $confidence = 0.999 ) {
	$mu = $conversions / $samples;
	$sigma = sigma( $mu, $conversions, $samples );
	$z = erfinv( $confidence ) * sqrt( 2 );

	$r1 = $mu - $sigma * $z / sqrt( $samples );
	$r2 = $mu + $sigma * $z / sqrt( $samples );

	return array( 'low' => $r1, 'average' => ( $conversions / $samples ), 'high' => $r2 );
}

//calculates chance that other is best
function greater( $conversions_control, $samples_control, $conversions_experiment, $samples_experiment ) {
	$mu_control = $conversions_control / $samples_control;
	$mu_experiment = $conversions_experiment / $samples_experiment;
	$mu = $mu_control - $mu_experiment;

	$sigma_control = sigma( $mu_control, $conversions_control, $samples_control );
	$sigma_experiment = sigma( $mu_experiment, $conversions_experiment, $samples_experiment );
	$sigma_sq = pow( $sigma_control, 2 ) / $samples_control + pow( $sigma_experiment, 2 ) / $samples_experiment;

	$p = ( 1 + erf( -$mu / sqrt( 2 * $sigma_sq ) ) ) / 2;
	return array( 'control' => 1 - $p, 'experiment' => $p );
}

//calculates confidence interval for the effective size
function improvement( $conversions_control, $samples_control, $conversions_experiment, $samples_experiment, $confidence = .8 ) {
	$mu_experiment = $conversions_experiment / $samples_experiment;
	$mu_control = $conversions_control / $samples_control;
	$mu = $mu_experiment - $mu_control;

	$sigma_experiment = sigma($mu_experiment,$conversions_experiment,$samples_experiment);
	$sigma_control = sigma($mu_control,$conversions_control,$samples_control);
	$sigma_sq = pow( $sigma_experiment, 2 ) / $samples_experiment + pow( $sigma_control, 2 ) / $samples_control;

	$z = erfinv( $confidence ) * sqrt( 2 );
	return array( ( $mu - sqrt( $sigma_sq ) * $z ), ( $mu + sqrt( $sigma_sq ) * $z ) );
}

//calculates the confidence interval for the improvement of test over control
function imp_pct( $conversions_control, $samples_control, $conversions_experiment, $samples_experiment, $confidence = .8 ) {
	$out = array();
	$mu_control = $conversions_control / $samples_control;
	$imp = improvement( $conversions_control, $samples_control, $conversions_experiment, $samples_experiment, $confidence );
	
	for( $i=0; $i < count( $imp ); $i++ ) {
		$out[] = $imp[$i] / $mu_control;
	}
	return array('low' => $out[0], 'average' => ( $out[0] + $out[1] ) / 2, 'high' => $out[1] );
}