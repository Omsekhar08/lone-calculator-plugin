# Lone Mortgage Calculator

A comprehensive mortgage calculator WordPress plugin with a professional UI for calculating loan repayments.

## Features

- **House Price Input** - Enter the total property price
- **Deposit Amount** - Specify the deposit (minimum $1)
- **Loan Term** - Set loan duration in years and months
- **Interest Rate** - Enter the annual interest rate
- **Repayment Frequency** - Choose between monthly or fortnightly payments
- **Real-time Calculations** - Instant calculation results as you type
- **Comprehensive Results** - View:
  - Minimum repayment amount
  - Total loan amount
  - Total number of payments
  - Total interest payable
  - Total amount payable
- **Responsive Design** - Works perfectly on desktop and mobile devices
- **Pre-loaded Interest Rates** - Quick access to current rates

## Installation

1. Download the plugin files
2. Upload to `/wp-content/plugins/lone-calculator/`
3. Activate the plugin in WordPress admin
4. Use the shortcode `[lone_calculator]` on any page or post

## Usage

### Basic Shortcode
```php
[lone_calculator]
```

### Add to Template
```php
echo do_shortcode('[lone_calculator]');
```

## Plugin Structure

```
lone-calculator/
├── lone-calculator.php          # Main plugin file
├── includes/
│   ├── class-calculator.php     # Calculation logic
│   ├── class-shortcode.php      # Shortcode rendering
│   └── ajax-handlers.php        # AJAX request handlers
├── assets/
│   ├── css/
│   │   └── calculator.css       # Styling
│   └── js/
│       └── calculator.js        # Frontend JavaScript
└── README.md                    # This file
```

## Calculation Method

The calculator uses standard mortgage calculation formulas:

### Monthly Repayment Formula
```
P = L[c(1 + c)^n]/[(1 + c)^n - 1]
```

Where:
- P = Monthly Payment
- L = Loan Amount
- c = Monthly Interest Rate (Annual Rate / 100 / 12)
- n = Total Number of Payments (Years × 12 + Months)

### Fortnightly Repayment Formula
```
P = L[c(1 + c)^n]/[(1 + c)^n - 1]
```

Where:
- c = Fortnightly Interest Rate (Annual Rate / 100 / 26)
- n = Total Number of Fortnightly Payments (Years × 26 + Months / 12 × 26)

## AJAX Endpoints

### Calculate Endpoint
**Action:** `lone_calculator_calculate`
**Method:** POST

Parameters:
- `house_price` (float) - Property price
- `deposit` (float) - Deposit amount
- `loan_term_years` (int) - Loan years
- `loan_term_months` (int) - Additional months
- `interest_rate` (float) - Annual interest rate
- `repayment_frequency` (string) - 'month' or 'fortnight'

Response:
```json
{
  "success": true,
  "minimum_repayment": "5000.00",
  "payment_frequency": "Monthly",
  "loan_amount": "400000.00",
  "total_payments": 360,
  "total_interest_payable": "300000.00",
  "total_amount_payable": "700000.00"
}
```

## Customization

### Styling
Edit `assets/css/calculator.css` to customize colors and styling. Main color variables:

```css
--lone-calc-primary-color: #001a4d;
--lone-calc-secondary-color: #f0f0f0;
--lone-calc-text-color: #333;
--lone-calc-border-color: #ddd;
```

### Interest Rates
Default rates are stored in the database. To update programmatically:

```php
update_option('lone_calculator_rates', array(
    'fixed' => array(...),
    'floating' => array(...)
));
```

### Validation
Customize validation in `Lone_Calculator::validate_input()` method.

## Settings

The plugin can be configured via the database options:

- `lone_calculator_rates` - Available interest rates

## Troubleshooting

### Calculator not calculating
- Check browser console for JavaScript errors
- Verify AJAX URL is correct
- Ensure nonce is being passed

### Input values not formatting
- Clear browser cache
- Check JavaScript isn't conflicting with other plugins
- Verify jQuery is loaded

### Results not displaying
- Check that all required fields have values
- Verify interest rate is a valid number
- Check for JavaScript console errors

## Compatibility

- WordPress 5.0+
- PHP 7.4+
- jQuery 1.7+

## Support

For issues or feature requests, please contact support.

## License

GPL2 License - See plugin header for details
