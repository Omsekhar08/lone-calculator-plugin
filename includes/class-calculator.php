<?php
/**
 * Calculator Class
 * Handles all mortgage calculation logic
 */

class Lone_Calculator {

	/**
	 * Calculate monthly repayment
	 *
	 * @param float $principal The loan amount
	 * @param float $annual_rate The annual interest rate (as percentage)
	 * @param int   $total_payments Total number of payments
	 * @return float Monthly repayment amount
	 */
	public static function calculate_repayment( $principal, $annual_rate, $total_payments ) {
		if ( $annual_rate == 0 ) {
			return $principal / $total_payments;
		}

		$monthly_rate = ( $annual_rate / 100 ) / 12;
		$repayment     = $principal * ( $monthly_rate * pow( 1 + $monthly_rate, $total_payments ) ) / ( pow( 1 + $monthly_rate, $total_payments ) - 1 );

		return round( $repayment, 2 );
	}

	/**
	 * Calculate fortnightly repayment
	 *
	 * @param float $principal The loan amount
	 * @param float $annual_rate The annual interest rate (as percentage)
	 * @param int   $total_payments Total number of payments
	 * @return float Fortnightly repayment amount
	 */
	public static function calculate_fortnightly_repayment( $principal, $annual_rate, $total_payments ) {
		if ( $annual_rate == 0 ) {
			return $principal / $total_payments;
		}

		$fortnightly_rate = ( $annual_rate / 100 ) / 26;
		$repayment        = $principal * ( $fortnightly_rate * pow( 1 + $fortnightly_rate, $total_payments ) ) / ( pow( 1 + $fortnightly_rate, $total_payments ) - 1 );

		return round( $repayment, 2 );
	}

	/**
	 * Validate calculator input
	 *
	 * @param array $data Input data
	 * @return array|WP_Error Validation result
	 */
	public static function validate_input( $data ) {
		$errors = array();

		// Validate house price
		if ( empty( $data['house_price'] ) || floatval( $data['house_price'] ) <= 0 ) {
			$errors[] = 'House price must be greater than 0';
		}

		// Validate deposit
		if ( empty( $data['deposit'] ) || floatval( $data['deposit'] ) < 1 ) {
			$errors[] = 'Deposit must be at least $1';
		}

		// Validate loan term
		if ( empty( $data['loan_term_years'] ) || empty( $data['loan_term_months'] ) ) {
			if ( ( empty( $data['loan_term_years'] ) || intval( $data['loan_term_years'] ) <= 0 ) && 
				 ( empty( $data['loan_term_months'] ) || intval( $data['loan_term_months'] ) <= 0 ) ) {
				$errors[] = 'Loan term must be specified';
			}
		}

		// Validate interest rate
		if ( empty( $data['interest_rate'] ) || floatval( $data['interest_rate'] ) < 0 ) {
			$errors[] = 'Interest rate is required';
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error( 'validation_failed', implode( ', ', $errors ) );
		}

		return true;
	}

	/**
	 * Perform full calculation
	 *
	 * @param array $data Input data
	 * @return array Calculation results
	 */
	public static function calculate( $data ) {
		// Validate input
		$validation = self::validate_input( $data );
		if ( is_wp_error( $validation ) ) {
			return array(
				'success' => false,
				'error'   => $validation->get_error_message(),
			);
		}

		$house_price  = floatval( $data['house_price'] );
		$deposit      = floatval( $data['deposit'] );
		$loan_term_years = intval( $data['loan_term_years'] ) ?? 0;
		$loan_term_months = intval( $data['loan_term_months'] ) ?? 0;
		$interest_rate = floatval( $data['interest_rate'] );
		$repayment_frequency = sanitize_text_field( $data['repayment_frequency'] ) ?? 'month';

		// Calculate loan amount
		$loan_amount = $house_price - $deposit;

		if ( $loan_amount <= 0 ) {
			return array(
				'success' => false,
				'error'   => 'Loan amount must be greater than 0',
			);
		}

		// Calculate total payments
		$total_months = ( $loan_term_years * 12 ) + $loan_term_months;

		if ( $total_months <= 0 ) {
			return array(
				'success' => false,
				'error'   => 'Loan term must be greater than 0',
			);
		}

		// Calculate repayment
		if ( $repayment_frequency === 'fortnight' ) {
			$total_payments = ceil( ( $total_months / 12 ) * 26 );
			$repayment      = self::calculate_fortnightly_repayment( $loan_amount, $interest_rate, $total_payments );
			$payment_label  = 'Fortnightly';
		} else {
			$total_payments = $total_months;
			$repayment      = self::calculate_repayment( $loan_amount, $interest_rate, $total_payments );
			$payment_label  = 'Monthly';
		}

		// Calculate total interest payable
		$total_payable     = ( $repayment * $total_payments );
		$total_interest    = round( $total_payable - $loan_amount, 2 );

		return array(
			'success'                   => true,
			'minimum_repayment'         => number_format( $repayment, 2 ),
			'minimum_repayment_raw'     => $repayment,
			'payment_frequency'         => $payment_label,
			'loan_amount'               => number_format( $loan_amount, 2 ),
			'loan_amount_raw'           => $loan_amount,
			'total_payments'            => $total_payments,
			'total_interest_payable'    => number_format( $total_interest, 2 ),
			'total_interest_payable_raw' => $total_interest,
			'total_amount_payable'      => number_format( $total_payable, 2 ),
			'total_amount_payable_raw'  => $total_payable,
		);
	}
}
