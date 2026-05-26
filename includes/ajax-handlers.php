<?php
/**
 * AJAX Handlers
 * Processes calculator requests via AJAX
 */

/**
 * Handle calculator calculation AJAX request
 */
function lone_calculator_calculate_handler() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lone_calculator_nonce' ) ) {
		wp_send_json_error( array(
			'message' => 'Security check failed',
		) );
	}

	// Get and sanitize input data
	$data = array(
		'house_price'       => isset( $_POST['house_price'] ) ? floatval( $_POST['house_price'] ) : 0,
		'deposit'           => isset( $_POST['deposit'] ) ? floatval( $_POST['deposit'] ) : 0,
		'loan_term_years'   => isset( $_POST['loan_term_years'] ) ? intval( $_POST['loan_term_years'] ) : 0,
		'loan_term_months'  => isset( $_POST['loan_term_months'] ) ? intval( $_POST['loan_term_months'] ) : 0,
		'interest_rate'     => isset( $_POST['interest_rate'] ) ? floatval( $_POST['interest_rate'] ) : 0,
		'repayment_frequency' => isset( $_POST['repayment_frequency'] ) ? sanitize_text_field( $_POST['repayment_frequency'] ) : 'month',
	);

	// Perform calculation
	$result = Lone_Calculator::calculate( $data );

	if ( $result['success'] ) {
		wp_send_json_success( $result );
	} else {
		wp_send_json_error( array(
			'message' => isset( $result['error'] ) ? $result['error'] : 'Calculation failed',
		) );
	}
}
add_action( 'wp_ajax_lone_calculator_calculate', 'lone_calculator_calculate_handler' );
add_action( 'wp_ajax_nopriv_lone_calculator_calculate', 'lone_calculator_calculate_handler' );

/**
 * Handle interest rate retrieval
 */
function lone_calculator_get_rates_handler() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'lone_calculator_nonce' ) ) {
		wp_send_json_error( array(
			'message' => 'Security check failed',
		) );
	}

	// Get rates from option (these can be updated via admin panel)
	$rates = get_option( 'lone_calculator_rates', lone_calculator_get_default_rates() );

	wp_send_json_success( array(
		'rates' => $rates,
	) );
}
add_action( 'wp_ajax_lone_calculator_get_rates', 'lone_calculator_get_rates_handler' );
add_action( 'wp_ajax_nopriv_lone_calculator_get_rates', 'lone_calculator_get_rates_handler' );

/**
 * Get default interest rates
 */
function lone_calculator_get_default_rates() {
	return array(
		'fixed' => array(
			array( 'rate' => '5.29%', 'term' => '6 months' ),
			array( 'rate' => '4.69%', 'term' => '1 year', 'special' => true ),
			array( 'rate' => '5.29%', 'term' => '1 year' ),
			array( 'rate' => '5.59%', 'term' => '18 months' ),
			array( 'rate' => '5.19%', 'term' => '2 years', 'special' => true ),
			array( 'rate' => '5.79%', 'term' => '2 years' ),
			array( 'rate' => '5.35%', 'term' => '3 years', 'special' => true ),
			array( 'rate' => '5.95%', 'term' => '3 years' ),
			array( 'rate' => '5.39%', 'term' => '4 years', 'special' => true ),
			array( 'rate' => '5.99%', 'term' => '4 years' ),
			array( 'rate' => '5.59%', 'term' => '5 years', 'special' => true ),
			array( 'rate' => '6.19%', 'term' => '5 years' ),
			array( 'rate' => '4.69%', 'term' => '6 months', 'special' => true ),
			array( 'rate' => '4.99%', 'term' => '18 months', 'special' => true ),
		),
		'floating' => array(
			array( 'rate' => '5.89%', 'label' => 'Residential Base Rate' ),
			array( 'rate' => '5.99%', 'label' => 'Transactional Base Rate' ),
			array( 'rate' => '5.89%', 'label' => 'Housing Base Rate' ),
			array( 'rate' => '5.99%', 'label' => 'Choices Everyday Floating' ),
			array( 'rate' => '5.89%', 'label' => 'Choices Floating' ),
			array( 'rate' => '5.89%', 'label' => 'Choices Floating with Offset' ),
		),
	);
}
