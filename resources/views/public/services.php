<?php
$page = 'services';
$pageTitle = 'Our Services';
include VIEWS_PATH . '/layouts/public-header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Our Services</h1>
            <p class="hero-subtitle">
                Comprehensive funeral and welfare services designed to support you and your family during difficult times
            </p>
        </div>
    </div>
    
    <!-- Decorative wave -->
    <svg class="hero-wave" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
    </svg>
</section>

<!-- Core Services -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>What We Offer</h2>
            <p>Complete funeral services to support you through every step</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <!-- Mortuary Services -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: var(--gradient-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-hospital-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Mortuary Services</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Complete mortuary care including body preservation, preparation, and professional handling with dignity and respect.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Body preservation</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Professional preparation</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Viewing arrangements</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Transportation -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: var(--gradient-success); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-truck" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Transportation</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Reliable transportation services for the deceased from hospital to mortuary and from mortuary to burial site.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Hospital to mortuary</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Mortuary to burial site</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Professional hearses</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Coffin & Caskets -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: var(--gradient-info); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Coffins & Caskets</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Wide selection of quality coffins and caskets in various styles and materials to honor your loved one.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Multiple styles available</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Quality materials</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Affordable pricing</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Burial Services -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: var(--gradient-warning); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-flower2" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Burial Services</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Complete burial arrangements including grave preparation, ceremony coordination, and all necessary permits.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Grave preparation</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Ceremony coordination</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Permit processing</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Memorial Services -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: var(--gradient-danger); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-bookmark-heart-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Memorial Services</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Assistance with planning and organizing memorial services that celebrate the life of your loved one.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Service planning</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Venue arrangements</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Program coordination</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Counseling Support -->
            <div class="card">
                <div class="card-body">
                    <div style="width: 70px; height: 70px; margin-bottom: 1.5rem; background: linear-gradient(135deg, #8E2DE2 0%, #4A00E0 100%); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-heart-pulse-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 1rem;">Counseling Support</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Professional grief counseling and emotional support services for families during their time of loss.
                    </p>
                    <ul style="list-style: none; padding: 0; margin: 0;">
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Grief counseling</span>
                        </li>
                        <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--light-grey); display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>Family support</span>
                        </li>
                        <li style="padding: 0.5rem 0; display: flex; align-items: center; gap: 0.75rem;">
                            <i class="bi bi-check-circle-fill" style="color: var(--success-green);"></i>
                            <span>24/7 hotline</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Process -->
<section class="section" style="background: var(--soft-grey);">
    <div class="container">
        <div class="section-header">
            <h2>How It Works</h2>
            <p>Our simple and efficient process to serve you</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; margin-top: 3rem;">
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <span style="font-size: 2rem; font-weight: 700; color: var(--primary-purple);">1</span>
                </div>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Contact Us</h4>
                <p style="color: var(--medium-grey);">Call our 24/7 hotline or visit our office to report the loss</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <span style="font-size: 2rem; font-weight: 700; color: var(--primary-purple);">2</span>
                </div>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Submit Claim</h4>
                <p style="color: var(--medium-grey);">Provide required documents and complete claim forms</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <span style="font-size: 2rem; font-weight: 700; color: var(--primary-purple);">3</span>
                </div>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Get Approved</h4>
                <p style="color: var(--medium-grey);">Quick claim processing within 24-48 hours</p>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-lg);">
                    <span style="font-size: 2rem; font-weight: 700; color: var(--primary-purple);">4</span>
                </div>
                <h4 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Receive Services</h4>
                <p style="color: var(--medium-grey);">We handle all arrangements with care and professionalism</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div style="max-width: 700px; margin: 0 auto; text-align: center;">
            <h2 style="color: white; margin-bottom: 1rem;">Need Immediate Assistance?</h2>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; margin-bottom: 2rem;">
                Our compassionate team is available 24/7 to support you during this difficult time
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="tel:0748585067" class="btn btn-light">
                    <i class="bi bi-telephone-fill"></i> Call 0748 585 067
                </a>
                <a href="/contact" class="btn btn-outline" style="border-color: white; color: white;">
                    <i class="bi bi-envelope-fill"></i> Contact Us
                </a>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/public-footer.php'; ?>
