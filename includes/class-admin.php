<?php
/**
 * Admin Settings Class
 * Handles plugin settings and admin pages
 */

class Lone_Calculator_Admin {

	/**
	 * Initialize admin
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Add admin menu
	 */
	public static function add_admin_menu() {
		add_menu_page(
			'Mortgage Calculator',
			'Calculator',
			'manage_options',
			'lone-calculator',
			array( __CLASS__, 'render_settings_page' ),
			'dashicons-calculator',
			65
		);
	}

	/**
	 * Register settings
	 */
	public static function register_settings() {
		register_setting( 'lone_calculator_settings', 'lone_calculator_rates' );
	}

	/**
	 * Render settings page
	 */
	public static function render_settings_page() {
		?>
		<div class="wrap">
			<h1>Mortgage Calculator Settings</h1>
			
			<nav class="nav-tab-wrapper">
				<a href="?page=lone-calculator&tab=general" class="nav-tab <?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'general' ? 'nav-tab-active' : ''; ?>">General</a>
				<a href="?page=lone-calculator&tab=rates" class="nav-tab <?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'rates' ? 'nav-tab-active' : ''; ?>">Interest Rates</a>
				<a href="?page=lone-calculator&tab=help" class="nav-tab <?php echo isset( $_GET['tab'] ) && $_GET['tab'] === 'help' ? 'nav-tab-active' : ''; ?>">Help</a>
			</nav>

			<div class="tab-content">
				<?php
				$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
				
				switch ( $tab ) {
					case 'rates':
						self::render_rates_tab();
						break;
					case 'help':
						self::render_help_tab();
						break;
					default:
						self::render_general_tab();
				}
				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Render general settings tab
	 */
	public static function render_general_tab() {
		?>
		<form method="post" action="options.php" class="lone-calculator-form">
			<?php settings_fields( 'lone_calculator_settings' ); ?>
			
			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="calculator_title">Calculator Title</label>
					</th>
					<td>
						<input type="text" id="calculator_title" name="lone_calculator_title" value="Mortgage Calculator" class="regular-text">
						<p class="description">Title displayed at the top of the calculator</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="show_rates_button">Show Rates Button</label>
					</th>
					<td>
						<input type="checkbox" id="show_rates_button" name="lone_calculator_show_rates" value="1" checked>
						<label for="show_rates_button">Display button to show available interest rates</label>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="contact_link">Contact Page URL</label>
					</th>
					<td>
						<input type="url" id="contact_link" name="lone_calculator_contact_url" class="regular-text">
						<p class="description">URL for "Get in touch" button (leave empty for #get-in-touch anchor)</p>
					</td>
				</tr>
			</table>

			<?php submit_button(); ?>
		</form>
		<?php
	}

	/**
	 * Render interest rates tab
	 */
	public static function render_rates_tab() {
		$rates = get_option( 'lone_calculator_rates', lone_calculator_get_default_rates() );
		?>
		<form method="post" action="<?php echo admin_url( 'admin-ajax.php' ); ?>" id="rates-form" class="lone-calculator-form">
			<?php wp_nonce_field( 'lone_calculator_rates_nonce' ); ?>
			<input type="hidden" name="action" value="lone_calculator_save_rates">

			<h3>Fixed Rate Products</h3>
			<table class="wp-list-table fixed striped">
				<thead>
					<tr>
						<th>Interest Rate</th>
						<th>Term</th>
						<th>Special Offer</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $rates['fixed'] as $index => $rate ) {
						?>
						<tr>
							<td>
								<input type="text" name="rates[fixed][<?php echo $index; ?>][rate]" value="<?php echo esc_attr( $rate['rate'] ); ?>" placeholder="5.29%">
							</td>
							<td>
								<input type="text" name="rates[fixed][<?php echo $index; ?>][term]" value="<?php echo esc_attr( $rate['term'] ); ?>" placeholder="6 months">
							</td>
							<td>
								<input type="checkbox" name="rates[fixed][<?php echo $index; ?>][special]" value="1" <?php checked( ! empty( $rate['special'] ) ); ?>>
							</td>
							<td>
								<button type="button" class="button button-small delete-rate">Delete</button>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>

			<h3>Floating Rate Products</h3>
			<table class="wp-list-table fixed striped">
				<thead>
					<tr>
						<th>Interest Rate</th>
						<th>Label</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ( $rates['floating'] as $index => $rate ) {
						?>
						<tr>
							<td>
								<input type="text" name="rates[floating][<?php echo $index; ?>][rate]" value="<?php echo esc_attr( $rate['rate'] ); ?>" placeholder="5.89%">
							</td>
							<td>
								<input type="text" name="rates[floating][<?php echo $index; ?>][label]" value="<?php echo esc_attr( $rate['label'] ?? '' ); ?>" placeholder="Rate label">
							</td>
							<td>
								<button type="button" class="button button-small delete-rate">Delete</button>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>

			<?php submit_button( 'Save Rates' ); ?>
		</form>
		<?php
	}

	/**
	 * Render help tab
	 */
	public static function render_help_tab() {
		?>
		<div class="lone-calculator-help">
			<h3>How to use the Mortgage Calculator</h3>
			
			<h4>Add Calculator to Page</h4>
			<p>Use the shortcode below on any page or post:</p>
			<code>[lone_calculator]</code>

			<h4>Calculator Fields</h4>
			<ul>
				<li><strong>House Price:</strong> Total property purchase price</li>
				<li><strong>Deposit:</strong> Initial payment amount (minimum $1)</li>
				<li><strong>Loan Term:</strong> Duration in years and/or months</li>
				<li><strong>Interest Rate:</strong> Annual interest rate (p.a.)</li>
				<li><strong>Repayment Frequency:</strong> Monthly or Fortnightly payments</li>
			</ul>

			<h4>Results Displayed</h4>
			<ul>
				<li><strong>Minimum Repayment:</strong> Regular payment amount</li>
				<li><strong>Loan Amount:</strong> House price minus deposit</li>
				<li><strong>Total Number of Payments:</strong> Based on loan term and frequency</li>
				<li><strong>Total Interest Payable:</strong> Interest accrued over loan term</li>
				<li><strong>Total Amount Payable:</strong> Loan amount plus total interest</li>
			</ul>

			<h4>Calculation Formula</h4>
			<p>The calculator uses the standard amortization formula:</p>
			<code>P = L[c(1 + c)^n]/[(1 + c)^n - 1]</code>
			<p>Where P is payment, L is loan amount, c is monthly/fortnightly rate, and n is number of payments.</p>

			<h4>Troubleshooting</h4>
			<ul>
				<li><strong>Not calculating:</strong> Ensure all fields are filled with valid numbers</li>
				<li><strong>Wrong results:</strong> Check that interest rate is entered as a percentage (e.g., 5.29 not 0.0529)</li>
				<li><strong>Display issues:</strong> Check browser console for errors</li>
			</ul>
		</div>
		<?php
	}
}

// Initialize admin
if ( is_admin() ) {
	Lone_Calculator_Admin::init();
}

// AJAX handler for saving rates
add_action( 'wp_ajax_lone_calculator_save_rates', 'lone_calculator_save_rates' );

function lone_calculator_save_rates() {
	// Verify nonce
	check_ajax_referer( 'lone_calculator_rates_nonce' );

	// Check capability
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'Unauthorized' );
	}

	// Save rates
	if ( isset( $_POST['rates'] ) ) {
		update_option( 'lone_calculator_rates', $_POST['rates'] );
		wp_send_json_success( 'Rates saved successfully' );
	}

	wp_send_json_error( 'No rates provided' );
}
