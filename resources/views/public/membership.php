<?php include VIEWS_PATH . '/layouts/header.php'; ?>

<!-- Membership Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Membership Packages</h1>
                <p class="lead mb-4">Choose the perfect membership package for you and your family. Affordable monthly contributions with comprehensive coverage.</p>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-users fa-10x opacity-75"></i>
            </div>
        </div>
    </div>
</section>

<!-- Membership Packages Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold">Choose Your Package</h2>
                <p class="lead">Select from our flexible membership options designed to meet your family's needs and budget.</p>
            </div>
        </div>

        <div class="row">
            <!-- Individual Package -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-primary">
                    <div class="card-header bg-primary text-white text-center">
                        <h5><i class="fas fa-user"></i> Individual Package</h5>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-primary">From KES 500<small class="text-muted">/month</small></h3>
                        <p class="text-muted">Perfect for single individuals</p>
                        
                        <hr>
                        <h6 class="fw-bold">Age-Based Pricing:</h6>
                        <ul class="list-unstyled small">
                            <li>18-30 years: <strong>KES 500</strong></li>
                            <li>31-50 years: <strong>KES 600</strong></li>
                            <li>51-65 years: <strong>KES 750</strong></li>
                            <li>66-80 years: <strong>KES 900</strong></li>
                            <li>81-100 years: <strong>KES 1,000</strong></li>
                        </ul>
                        
                        <hr>
                        <h6 class="fw-bold">Benefits Include:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Personal coverage</li>
                            <li><i class="fas fa-check text-success"></i> Mortuary expenses</li>
                            <li><i class="fas fa-check text-success"></i> Transportation</li>
                            <li><i class="fas fa-check text-success"></i> Body dressing</li>
                            <li><i class="fas fa-check text-success"></i> Grace period</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/register" class="btn btn-primary btn-block w-100">Choose Individual</a>
                    </div>
                </div>
            </div>

            <!-- Couple Package -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-success">
                    <div class="card-header bg-success text-white text-center">
                        <h5><i class="fas fa-heart"></i> Couple Package</h5>
                        <span class="badge bg-warning text-dark">10% Discount</span>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-success">KES 800<small class="text-muted">/month</small></h3>
                        <p class="text-muted">Great value for couples</p>
                        
                        <hr>
                        <h6 class="fw-bold">Coverage Details:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Both spouses covered</li>
                            <li><i class="fas fa-check text-success"></i> 10% discount applied</li>
                            <li><i class="fas fa-check text-success"></i> Shared benefits</li>
                            <li><i class="fas fa-check text-success"></i> Joint premium payments</li>
                        </ul>
                        
                        <hr>
                        <h6 class="fw-bold">All Individual Benefits Plus:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Spouse automatic coverage</li>
                            <li><i class="fas fa-check text-success"></i> Simplified management</li>
                            <li><i class="fas fa-check text-success"></i> Cost savings</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/register" class="btn btn-success btn-block w-100">Choose Couple</a>
                    </div>
                </div>
            </div>

            <!-- Family Package -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-sm border-info">
                    <div class="card-header bg-info text-white text-center">
                        <h5><i class="fas fa-home"></i> Family Package</h5>
                        <span class="badge bg-warning text-dark">15% Discount</span>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-info">KES 1,200<small class="text-muted">/month</small></h3>
                        <p class="text-muted">Best value for families</p>
                        
                        <hr>
                        <h6 class="fw-bold">Family Coverage:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Up to 6 family members</li>
                            <li><i class="fas fa-check text-success"></i> 15% discount applied</li>
                            <li><i class="fas fa-check text-success"></i> Children included</li>
                            <li><i class="fas fa-check text-success"></i> Extended family options</li>
                        </ul>
                        
                        <hr>
                        <h6 class="fw-bold">Family Benefits:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Comprehensive coverage</li>
                            <li><i class="fas fa-check text-success"></i> Significant savings</li>
                            <li><i class="fas fa-check text-success"></i> Easy member management</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/register" class="btn btn-info btn-block w-100">Choose Family</a>
                    </div>
                </div>
            </div>

            <!-- Executive Package -->
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card h-100 shadow-lg border-warning position-relative">
                    <div class="card-header bg-warning text-dark text-center">
                        <h5><i class="fas fa-crown"></i> Executive Package</h5>
                        <span class="badge bg-primary">Premium</span>
                    </div>
                    <div class="card-body text-center">
                        <h3 class="text-warning">KES 2,000<small class="text-muted">/month</small></h3>
                        <p class="text-muted">Premium services and benefits</p>
                        
                        <hr>
                        <h6 class="fw-bold">Executive Benefits:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Premium services</li>
                            <li><i class="fas fa-check text-success"></i> Priority processing</li>
                            <li><i class="fas fa-check text-success"></i> Enhanced benefits</li>
                            <li><i class="fas fa-check text-success"></i> Dedicated support</li>
                        </ul>
                        
                        <hr>
                        <h6 class="fw-bold">Additional Perks:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-star text-warning"></i> VIP treatment</li>
                            <li><i class="fas fa-star text-warning"></i> Express claims</li>
                            <li><i class="fas fa-star text-warning"></i> Premium coffins</li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="/register" class="btn btn-warning btn-block w-100 text-dark fw-bold">Choose Executive</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Registration Requirements Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="text-center mb-5">Registration Requirements</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5><i class="fas fa-file-alt text-primary"></i> Required Documents</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Copy of National ID</li>
                                    <li><i class="fas fa-check text-success"></i> Passport-size photos (2)</li>
                                    <li><i class="fas fa-check text-success"></i> Next of kin details</li>
                                    <li><i class="fas fa-check text-success"></i> Contact information</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5><i class="fas fa-money-bill text-success"></i> Payment Information</h5>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Registration fee: <strong>KES 200</strong></li>
                                    <li><i class="fas fa-check text-success"></i> M-Pesa Paybill: <strong>4163987</strong></li>
                                    <li><i class="fas fa-check text-success"></i> Account: Your member number</li>
                                    <li><i class="fas fa-check text-success"></i> Monthly contributions start after approval</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Grace Period Information -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Grace Period Policy</h2>
                <p class="lead mb-4">We understand that life can present challenges. That's why we offer generous grace periods for our members.</p>
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                                <h5>Members Under 80 Years</h5>
                                <h3 class="text-primary">4 Months</h3>
                                <p>Grace period for payment delays</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-heart fa-3x text-success mb-3"></i>
                                <h5>Members 80+ Years</h5>
                                <h3 class="text-success">5 Months</h3>
                                <p>Extended grace period for seniors</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Reactivation Fee:</strong> KES 100 plus any outstanding dues
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="mb-4">Ready to Secure Your Family's Future?</h2>
                <p class="lead mb-4">Join thousands of satisfied members who have chosen Shena Companion for their welfare needs.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/register" class="btn btn-light btn-lg">
                        <i class="fas fa-user-plus"></i> Register Now - Only KES 200
                    </a>
                    <a href="/contact" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-question-circle"></i> Have Questions?
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include VIEWS_PATH . '/layouts/footer.php'; ?>
