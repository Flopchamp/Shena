# 🎨 SHENA COMPANION - QUICK REFERENCE CARD

## 🎨 COLORS (Copy & Paste Ready)

```css
/* Primary Colors */
--primary-royal-purple: #5A0C6D;
--primary-deep-purple: #6A0DAD;
--secondary-violet: #3B0A45;
--accent-purple-glow: #8E2DE2;

/* Status Colors */
--success: #10B981;  /* Green */
--warning: #F59E0B;  /* Amber */
--error: #EF4444;    /* Red */
--info: #3B82F6;     /* Blue */

/* Neutrals */
--white: #FFFFFF;
--soft-grey: #F5F3F7;
--dark-text: #2E2E2E;
```

## 🔘 BUTTONS

```html
<!-- Primary Action -->
<button class="btn btn-primary">Primary Button</button>

<!-- Secondary Action -->
<button class="btn btn-secondary">Secondary Button</button>

<!-- Outline Style -->
<button class="btn btn-outline">Outline Button</button>

<!-- Status Buttons -->
<button class="btn btn-success">Success</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-danger">Danger</button>

<!-- Sizes -->
<button class="btn btn-primary btn-lg">Large</button>
<button class="btn btn-primary">Normal</button>
<button class="btn btn-primary btn-sm">Small</button>

<!-- With Icons -->
<button class="btn btn-primary">
    <i class="bi bi-plus-circle"></i> Add Member
</button>
```

## 📇 CARDS

```html
<!-- Basic Card -->
<div class="card">
    <div class="card-header">Title</div>
    <div class="card-body">Content</div>
    <div class="card-footer">Footer</div>
</div>

<!-- Royal Purple Card -->
<div class="card card-royal">
    <div class="card-header">Premium Title</div>
    <div class="card-body">White text on purple</div>
</div>

<!-- Stat Card -->
<div class="stat-card stat-primary">
    <div class="stat-card-value">1,234</div>
    <div class="stat-card-label">Label</div>
    <i class="bi bi-icon stat-card-icon"></i>
</div>

<!-- Stat Card Colors -->
.stat-primary  (Purple)
.stat-success  (Green)
.stat-warning  (Amber)
.stat-danger   (Red)
```

## 📝 FORMS

```html
<!-- Form Group -->
<div class="form-group">
    <label class="form-label" for="name">Full Name</label>
    <input type="text" id="name" class="form-control" placeholder="Enter name">
</div>

<!-- Validation States -->
<input class="form-control is-valid">
<input class="form-control is-invalid">
<div class="invalid-feedback">Error message</div>
<div class="valid-feedback">Success message</div>

<!-- Select Dropdown -->
<select class="form-select">
    <option>Choose option</option>
    <option value="1">Option 1</option>
</select>

<!-- Textarea -->
<textarea class="form-control" rows="4"></textarea>
```

## 📊 TABLES

```html
<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Column 1</th>
                <th>Column 2</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Data 1</td>
                <td>Data 2</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- Sortable Table -->
<table class="table table-sortable">
    <thead>
        <th data-sortable>Sortable Column</th>
    </thead>
</table>

<!-- With Search -->
<input type="text" 
       class="form-control" 
       placeholder="Search..." 
       data-table-search="#tableId">
```

## 🏷️ BADGES

```html
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Rejected</span>
<span class="badge badge-info">Info</span>
<span class="badge badge-primary">Primary</span>
<span class="badge badge-secondary">Secondary</span>
```

## 🚨 ALERTS

```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-warning">Warning message</div>
<div class="alert alert-danger">Error message</div>
<div class="alert alert-info">Info message</div>
```

## 📐 LAYOUTS

### Section Container
```html
<section class="section">
    <div class="container">
        <h2 class="section-title">Title</h2>
        <!-- Content -->
    </div>
</section>
```

### Stats Grid
```html
<div class="stats-grid">
    <div class="stat-card stat-primary">...</div>
    <div class="stat-card stat-success">...</div>
    <div class="stat-card stat-warning">...</div>
    <div class="stat-card stat-danger">...</div>
</div>
```

### Dashboard Layout
```html
<div class="dashboard-wrapper">
    <aside class="dashboard-sidebar">
        <!-- Sidebar content -->
    </aside>
    <main class="dashboard-main">
        <!-- Main content -->
    </main>
</div>
```

## 🎬 ANIMATIONS

```html
<!-- Fade in on scroll -->
<div data-animate>Content</div>

<!-- CSS Classes -->
<div class="fade-in">Fade in</div>
<div class="slide-in-left">Slide from left</div>
<div class="slide-in-right">Slide from right</div>
```

## 🛠️ UTILITIES

### Spacing
```html
<!-- Margin -->
.mt-1, .mt-2, .mt-3, .mt-4, .mt-5  (margin-top)
.mb-1, .mb-2, .mb-3, .mb-4, .mb-5  (margin-bottom)

<!-- Padding -->
.p-1, .p-2, .p-3, .p-4, .p-5

<!-- Gaps -->
.gap-1, .gap-2, .gap-3
```

### Flex
```html
<div class="d-flex justify-content-between align-items-center gap-2">
    <div>Left</div>
    <div>Right</div>
</div>
```

