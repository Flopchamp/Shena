<?php
// resources/views/admin/reports-financial-pdf.php
?>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 13px; }
        h2 { color: #7F3D9E; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
    <h2>Financial Analysis & Trends</h2>
    <p>Period: <?php echo htmlspecialchars($dateFrom); ?> to <?php echo htmlspecialchars($dateTo); ?></p>
    <p>Generated: <?php echo htmlspecialchars($generatedAt); ?></p>
    <h3>Summary</h3>
    <table>
        <tr><th>Total Payments</th><td><?php echo (int)$summary['total_payments']; ?></td></tr>
        <tr><th>Completed Payments</th><td><?php echo (int)$summary['completed_payments']; ?></td></tr>
        <tr><th>Pending Payments</th><td><?php echo (int)$summary['pending_payments']; ?></td></tr>
        <tr><th>Failed Payments</th><td><?php echo (int)$summary['failed_payments']; ?></td></tr>
        <tr><th>Total Amount (KES)</th><td><?php echo number_format($summary['total_amount'], 2); ?></td></tr>
        <tr><th>Completed Amount (KES)</th><td><?php echo number_format($summary['completed_amount'], 2); ?></td></tr>
    </table>
    <h3>Payments by Method</h3>
    <table>
        <thead><tr><th>Method</th><th>Count</th><th>Total Amount (KES)</th></tr></thead>
        <tbody>
        <?php foreach ($methods as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td><?php echo (int)$row['count']; ?></td>
                <td><?php echo number_format($row['total_amount'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <h3>Payments by Type</h3>
    <table>
        <thead><tr><th>Type</th><th>Count</th><th>Total Amount (KES)</th></tr></thead>
        <tbody>
        <?php foreach ($types as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['payment_type']); ?></td>
                <td><?php echo (int)$row['count']; ?></td>
                <td><?php echo number_format($row['total_amount'], 2); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
