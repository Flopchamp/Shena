<?php include_once __DIR__ . '/../layouts/agent-header.php'; ?>

<div style="max-width:800px;margin:48px auto;text-align:center;padding:24px;background:#fff;border-radius:12px;box-shadow:0 6px 24px rgba(0,0,0,0.04)">
    <h2 style="color:#111;font-family:inherit;margin-bottom:12px"><?php echo htmlspecialchars($title ?? 'Success'); ?></h2>
    <p style="color:#374151;font-size:16px;margin-bottom:18px"><?php echo htmlspecialchars($message ?? 'Operation completed.'); ?></p>
    <div style="margin-top:12px;color:#6B7280">You will be redirected in <span id="countdown"><?php echo intval($redirect_delay ?? 3); ?></span> seconds...</div>
    <div style="margin-top:18px"><a href="<?php echo htmlspecialchars($redirect_url ?? '/agent/members'); ?>" class="btn btn-primary">Go to Members Now</a></div>
</div>

<script>
(function(){
    var delay = <?php echo intval($redirect_delay ?? 3); ?>;
    var url = '<?php echo htmlspecialchars($redirect_url ?? '/agent/members'); ?>';
    var el = document.getElementById('countdown');
    var t = delay;
    var iv = setInterval(function(){
        t--;
        if (el) el.textContent = t;
        if (t <= 0) {
            clearInterval(iv);
            window.location.href = url;
        }
    }, 1000);
})();
</script>

<?php include_once __DIR__ . '/../layouts/agent-footer.php'; ?>