### Text
```html
.text-center, .text-left, .text-right
.text-purple, .text-white, .text-muted
```

### Background
```html
.bg-royal    (Purple gradient)
.bg-primary  (Primary gradient)
.bg-white
.bg-soft     (Soft grey)
```

### Borders & Shadows
```html
.rounded    (12px radius)
.rounded-lg (16px radius)
.shadow     (Medium shadow)
.shadow-lg  (Large shadow)
```

## 🖱️ JAVASCRIPT API

```javascript
// Toast Notification
ShenaCompanion.showToast('Message', 'success', 3000);
// Types: success, warning, danger, info

// Format Currency
ShenaCompanion.formatCurrency(1000); // "KES 1,000.00"

// Format Date
ShenaCompanion.formatDate('2026-01-29'); // "January 29, 2026"

// Loading Spinner
const btn = document.querySelector('button');
ShenaCompanion.showLoading(btn);
ShenaCompanion.hideLoading(btn, 'Original Text');

// Confirm Action
ShenaCompanion.confirmAction('Are you sure?', function() {
    // Do something
});

// Debounce
const debouncedFunc = ShenaCompanion.debounce(function() {
    // Function logic
}, 300);
```

## 🎨 HERO SECTION

```html
<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Main Title</h1>
        <p class="hero-subtitle">Subtitle text</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
            <a href="#" class="btn btn-primary btn-lg">CTA 1</a>
            <a href="#" class="btn btn-secondary btn-lg">CTA 2</a>
        </div>
    </div>
    <div class="curve-divider">
        <!-- SVG curve -->
    </div>
</section>
```

## 📱 RESPONSIVE BREAKPOINTS

```css
/* Mobile First */
/* Base styles apply to mobile */

/* Tablet */
@media (min-width: 768px) { }

/* Desktop */
@media (min-width: 1024px) { }

/* Large Desktop */
@media (min-width: 1440px) { }
```

## 🎯 ICONS

Using Bootstrap Icons:
```html
<i class="bi bi-house-fill"></i>
<i class="bi bi-person-fill"></i>
<i class="bi bi-gear-fill"></i>
<i class="bi bi-graph-up"></i>
<i class="bi bi-cash-stack"></i>
<i class="bi bi-file-earmark-text-fill"></i>
<i class="bi bi-envelope-fill"></i>
<i class="bi bi-telephone-fill"></i>
<i class="bi bi-geo-alt-fill"></i>
<i class="bi bi-check-circle-fill"></i>
<i class="bi bi-x-circle-fill"></i>
<i class="bi bi-exclamation-triangle-fill"></i>
```

Full icon list: https://icons.getbootstrap.com/

## 📄 PAGE TEMPLATES

### Public Page
```php
<?php
$pageTitle = 'Page Title';
$activePage = 'home';
include __DIR__ . '/layouts/public-header.php';
?>

<section class="hero">
    <!-- Hero content -->
</section>

<section class="section">
    <div class="container">
        <!-- Content -->
    </div>
</section>

<?php include __DIR__ . '/layouts/public-footer.php'; ?>
```

### Dashboard Page
```php
<?php
$pageTitle = 'Dashboard';
$pageSubtitle = 'Subtitle';
$activePage = 'dashboard';
$userName = 'User Name';
$userRole = 'Role';
include __DIR__ . '/../layouts/dashboard-header.php';
?>

<div class="stats-grid">
    <!-- Stat cards -->
</div>

<div class="card">
    <!-- Card content -->
</div>

<?php include __DIR__ . '/../layouts/dashboard-footer.php'; ?>
```

## 🎨 MODAL

```html
<div class="modal-overlay" id="myModal" style="display: none;">
    <div class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Modal Title</h3>
            <button data-modal-close>&times;</button>
        </div>
        <div class="modal-body">
            Modal content
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" data-modal-close">Cancel</button>
            <button class="btn btn-primary">Confirm</button>
        </div>
    </div>
</div>

<!-- Trigger -->
<button data-modal-open="#myModal">Open Modal</button>
```

## 💡 TOOLTIP

```html
<button data-tooltip="This is a tooltip">Hover Me</button>
```

## ⚡ PROGRESS BAR

```html
<div class="progress">
    <div class="progress-bar" style="width: 75%;"></div>
</div>
```

## 📋 DATA ATTRIBUTES

```html
<!-- Animations -->
data-animate

<!-- Navigation -->
data-nav-toggle
data-nav-menu
data-sidebar-toggle

<!-- Modal -->
data-modal-open="#modalId"
data-modal-close

<!-- Table -->
data-table-search="#tableId"
data-sortable

<!-- Tooltip -->
data-tooltip="Text"
```

---

## 🚀 QUICK COMMANDS

### Add New Page
1. Create PHP file in appropriate folder
2. Set page variables
3. Include header
4. Add content using components
5. Include footer

### Add New Component
1. Define HTML structure
2. Add CSS to style.css (if new)
3. Add JS functionality (if interactive)
4. Test responsiveness

### Debug
1. Check browser console (F12)
2. Inspect element styles
3. Verify file paths
4. Test form validation

---

**Keep this file open while coding for quick reference!** 📌
