<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Agent Performance Report</title>
    <style>
        @page {
            margin: 24px 28px;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #111827;
        }

        .header {
            margin-bottom: 16px;
        }

        .title {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 4px 0;
        }

        .subtitle {
            font-size: 10px;
            color: #6B7280;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #E5E7EB;
            padding: 6px 6px;
            text-align: left;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #F3F4F6;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 9px;
            letter-spacing: 0.3px;
        }

        .empty {
            padding: 24px;
            text-align: center;
            color: #6B7280;
        }
    </style>
</head>
<body>
<?php
function formatAgentValue($key, $value)
{
    if ($value === null || $value === '') {
        return '';
    }

    if (is_bool($value)) {
        return $value ? 'Yes' : 'No';
    }

    if (is_array($value)) {
        return json_encode($value);
    }

    $lowerKey = strtolower((string)$key);
    if (is_string($value) && (substr($lowerKey, -3) === '_at' || substr($lowerKey, -5) === '_date')) {
        $timestamp = strtotime($value);
        if ($timestamp) {
            return date('Y-m-d H:i', $timestamp);
        }
    }

    return (string)$value;
}
?>

<div class="header">
    <h1 class="title">Agent Performance Report</h1>
    <p class="subtitle">Generated: <?php echo htmlspecialchars($generatedAt ?? date('Y-m-d H:i')); ?></p>
</div>

<?php if (!empty($agents)): ?>
    <?php $headers = array_keys($agents[0]); ?>
    <table>
        <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                    <th><?php echo htmlspecialchars(str_replace('_', ' ', $header)); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agents as $agent): ?>
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <?php $value = $agent[$header] ?? ''; ?>
                        <td><?php echo htmlspecialchars(formatAgentValue($header, $value)); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="empty">No agents available for this report.</div>
<?php endif; ?>
</body>
</html>
