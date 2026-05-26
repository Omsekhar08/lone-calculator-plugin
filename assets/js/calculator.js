/**
 * Lone Calculator - Main JavaScript
 */

(function($) {
	'use strict';

	const LoneCalculator = {
		/**
		 * Initialize calculator
		 */
		init: function() {
			this.cacheElements();
			this.bindEvents();
		},

		/**
		 * Cache DOM elements
		 */
		cacheElements: function() {
			this.$form = $('form[name="calculatorForm"]');
			this.$holder = this.$form.closest('[data-calculator-id]');
			this.$inputs = {
				housePrice: this.$form.find('[data-field="house_price"]'),
				deposit: this.$form.find('[data-field="deposit"]'),
				loanTermYears: this.$form.find('[data-field="loan_term_years"]'),
				loanTermMonths: this.$form.find('[data-field="loan_term_months"]'),
				interestRate: this.$form.find('[data-field="interest_rate"]'),
				repaymentFrequency: this.$form.find('[data-field="repayment_frequency"]'),
			};
			this.$results = {
				repayment: this.$form.find('.lone-calculator__hero-amount'),
				repaymentPeriod: this.$form.find('.lone-calculator__repayment-period'),
				loanAmount: this.$form.find('.lone-calculator__amount-value').eq(0),
				totalPayments: this.$form.find('.lone-calculator__amount-value').eq(1),
				totalInterest: this.$form.find('.lone-calculator__amount-value').eq(2),
				totalPayable: this.$form.find('.lone-calculator__amount-value').eq(3),
			};
			this.$validationMessage = this.$form.find('.lone-calculator__validation-message');
			this.$compareBtn = this.$form.find('.lone-calculator__btn--secondary');
			this.$contactBtn = this.$form.find('.lone-calculator__btn--primary');
		},

		/**
		 * Bind events
		 */
		bindEvents: function() {
			const self = this;

			// Input changes
			this.$inputs.housePrice.on('input', function() {
				self.formatCurrencyInput($(this));
				self.calculate();
			});

			this.$inputs.deposit.on('input', function() {
				self.formatCurrencyInput($(this));
				self.calculate();
			});

			this.$inputs.loanTermYears.on('input', function() {
				self.formatNumberInput($(this));
				self.calculate();
			});

			this.$inputs.loanTermMonths.on('input', function() {
				self.formatNumberInput($(this));
				self.calculate();
			});

			this.$inputs.interestRate.on('input', function() {
				self.formatPercentageInput($(this));
				self.calculate();
			});

			this.$inputs.repaymentFrequency.on('change', function() {
				self.calculate();
			});

			// Button events
			this.$compareBtn.on('click', function(e) {
				e.preventDefault();
				self.handleCompare();
			});

			this.$contactBtn.on('click', function(e) {
				e.preventDefault();
				self.handleContact();
			});
		},

		/**
		 * Format currency input
		 */
		formatCurrencyInput: function($input) {
			let value = $input.val().replace(/[^\d.]/g, '');
			if (value) {
				$input.val(value);
			}
		},

		/**
		 * Format number input
		 */
		formatNumberInput: function($input) {
			let value = $input.val().replace(/[^\d]/g, '');
			$input.val(value);
		},

		/**
		 * Format percentage input
		 */
		formatPercentageInput: function($input) {
			let value = $input.val().replace(/[^\d.]/g, '');
			if (value && value.split('.').length > 2) {
				value = value.substring(0, value.length - 1);
			}
			$input.val(value);
		},

		/**
		 * Perform calculation
		 */
		calculate: function() {
			const self = this;
			const data = this.getFormData();

			// Check if we have minimum required data
			if (!this.hasRequiredData(data)) {
				this.showValidationMessage('Please enter a house price, a deposit, a loan term and an interest rate');
				this.clearResults();
				return;
			}

			// Show loading state
			this.showValidationMessage('Calculating...');

			// Send AJAX request
			$.ajax({
				type: 'POST',
				url: loneCalculator.ajaxUrl,
				data: {
					action: 'lone_calculator_calculate',
					nonce: loneCalculator.nonce,
					...data
				},
				success: function(response) {
					if (response.success) {
						self.displayResults(response.data);
						self.hideValidationMessage();
					} else {
						self.showValidationMessage(response.data.message || 'Calculation failed');
						self.clearResults();
					}
				},
				error: function() {
					self.showValidationMessage('An error occurred during calculation');
					self.clearResults();
				}
			});
		},

		/**
		 * Get form data
		 */
		getFormData: function() {
			return {
				house_price: this.$inputs.housePrice.val(),
				deposit: this.$inputs.deposit.val(),
				loan_term_years: this.$inputs.loanTermYears.val(),
				loan_term_months: this.$inputs.loanTermMonths.val(),
				interest_rate: this.$inputs.interestRate.val(),
				repayment_frequency: this.$inputs.repaymentFrequency.filter(':checked').val(),
			};
		},

		/**
		 * Check if required data is present
		 */
		hasRequiredData: function(data) {
			return data.house_price && 
				   data.deposit && 
				   (data.loan_term_years || data.loan_term_months) && 
				   data.interest_rate;
		},

		/**
		 * Display calculation results
		 */
		displayResults: function(results) {
			this.$results.repayment.text('$' + results.minimum_repayment);
			this.$results.repaymentPeriod.text(results.payment_frequency.toLowerCase());
			this.$results.loanAmount.text('$' + results.loan_amount);
			this.$results.totalPayments.text(results.total_payments);
			this.$results.totalInterest.text('$' + results.total_interest_payable);
			this.$results.totalPayable.text('$' + results.total_amount_payable);

			// Update hero value with animation
			this.$form.find('.lone-calculator__hero-value').addClass('animate');
		},

		/**
		 * Clear results
		 */
		clearResults: function() {
			this.$results.repayment.text('0');
			this.$results.repaymentPeriod.text('monthly');
			this.$results.loanAmount.text('$0');
			this.$results.totalPayments.text('0');
			this.$results.totalInterest.text('$0');
			this.$results.totalPayable.text('$0');
		},

		/**
		 * Show validation message
		 */
		showValidationMessage: function(message) {
			this.$validationMessage.find('.lone-calculator__message-content').text(message);
			this.$validationMessage.addClass('visible');
		},

		/**
		 * Hide validation message
		 */
		hideValidationMessage: function() {
			this.$validationMessage.removeClass('visible');
		},

		/**
		 * Handle compare button click
		 */
		handleCompare: function() {
			// Scroll to top or open comparison modal
			alert('Compare functionality - can be customized to open a modal or redirect');
		},

		/**
		 * Handle contact button click
		 */
		handleContact: function() {
			// Scroll to contact form or open modal
			const contactSection = $('#get-in-touch');
			if (contactSection.length) {
				$('html, body').animate({
					scrollTop: contactSection.offset().top - 100
				}, 800);
			} else {
				alert('Contact section not found. Please customize this action.');
			}
		}
	};

	/**
	 * Initialize on document ready
	 */
	$(document).ready(function() {
		LoneCalculator.init();
	});

})(jQuery);
