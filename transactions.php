<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$q = $conn->query("
    SELECT 
        t.transaction_id,
        a1.account_no AS from_acc,
        a2.account_no AS to_acc,
        t.amount,
        t.transaction_date
    FROM transactions t
    JOIN accounts a1 ON t.from_account = a1.account_id
    JOIN accounts a2 ON t.to_account = a2.account_id
");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Transaction History</title>

    <!-- STYLE ONLY FOR TRANSACTIONS PAGE -->
    <style>
        body {
            background-color: #f4f6f8;
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 25px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: #ffffff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th {
            background: #2c3e50;
            color: #ffffff;
            padding: 12px;
            font-size: 14px;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .amount {
            font-weight: bold;
            color: #27ae60;
        }

        .back {
            display: block;
            margin: 25px auto;
            width: fit-content;
            text-decoration: none;
            font-weight: bold;
            color: #2980b9;
        }

        .back:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<h1>Transaction History</h1>

<table>
    <tr>
        <th>ID</th>
        <th>From Account</th>
        <th>To Account</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>

    <?php while ($r = $q->fetch_assoc()) { ?>
        <tr>
            <td><?= $r['transaction_id'] ?></td>
            <td><?= $r['from_acc'] ?></td>
            <td><?= $r['to_acc'] ?></td>
            <td class="amount">₹<?= number_format($r['amount'], 2) ?></td>
            <td><?= $r['transaction_date'] ?></td>
        </tr>
    <?php } ?>
</table>

<a href="user_dashboard.php" class="back">← Back to Dashboard</a>

</body>
</html>
