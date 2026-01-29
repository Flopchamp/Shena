<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<!-- Contact Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Contact Us</h1>
                <p class="lead mb-4">Get in touch with our friendly team. We're here to help you 24/7 with any questions or assistance you need.</p>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-phone fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                        <h5>24/7 Emergency Hotline</h5>
                        <p class="text-muted">Call us anytime for immediate assistance</p>
                        <h4 class="text-primary">0748 585 067 / 0748 585 071</h4>
                        <small class="text-muted">Available round the clock</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope fa-3x text-success mb-3"></i>
                        <h5>Email Support</h5>
                        <p class="text-muted">Send us your questions and concerns</p>
                        <h6 class="text-success">info@shenacompanion.ac.ke</h6>
                        <small class="text-muted">We respond within 24 hours</small>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-map-marker-alt fa-3x text-danger mb-3"></i>
                        <h5>Visit Our Office</h5>
                        <p class="text-muted">Come see us at our main office</p>
                        <address class="text-danger">
                            <strong>SHENA Companion Welfare Association</strong><br>
                            P.O. Box 4018<br>
                            40100 - Kisumu
                        </address>
                        <small class="text-muted">Mon-Fri: 8:00 AM - 5:00 PM</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- M-Pesa Payment Information -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">M-Pesa Payment Information</h2>
                <p class="lead mb-4">Make your payments conveniently through M-Pesa</p>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <i class="fas fa-mobile-alt fa-4x text-success mb-3"></i>
                                <h4>M-Pesa Paybill</h4>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-success text-white p-4 rounded">
                                    <h2 class="mb-2">4163987</h2>
                                    <p class="mb-0">Use your Member Number as Account Number</p>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6><i class="fas fa-user-plus text-primary"></i> Registration Fee</h6>
                                <p class="text-muted">KES 200 (one-time, non-refundable)</p>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fas fa-calendar text-success"></i> Monthly Contributions</h6>
                                <p class="text-muted">According to your selected package</p>
                            </div>
                            <div class="col-md-4">
                                <h6><i class="fas fa-redo text-warning"></i> Reactivation Fee</h6>
                                <p class="text-muted">KES 100 + all outstanding contributions</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Send Us a Message</h2>
                
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="/contact" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? ''; ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="+254 700 000 000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <select class="form-select" id="subject" name="subject">
                                        <option value="">Select a subject</option>
                                        <option value="General Inquiry">General Inquiry</option>
                                        <option value="Membership Question">Membership Question</option>
                                        <option value="Payment Issue">Payment Issue</option>
                                        <option value="Claim Support">Claim Support</option>
                                        <option value="Technical Support">Technical Support</option>
                                        <option value="Emergency">Emergency</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Please describe how we can help you..." required></textarea>
                            </div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-paper-plane"></i> Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Frequently Asked Questions</h2>
                
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq1">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1">
                                How do I become a member?
                            </button>
                        </h2>
                        <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Simply register online, pay the KES 200 registration fee via M-Pesa, and wait for admin approval. Once approved, you can start making monthly contributions.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq2">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2">
                                What happens if I miss a payment?
                            </button>
                        </h2>
                                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Your membership remains active for a maximum grace period of 2 consecutive months (60 days) after a missed contribution. If you fail to pay for more than 2 months, your account goes into default and cover is suspended. You can reactivate by paying all outstanding contributions plus a KES 100 reactivation fee and serving a fresh 4-month maturity period.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq3">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3">
                                How do I make a claim?
                            </button>
                        </h2>
                                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Notify SHENA Companion immediately by phone or by visiting our office, then log in to your member dashboard and submit a claim. You must provide: copy of the deceased's ID or birth certificate, a letter from the area chief, and the mortuary invoice. Claims are verified (including maturity and payment status) and once approved we arrange services directly with the mortuary and family.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faq4">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4">
                                Can I change my membership package?
                            </button>
                        </h2>
                        <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Yes, you can upgrade your package at any time. Contact our support team or visit your member dashboard to make changes. Downgrades may have waiting periods.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Emergency Contact Section -->
<section class="py-5 bg-danger text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4"><i class="fas fa-exclamation-triangle"></i> Emergency?</h2>
                <p class="lead mb-4">If you have an emergency and need immediate assistance, call our 24/7 hotline now.</p>
                <a href="tel:0748585067" class="btn btn-light btn-lg">
                    <i class="fas fa-phone"></i> Call Now: 0748 585 067 / 0748 585 071
                </a>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
