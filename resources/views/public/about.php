<?php
$page = 'about';
$pageTitle = 'About Us';
include VIEWS_PATH . '/layouts/public-header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">About SHENA Companion</h1>
            <p class="hero-subtitle">
                Providing compassionate support and affordable funeral services to our community since establishment
            </p>
        </div>
    </div>
    
    <!-- Decorative wave -->
    <svg class="hero-wave" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
    </svg>
</section>

<!-- Mission, Vision, Values -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Our Foundation</h2>
            <p>The principles that guide our commitment to serving you</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <div class="card" style="text-align: center;">
                <div class="card-body">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-bullseye" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Our Mission</h3>
                    <p style="color: var(--medium-grey);">
                        To provide affordable, reliable, and compassionate welfare services that support our community members during times of bereavement, ensuring dignity and respect in their most challenging moments.
                    </p>
                </div>
            </div>
            
            <div class="card" style="text-align: center;">
                <div class="card-body">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-success); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-eye-fill" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Our Vision</h3>
                    <p style="color: var(--medium-grey);">
                        To be the leading welfare association in Kenya, recognized for excellence in funeral services, community support, and financial security for all our members and their families.
                    </p>
                </div>
            </div>
            
            <div class="card" style="text-align: center;">
                <div class="card-body">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-danger); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-heart-fill" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Our Values</h3>
                    <p style="color: var(--medium-grey);">
                        Compassion, integrity, reliability, and community solidarity guide everything we do. We believe in supporting each other through life's challenges with dignity and respect.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Story -->
<section class="section" style="background: var(--soft-grey);">
    <div class="container">
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="section-header">
                <h2>Our Story</h2>
            </div>
            
            <div class="card">
                <div class="card-body" style="padding: 2.5rem;">
                    <p style="font-size: 1.125rem; line-height: 1.8; color: var(--secondary-violet); margin-bottom: 1.5rem;">
                        <strong>SHENA Companion Welfare Association</strong> was founded with a simple yet powerful vision: to ensure that no family faces the financial burden of funeral expenses alone.
                    </p>
                    
                    <p style="line-height: 1.8; color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Recognizing the challenges that many families face during times of loss, our founders established this association to provide a reliable support system that combines affordability with comprehensive coverage.
                    </p>
                    
                    <p style="line-height: 1.8; color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Today, we serve hundreds of families across Kenya, providing not just financial support but also guidance, compassion, and peace of mind during difficult times. Our commitment to transparency, reliability, and community support has made us a trusted partner for families seeking security and dignity in their final arrangements.
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 2rem; margin-top: 2.5rem; padding-top: 2rem; border-top: 2px solid var(--light-grey);">
                        <div style="text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-purple); margin-bottom: 0.5rem;">500+</div>
                            <div style="color: var(--medium-grey); font-size: 0.875rem;">Active Members</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: var(--success-green); margin-bottom: 0.5rem;">24/7</div>
                            <div style="color: var(--medium-grey); font-size: 0.875rem;">Support Available</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: var(--accent-glow); margin-bottom: 0.5rem;">100%</div>
                            <div style="color: var(--medium-grey); font-size: 0.875rem;">Claim Coverage</div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 2.5rem; font-weight: 700; color: var(--warning-yellow); margin-bottom: 0.5rem;">5+</div>
                            <div style="color: var(--medium-grey); font-size: 0.875rem;">Years Experience</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose Us</h2>
            <p>The benefits that set us apart from other welfare associations</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem;">
            <div style="padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md); border-left: 4px solid var(--primary-purple);">
                <i class="bi bi-shield-check" style="font-size: 2rem; color: var(--primary-purple); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Reliable Coverage</h4>
                <p style="color: var(--medium-grey); margin: 0;">Comprehensive funeral expense coverage with no hidden costs or surprises</p>
            </div>
            
            <div style="padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md); border-left: 4px solid var(--success-green);">
                <i class="bi bi-cash-coin" style="font-size: 2rem; color: var(--success-green); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Affordable Rates</h4>
                <p style="color: var(--medium-grey); margin: 0;">Monthly contributions as low as KES 500 for individual coverage</p>
            </div>
            
            <div style="padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md); border-left: 4px solid var(--info-blue);">
                <i class="bi bi-clock-history" style="font-size: 2rem; color: var(--info-blue); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Quick Processing</h4>
                <p style="color: var(--medium-grey); margin: 0;">Fast claim processing within 24-48 hours of submission</p>
            </div>
            
            <div style="padding: 1.5rem; background: var(--soft-grey); border-radius: var(--radius-md); border-left: 4px solid var(--warning-yellow);">
                <i class="bi bi-people-fill" style="font-size: 2rem; color: var(--warning-yellow); margin-bottom: 1rem;"></i>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Family Plans</h4>
                <p style="color: var(--medium-grey); margin: 0;">Cover your entire family with our comprehensive family packages</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div style="max-width: 700px; margin: 0 auto; text-align: center;">
            <h2 style="color: white; margin-bottom: 1rem;">Ready to Join Our Family?</h2>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; margin-bottom: 2rem;">
                Become a member today and secure your family's future with affordable, reliable coverage
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/register" class="btn btn-light">
                    <i class="bi bi-person-plus-fill"></i> Register Now
                </a>
                <a href="/contact" class="btn btn-outline" style="border-color: white; color: white;">
                    <i class="bi bi-telephone-fill"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/public-footer.php'; ?>
