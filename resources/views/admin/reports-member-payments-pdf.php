<?php
// resources/views/admin/reports-member-payments-pdf.php
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
    <h2>Member Payment History</h2>
    <p>Member: <strong><?php echo htmlspecialchars($member['member_number'] . ' - ' . $member['first_name'] . ' ' . $member['last_name']); ?></strong></p>
    <p>Period: <?php echo htmlspecialchars($dateFrom); ?> to <?php echo htmlspecialchars($dateTo); ?></p>
    <p>Generated: <?php echo htmlspecialchars($generatedAt); ?></p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Amount (KES)</th>
                <th>Method</th>
                <th>Status</th>
                <th>Reference</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($payments as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                <td><?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td><?php echo htmlspecialchars($row['reference']); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
