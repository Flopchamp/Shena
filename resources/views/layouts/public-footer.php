<!-- Footer -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <!-- Company Info -->
            <div class="footer-section">
                <h4>SHENA COMPANION</h4>
                <p>Providing dignified welfare and funeral services to our community. Your trusted partner in times of need, ensuring peace of mind for you and your loved ones.</p>
                <div style="margin-top: 1rem;">
                    <p style="font-size: 0.875rem; opacity: 0.8;">Est. [Year] | License No: [Number]</p>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h4>Quick Links</h4>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="/">Home</a>
                    <a href="/about">About Us</a>
                    <a href="/services">Our Services</a>
                    <a href="/membership">Membership Packages</a>
                    <a href="/contact">Contact Us</a>
                    <a href="/login">Member Login</a>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="footer-section">
                <h4>Contact Us</h4>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    <p>
                        <i class="bi bi-telephone-fill"></i> 
                        <a href="tel:+254XXXXXXXXX">+254 XXX XXX XXX</a>
                    </p>
                    <p>
                        <i class="bi bi-envelope-fill"></i> 
                        <a href="mailto:info@shenacompanion.co.ke">info@shenacompanion.co.ke</a>
                    </p>
                    <p>
                        <i class="bi bi-geo-alt-fill"></i> 
                        Nairobi, Kenya
                    </p>
                    <div style="margin-top: 1rem; display: flex; gap: 1rem; font-size: 1.5rem;">
                        <a href="#" data-tooltip="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" data-tooltip="Twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" data-tooltip="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" data-tooltip="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- M-Pesa Payment Info -->
            <div class="footer-section">
                <h4>M-Pesa Paybill</h4>
                <div style="background: rgba(255,255,255,0.1); padding: 1.5rem; border-radius: var(--radius-md); text-align: center;">
                    <p style="font-size: 0.875rem; margin-bottom: 0.5rem; opacity: 0.9;">Make your contributions via M-Pesa:</p>
                    <p style="font-size: 2.5rem; font-weight: 700; font-family: var(--font-mono); line-height: 1; margin-bottom: 0.5rem;">4163987</p>
                    <p style="font-size: 0.75rem; opacity: 0.8;">Account Number: Your Member ID</p>
                </div>
                <div style="margin-top: 1rem; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: var(--radius-sm); font-size: 0.875rem;">
                    <p style="margin-bottom: 0.5rem;"><strong>Office Hours:</strong></p>
                    <p style="opacity: 0.9;">Mon - Fri: 8:00 AM - 5:00 PM</p>
                    <p style="opacity: 0.9;">Sat: 9:00 AM - 1:00 PM</p>
                    <p style="opacity: 0.9;">Sun & Holidays: Closed</p>
                </div>
            </div>
        </div>
        
        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> SHENA Companion Welfare Association. All rights reserved.</p>
            <p style="margin-top: 0.5rem;">
                <a href="/terms" style="margin: 0 0.5rem;">Terms & Conditions</a> | 
                <a href="/privacy" style="margin: 0 0.5rem;">Privacy Policy</a> | 
                <a href="/sitemap" style="margin: 0 0.5rem;">Sitemap</a>
            </p>
        </div>
    </div>
</footer>

<script src="/public/js/app.js"></script>
<?php if (isset($additionalJS)): ?>
    <?php foreach ($additionalJS as $js): ?>
        <script src="<?php echo $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>

<?php if (isset($inlineJS)): ?>
    <script>
        <?php echo $inlineJS; ?>
    </script>
<?php endif; ?>

</body>
</html>
