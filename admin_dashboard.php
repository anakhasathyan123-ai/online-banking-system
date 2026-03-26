<?php
session_start();
require 'db.php';

/* ===============================
   ADMIN LOGIN CHECK (CORRECTED)
=============================== */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

/* ===============================
   AGGREGATE FUNCTIONS
=============================== */

// Total Users
$total_users = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($result) {
    $row = $result->fetch_assoc();
    $total_users = $row['total'];
}

// Total Transactions
$total_transactions = 0;
$result = $conn->query("SELECT COUNT(*) AS total FROM transactions");
if ($result) {
    $row = $result->fetch_assoc();
    $total_transactions = $row['total'];
}

// Total Bank Balance
$total_balance = 0;
$result = $conn->query("SELECT SUM(balance) AS total FROM accounts");
if ($result) {
    $row = $result->fetch_assoc();
    $total_balance = $row['total'] ?? 0;
}

// Highest Transaction
$highest_transaction = 0;
$result = $conn->query("SELECT MAX(amount) AS max_amt FROM transactions");
if ($result) {
    $row = $result->fetch_assoc();
    $highest_transaction = $row['max_amt'] ?? 0;
}

// Average Transaction
$average_transaction = 0;
$result = $conn->query("SELECT AVG(amount) AS avg_amt FROM transactions");
if ($result) {
    $row = $result->fetch_assoc();
    $average_transaction = round($row['avg_amt'] ?? 0, 2);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>Welcome, Admin</h1>
<hr>

<h2>Admin Dashboard</h2>

<!-- ===============================
     SYSTEM REPORTS
================================= -->

<h3>📊 System Reports</h3>

<p><strong>Total Users:</strong> <?= $total_users ?></p>
<p><strong>Total Transactions:</strong> <?= $total_transactions ?></p>
<p><strong>Total Bank Balance:</strong> ₹<?= number_format($total_balance, 2) ?></p>
<p><strong>Highest Transaction:</strong> ₹<?= number_format($highest_transaction, 2) ?></p>
<p><strong>Average Transaction:</strong> ₹<?= number_format($average_transaction, 2) ?></p>

<hr>

<!-- ===============================
     ADMIN MENU
================================= -->

<ul>
    <li><a href="admin_users.php">Manage Users</a></li>
    <li><a href="admin_accounts.php">Manage Accounts</a></li>
    <li><a href="admin_transactions.php">View All Transactions</a></li>
</ul>

<hr>

<a href="logout.php">Logout</a>

</body>
</html>
