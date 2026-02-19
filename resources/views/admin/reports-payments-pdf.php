<?php
// resources/views/admin/reports-payments-pdf.php
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
    <h2>Monthly Payment Summary</h2>
    <p>Period: <?php echo htmlspecialchars($dateFrom); ?> to <?php echo htmlspecialchars($dateTo); ?></p>
    <p>Generated: <?php echo htmlspecialchars($generatedAt); ?></p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Total Payments</th>
                <th>Total Amount (KES)</th>
                <th>Completed Amount</th>
                <th>Completed Count</th>
                <th>Failed Count</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($payments as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                <td><?php echo (int)$row['total_payments']; ?></td>
                <td><?php echo number_format($row['total_amount'], 2); ?></td>
                <td><?php echo number_format($row['completed_amount'], 2); ?></td>
                <td><?php echo (int)$row['completed_count']; ?></td>
                <td><?php echo (int)$row['failed_count']; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
