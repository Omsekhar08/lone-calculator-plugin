# Quick Start Guide - Lone Mortgage Calculator

## 🚀 5-Minute Setup

### Step 1: Plugin Already Installed ✅
The plugin is located at: `wp-content/plugins/lone-calculator/`

### Step 2: Activate the Plugin
1. Login to WordPress Admin Dashboard
2. Go to **Plugins** menu
3. Find **Lone Mortgage Calculator**
4. Click **Activate**

### Step 3: Add to Your Page
1. Go to **Pages** → **Add New** (or edit existing)
2. Add this shortcode where you want the calculator:
   ```
   [lone_calculator]
   ```
3. Click **Publish** or **Update**

### Step 4: View Your Calculator
Visit the page in your browser. The calculator should now be visible!

---

## 📋 What The Calculator Does

Users can input:
- **House Price** - Total property cost
- **Deposit** - Down payment amount
- **Loan Term** - Duration (years + months)
- **Interest Rate** - Annual percentage rate
- **Repayment Frequency** - Monthly or Fortnightly

The calculator instantly shows:
- **Minimum Repayment** - Regular payment amount
- **Loan Amount** - Amount borrowed
- **Total Payments** - Number of payments
- **Total Interest** - Interest charges
- **Total Amount Payable** - Complete cost

---

## 🎨 Customization

### Change Colors
Edit: `wp-content/plugins/lone-calculator/assets/css/calculator.css`

Look for:
```css
:root {
    --lone-calc-primary-color: #001a4d;      /* Dark blue - change here */
    --lone-calc-secondary-color: #f0f0f0;    /* Light gray */
    --lone-calc-text-color: #333;            /* Dark text */
}
```

### Add Interest Rates
WordPress Admin → **Calculator** → **Rates Tab**
- Add/edit/remove available interest rates
- Rates appear in the rates modal

### Change Button Text
Edit: `wp-content/plugins/lone-calculator/includes/class-shortcode.php`

---

## 🔧 Troubleshooting

### "Calculator not showing"
- ✓ Check shortcode: `[lone_calculator]`
- ✓ Plugin is activated
- ✓ You're viewing page (not editor preview)
- ✓ Clear browser cache (Ctrl+Shift+Delete)

### "Not calculating"
- ✓ All fields have numbers
- ✓ Interest rate is like "5.5" not "0.055"
- ✓ Browser console has no errors (F12)
- ✓ JavaScript is enabled

### "Styling looks wrong"
- ✓ Clear cache (Shift+refresh in browser)
- ✓ Check no other plugins conflict
- ✓ CSS file is loading (check Network tab in F12)

---

## 📁 Plugin Files Explained

```
Calculator Home: wp-content/plugins/lone-calculator/

┌─ lone-calculator.php           ← Main plugin file
│
├─ includes/                     ← Logic files
│  ├─ class-calculator.php       ← Math calculations
│  ├─ class-shortcode.php        ← Calculator display
│  ├─ class-admin.php            ← Admin panel
│  └─ ajax-handlers.php          ← Server requests
│
├─ assets/                       ← User-facing files
│  ├─ css/calculator.css         ← Styling
│  └─ js/calculator.js           ← Interactions
│
├─ README.md                     ← Full documentation
├─ SETUP.md                      ← Technical guide
└─ QUICKSTART.md                 ← This file!
```

---

## 🎯 Common Tasks

### Add Calculator to Homepage
1. Edit your homepage
2. Add shortcode: `[lone_calculator]`
3. Save

### Add to Sidebar/Widget
1. Go to **Appearance** → **Widgets**
2. Add **Custom HTML** widget
3. Paste: `[lone_calculator]`
4. Save

### Add to Multiple Pages
Just add the shortcode `[lone_calculator]` to any page or post!

### Customize Interest Rates
1. WordPress Admin
2. Left menu: **Calculator**
3. Click **Rates** tab
4. Edit rates and click **Save Rates**

---

## 📞 Support

### Getting Help
Check these files:
- `README.md` - Full documentation
- `SETUP.md` - Technical details
- Admin Panel → Calculator → Help tab

### Common Issues

| Issue | Solution |
|-------|----------|
| Not showing | Check plugin activated, shortcode correct |
| Not calculating | All fields filled, clear cache |
| Wrong styling | Clear browser cache, check CSS loads |
| Admin errors | Verify WordPress version 5.0+, PHP 7.4+ |

---

## ✨ Features

✅ **Real-time Calculations** - Results update as you type
✅ **Mobile Responsive** - Works on all devices
✅ **Professional Design** - Modern, clean interface
✅ **Input Validation** - Prevents errors
✅ **Admin Settings** - Easy rate management
✅ **Security** - Built-in protections
✅ **Performance** - Fast and efficient
✅ **No Dependencies** - Works standalone

---

## 🔐 Security

The calculator includes:
- ✅ WordPress nonce protection
- ✅ Input sanitization
- ✅ Output escaping
- ✅ Admin capability checks
- ✅ AJAX security verification

---

## 💡 Pro Tips

1. **Test It** - Try different values to ensure calculations are correct
2. **Mobile Test** - Check how it looks on phone/tablet
3. **Update Rates** - Keep interest rates current in admin panel
4. **Link Contact** - Admin panel can link "Get in Touch" button to your contact page
5. **Backup** - Keep backup of settings before major WordPress updates

---

## 📊 Example Calculation

**Input:**
- House: $400,000
- Deposit: $80,000
- Term: 25 years
- Rate: 5.5% p.a.
- Frequency: Monthly

**Output:**
- Loan: $320,000
- Payment: ~$1,880.29/month
- Payments: 300
- Interest: ~$244,087
- Total: ~$564,087

---

## 🎓 Learn More

- Full technical docs: Read `SETUP.md`
- Development docs: Read `README.md`
- Admin help: See Calculator → Help tab in WordPress

---

**That's it!** Your mortgage calculator is ready to use! 🎉

For questions or issues, refer to the documentation files or check the admin panel help section.
