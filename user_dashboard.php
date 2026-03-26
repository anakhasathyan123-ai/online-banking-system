<?php
session_start();
require 'db.php';

/* USER LOGIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

/* FETCH USER NAME */
$stmt = $conn->prepare("SELECT full_name FROM users WHERE user_id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();

/* FETCH USER ACCOUNT */
$acc = $conn->prepare("SELECT account_no, balance FROM accounts WHERE user_id=?");
$acc->bind_param("i",$user_id);
$acc->execute();
$acc_res = $acc->get_result();
$account = $acc_res->fetch_assoc();

/* TOTAL TRANSACTIONS */
$count = $conn->prepare("
SELECT COUNT(*) AS total
FROM transactions t
JOIN accounts a ON t.from_account = a.account_id
WHERE a.user_id=?
");
$count->bind_param("i",$user_id);
$count->execute();
$count_res = $count->get_result()->fetch_assoc();

$total_transactions = $count_res['total'] ?? 0;

?>

<!DOCTYPE html>
<html>
<head>

<title>User Dashboard</title>

<style>

body{
margin:0;
font-family:Segoe UI;
background:#f4f7fc;
}

/* NAVBAR */

.navbar{
background:linear-gradient(135deg,#1d3557,#457b9d);
color:white;
padding:20px 30px;
font-size:22px;
font-weight:bold;
display:flex;
justify-content:space-between;
align-items:center;
}

.logout{
background:#e63946;
padding:8px 15px;
color:white;
text-decoration:none;
border-radius:6px;
font-size:14px;
}

.logout:hover{
opacity:0.9;
}

/* LAYOUT */

.container{
display:flex;
}

.sidebar{
width:230px;
background:#1d3557;
min-height:100vh;
color:white;
}

.sidebar h3{
padding:20px;
margin:0;
border-bottom:1px solid rgba(255,255,255,0.2);
}

.sidebar a{
display:block;
padding:15px 20px;
color:white;
text-decoration:none;
}

.sidebar a:hover{
background:#457b9d;
}

/* MAIN */

.main{
flex:1;
padding:30px;
}

.title{
font-size:28px;
margin-bottom:20px;
color:#1d3557;
}

/* CARDS */

.cards{
display:flex;
gap:20px;
margin-bottom:30px;
flex-wrap:wrap;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
flex:1;
min-width:220px;
}

.card h4{
margin:0;
color:#666;
}

.card p{
font-size:24px;
margin-top:10px;
color:#06a561;
font-weight:bold;
}

/* ACTION BUTTONS */

.actions{
margin-top:20px;
}

.btn{
display:inline-block;
padding:12px 20px;
margin-right:10px;
background:linear-gradient(135deg,#118ab2,#06d6a0);
color:white;
text-decoration:none;
border-radius:6px;
font-weight:bold;
}

.btn:hover{
opacity:0.9;
}

</style>

</head>

<body>

<div class="navbar">
🏦 Banking System — User Panel

<a class="logout" href="logout.php">Logout</a>

</div>

<div class="container">

<div class="sidebar">

<h3>User Menu</h3>

<a href="user_dashboard.php">Dashboard</a>

<a href="transactions.php">Transaction History</a>

<a href="transfer.php">Transfer Money</a>

<a href="logout.php">Logout</a>

</div>

<div class="main">

<div class="title">
Welcome, <?= htmlspecialchars($user['full_name']) ?>
</div>

<div class="cards">

<div class="card">
<h4>Account Number</h4>
<p><?= $account['account_no'] ?? 'Not Created' ?></p>
</div>

<div class="card">
<h4>Account Balance</h4>
<p>₹<?= number_format($account['balance'] ?? 0,2) ?></p>
</div>

<div class="card">
<h4>Total Transactions</h4>
<p><?= $total_transactions ?></p>
</div>

</div>

<div class="actions">

<a class="btn" href="transactions.php">
View Transactions
</a>

<a class="btn" href="transfer.php">
Transfer Money
</a>

</div>

</div>

</div>

</body>
</html>