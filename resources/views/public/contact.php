<?php
$page = 'contact';
$pageTitle = 'Contact Us';
include VIEWS_PATH . '/layouts/public-header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Get In Touch</h1>
            <p class="hero-subtitle">
                We're here to help you 24/7. Reach out to us anytime for assistance or inquiries
            </p>
        </div>
    </div>
    
    <!-- Decorative wave -->
    <svg class="hero-wave" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 0L60 10C120 20 240 40 360 46.7C480 53 600 47 720 43.3C840 40 960 40 1080 46.7C1200 53 1320 67 1380 73.3L1440 80V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0V0Z" fill="white"/>
    </svg>
</section>

<!-- Contact Information Cards -->
<section class="section">
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-bottom: 4rem;">
            <!-- Emergency Hotline -->
            <div class="card" style="text-align: center;">
                <div class="card-body" style="padding: 2.5rem;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-danger); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-telephone-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">24/7 Emergency Hotline</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Call us anytime for immediate assistance
                    </p>
                    <a href="tel:0748585067" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-purple); text-decoration: none; display: block; margin-bottom: 0.5rem;">
                        0748 585 067
                    </a>
                    <a href="tel:0748585071" style="font-size: 1.5rem; font-weight: 700; color: var(--primary-purple); text-decoration: none; display: block;">
                        0748 585 071
                    </a>
                    <p style="color: var(--medium-grey); font-size: 0.875rem; margin-top: 1rem;">Available round the clock</p>
                </div>
            </div>
            
            <!-- Email -->
            <div class="card" style="text-align: center;">
                <div class="card-body" style="padding: 2.5rem;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-success); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-envelope-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Email Support</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Send us your questions and concerns
                    </p>
                    <a href="mailto:info@shenacompanion.ac.ke" style="font-size: 1.125rem; font-weight: 600; color: var(--primary-purple); text-decoration: none; word-break: break-all;">
                        info@shenacompanion.ac.ke
                    </a>
                    <p style="color: var(--medium-grey); font-size: 0.875rem; margin-top: 1rem;">We respond within 24 hours</p>
                </div>
            </div>
            
            <!-- Office Location -->
            <div class="card" style="text-align: center;">
                <div class="card-body" style="padding: 2.5rem;">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-geo-alt-fill" style="font-size: 2rem; color: white;"></i>
                    </div>
                    <h3 style="color: var(--secondary-violet); margin-bottom: 0.75rem;">Visit Our Office</h3>
                    <p style="color: var(--medium-grey); margin-bottom: 1.5rem;">
                        Come see us at our main office
                    </p>
                    <address style="font-style: normal; color: var(--secondary-violet); line-height: 1.6;">
                        <strong>SHENA Companion Welfare Association</strong><br>
                        P.O. Box 4018<br>
                        40100 - Kisumu
                    </address>
                    <p style="color: var(--medium-grey); font-size: 0.875rem; margin-top: 1rem;">Mon-Fri: 8:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>
        
        <!-- Contact Form -->
        <div style="max-width: 800px; margin: 0 auto;">
            <div class="card">
                <div class="card-header">
                    <h3 style="margin: 0;"><i class="bi bi-envelope-paper-fill"></i> Send Us a Message</h3>
                </div>
                <div class="card-body" style="padding: 2.5rem;">
                    <form method="POST" action="/contact/submit">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       class="form-control" 
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="tel" 
                                       id="phone" 
                                       name="phone" 
                                       class="form-control" 
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control" 
                                   required>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label class="form-label" for="subject">Subject</label>
                            <select id="subject" name="subject" class="form-select" required>
                                <option value="">Select a subject...</option>
                                <option value="general">General Inquiry</option>
                                <option value="membership">Membership Information</option>
                                <option value="claims">Claims Assistance</option>
                                <option value="payments">Payment Questions</option>
                                <option value="emergency">Emergency Assistance</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label class="form-label" for="message">Message</label>
                            <textarea id="message" 
                                      name="message" 
                                      class="form-control" 
                                      rows="6" 
                                      required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg" style="width: 100%;">
                            <i class="bi bi-send-fill"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- M-Pesa Payment Section -->
<section class="section" style="background: var(--soft-grey);">
    <div class="container">
        <div style="max-width: 700px; margin: 0 auto;">
            <div class="card" style="background: white; border: 2px solid var(--success-green);">
                <div class="card-body" style="padding: 2.5rem; text-align: center;">
                    <div style="width: 100px; height: 100px; margin: 0 auto 1.5rem; background: var(--gradient-success); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-phone-fill" style="font-size: 3rem; color: white;"></i>
                    </div>
                    <h2 style="color: var(--secondary-violet); margin-bottom: 1rem;">Make Payments via M-Pesa</h2>
                    <p style="color: var(--medium-grey); font-size: 1.125rem; margin-bottom: 2rem;">
                        Pay your monthly contributions easily using M-Pesa
                    </p>
                    
                    <div style="background: var(--soft-grey); padding: 2rem; border-radius: var(--radius-md); margin-bottom: 1.5rem;">
                        <div style="margin-bottom: 1rem;">
                            <span style="color: var(--medium-grey); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">Paybill Number</span>
                            <span style="font-size: 3rem; font-weight: 700; color: var(--primary-purple); font-family: var(--font-mono);">4163987</span>
                        </div>
                        <p style="color: var(--medium-grey); margin: 0; font-size: 0.875rem;">
                            Account Number: Your Member ID
                        </p>
                    </div>
                    
                    <div style="text-align: left; background: white; border: 1px solid var(--light-grey); padding: 1.5rem; border-radius: var(--radius-md);">
                        <h4 style="color: var(--secondary-violet); margin-bottom: 1rem; font-size: 1rem;">How to Pay:</h4>
                        <ol style="color: var(--medium-grey); line-height: 1.8; padding-left: 1.25rem; margin: 0;">
                            <li>Go to M-Pesa menu on your phone</li>
                            <li>Select "Lipa na M-Pesa"</li>
                            <li>Select "Paybill"</li>
                            <li>Enter Business Number: <strong style="color: var(--primary-purple);">4163987</strong></li>
                            <li>Enter Account Number: <strong>Your Member ID</strong></li>
                            <li>Enter Amount</li>
                            <li>Enter your M-Pesa PIN</li>
                            <li>Confirm the transaction</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section (Optional - Add actual map integration) -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Find Us</h2>
            <p>Our office location in Kisumu</p>
        </div>
        
        <div style="margin-top: 2rem; height: 400px; background: var(--soft-grey); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; border: 2px solid var(--light-grey);">
            <div style="text-align: center; color: var(--medium-grey);">
                <i class="bi bi-map" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                <p style="font-size: 1.125rem;">Map integration coming soon</p>
                <p style="font-size: 0.875rem;">Visit us at P.O. Box 4018, 40100 - Kisumu</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div style="max-width: 700px; margin: 0 auto; text-align: center;">
            <h2 style="color: white; margin-bottom: 1rem;">Ready to Become a Member?</h2>
            <p style="color: rgba(255, 255, 255, 0.9); font-size: 1.125rem; margin-bottom: 2rem;">
                Join hundreds of families who trust us with their funeral service needs
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <a href="/register" class="btn btn-light">
                    <i class="bi bi-person-plus-fill"></i> Register Now
                </a>
                <a href="/membership" class="btn btn-outline" style="border-color: white; color: white;">
                    <i class="bi bi-info-circle-fill"></i> View Packages
                </a>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/public-footer.php'; ?>
