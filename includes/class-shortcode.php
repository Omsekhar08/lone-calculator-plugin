<?php
/**
 * Shortcode Class
 * Handles shortcode registration and rendering
 */

class Lone_Calculator_Shortcode {

	/**
	 * Initialize shortcode
	 */
	public static function init() {
		add_shortcode( 'lone_calculator', array( __CLASS__, 'render' ) );
	}

	/**
	 * Render calculator shortcode
	 *
	 * @param array $atts Shortcode attributes
	 * @return string HTML output
	 */
	public static function render( $atts = array() ) {
		// Enqueue assets
		wp_enqueue_style( 'lone-calculator-style' );
		wp_enqueue_script( 'lone-calculator-script' );

		// Localize script
		wp_localize_script(
			'lone-calculator-script',
			'loneCalculator',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'lone_calculator_nonce' ),
			)
		);

		ob_start();
		?>
		<section class="lone-calculator__wrapper">
			<div class="lone-calculator__container">
				<article class="lone-calculator__article" role="article">
					<div class="lone-calculator__holder" data-calculator-id="mortgage-calculator">
						<form name="calculatorForm" class="lone-calculator__form" novalidate>
							<!-- Form Fields Section -->
							<div class="lone-calculator__fieldset" data-section="inputs">
								<fieldset name="FormFields" class="lone-calculator__fieldset-wrapper">
									<div class="lone-calculator__fieldset-body">
										<!-- House Price Field -->
										<div class="lone-calculator__field-group">
											<div class="lone-calculator__field">
												<div class="lone-calculator__money-field-wrapper">
													<div class="lone-calculator__title-wrapper">
														<label for="HousePrice">House price</label>
													</div>
													<div class="lone-calculator__field-wrapper">
														<div class="lone-calculator__money-field">
															<span class="lone-calculator__currency-symbol">$</span>
															<input 
																id="HousePrice" 
																type="text" 
																name="HousePrice" 
																class="lone-calculator__text-input" 
																placeholder="Amount" 
																value=""
																data-field="house_price"
															>
														</div>
													</div>
												</div>
											</div>

											<!-- Deposit Field -->
											<div class="lone-calculator__field">
												<div class="lone-calculator__money-field-wrapper">
													<div class="lone-calculator__title-wrapper">
														<label for="Deposit">Deposit (enter $1 as minimum)</label>
													</div>
													<div class="lone-calculator__field-wrapper">
														<div class="lone-calculator__money-field">
															<span class="lone-calculator__currency-symbol">$</span>
															<input 
																id="Deposit" 
																type="text" 
																name="Deposit" 
																class="lone-calculator__text-input" 
																placeholder="Amount" 
																value=""
																data-field="deposit"
															>
														</div>
													</div>
												</div>
											</div>

											<!-- Loan Term Field -->
											<div class="lone-calculator__field">
												<div class="lone-calculator__title-wrapper">
													<label for="LoanTerm">Loan term</label>
												</div>
												<div class="lone-calculator__split-fields">
													<div class="lone-calculator__text--suffixed">
														<div class="lone-calculator__text-field">
															<div class="lone-calculator__text-fieldwrapper">
																<input 
																	id="LoanTerm" 
																	name="LoanTerm" 
																	type="text" 
																	placeholder="—" 
																	class="lone-calculator__text-input" 
																	value=""
																	data-field="loan_term_years"
																>
																<div class="lone-calculator__text-suffix">
																	<span class="lone-calculator__suffix-overlay">—</span>
																	<span>Years</span>
																</div>
															</div>
														</div>
													</div>

													<div class="lone-calculator__text--suffixed">
														<div class="lone-calculator__text-field">
															<div class="lone-calculator__text-fieldwrapper">
																<input 
																	id="LoanTermMonths" 
																	name="LoanTermMonths" 
																	type="text" 
																	placeholder="—" 
																	class="lone-calculator__text-input" 
																	value=""
																	data-field="loan_term_months"
																>
																<div class="lone-calculator__text-suffix">
																	<span class="lone-calculator__suffix-overlay">—</span>
																	<span>Months</span>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>

											<!-- Interest Rate Field -->
											<div class="lone-calculator__field lone-calculator__field--interest-rates">
												<div class="lone-calculator__text--suffixed">
													<div class="lone-calculator__title-wrapper">
														<label for="InterestRate">Interest rate</label>
													</div>
													<div class="lone-calculator__text-field">
														<div class="lone-calculator__text-fieldwrapper">
															<input 
																id="InterestRate" 
																name="InterestRate" 
																type="text" 
																placeholder=" — " 
																class="lone-calculator__text-input" 
																value=""
																data-field="interest_rate"
															>
															<div class="lone-calculator__text-suffix">
																<span class="lone-calculator__suffix-overlay"> — </span>
																<span>% p.a.</span>
															</div>
														</div>
													</div>
												</div>
											</div>

											<!-- Repayment Frequency Field -->
											<div class="lone-calculator__field">
												<div class="lone-calculator__title-wrapper">
													<label>Repayment frequency</label>
												</div>
												<div class="lone-calculator__segmented-control">
													<div class="lone-calculator__segment">
														<input 
															id="radio_RepaymentFrequency_fortnight" 
															type="radio" 
															name="RepaymentFrequency" 
															class="lone-calculator__radio" 
															value="fortnight"
															data-field="repayment_frequency"
														>
														<label class="lone-calculator__segment-label" for="radio_RepaymentFrequency_fortnight">
															<span>Fortnightly</span>
														</label>
													</div>
													<div class="lone-calculator__segment">
														<input 
															id="radio_RepaymentFrequency_month" 
															type="radio" 
															name="RepaymentFrequency" 
															class="lone-calculator__radio" 
															value="month"
															data-field="repayment_frequency"
															checked
														>
														<label class="lone-calculator__segment-label" for="radio_RepaymentFrequency_month">
															<span>Monthly</span>
														</label>
													</div>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
							</div>

							<!-- Results Section -->
							<div class="lone-calculator__results-section" data-section="results">
								<fieldset name="Results" class="lone-calculator__results-fieldset">
									<div class="lone-calculator__results-body">
										<div class="lone-calculator__results-sidebar">
											<h2 class="lone-calculator__results-title">Your results</h2>
											<div class="lone-calculator__results-content">
												<!-- Main Result Block -->
												<div class="lone-calculator__result-block">
													<div class="lone-calculator__result-label">
														Minimum <span class="lone-calculator__repayment-period">monthly</span> repayments
													</div>
													<p class="lone-calculator__hero-value">
														<span class="lone-calculator__currency-symbol">$</span>
														<span class="lone-calculator__hero-amount">0</span>
													</p>
													<div class="lone-calculator__comparison-label">
														See how much you could save:
													</div>
													<button type="button" class="lone-calculator__btn lone-calculator__btn--secondary lone-calculator__btn--fullwidth">
														<span>Compare price, deposit, term or rates</span>
													</button>
												</div>

												<!-- Amount Details -->
												<div class="lone-calculator__amounts-list">
													<p class="lone-calculator__amount-item">
														<span class="lone-calculator__amount-label">Loan amount</span>
														<span class="lone-calculator__amount-value">$0</span>
													</p>
													<p class="lone-calculator__amount-item">
														<span class="lone-calculator__amount-label">Total number of payments</span>
														<span class="lone-calculator__amount-value">0</span>
													</p>
													<p class="lone-calculator__amount-item">
														<span class="lone-calculator__amount-label">Total interest payable</span>
														<span class="lone-calculator__amount-value">$0</span>
													</p>
													<p class="lone-calculator__amount-item">
														<span class="lone-calculator__amount-label">Total amount payable</span>
														<span class="lone-calculator__amount-value">$0</span>
													</p>
												</div>

												<!-- Validation Message -->
												<div class="lone-calculator__validation-message">
													<div class="lone-calculator__message-status lone-calculator__message-status--visible">
														<div class="lone-calculator__message-wrapper">
															<div class="lone-calculator__message-content">
																Please enter a house price, a deposit, a loan term and an interest rate
															</div>
														</div>
													</div>
												</div>

												<!-- Footer -->
												<div class="lone-calculator__results-footer">
													<button type="button" class="lone-calculator__btn lone-calculator__btn--primary lone-calculator__btn--fullwidth">
														<span>Get in touch</span>
													</button>
												</div>
											</div>
										</div>
									</div>
								</fieldset>
							</div>
						</form>
					</div>
				</article>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
