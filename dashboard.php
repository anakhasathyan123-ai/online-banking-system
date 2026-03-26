<?php
session_start();
require 'db.php';

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

/* AGGREGATE FUNCTIONS */

// Total Users
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// Total Transactions
$total_transactions = $conn->query("SELECT COUNT(*) AS total FROM transactions")->fetch_assoc()['total'];

// Total Balance
$total_balance = $conn->query("SELECT SUM(balance) AS total FROM accounts")->fetch_assoc()['total'] ?? 0;

// Highest Transaction
$highest_transaction = $conn->query("SELECT MAX(amount) AS max_amt FROM transactions")->fetch_assoc()['max_amt'] ?? 0;

// Average Transaction
$average_transaction = round(
$conn->query("SELECT AVG(amount) AS avg_amt FROM transactions")->fetch_assoc()['avg_amt'] ?? 0, 2);

?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Dashboard</title>

<style>

/* GLOBAL */

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
}

/* LAYOUT */

.container{
display:flex;
}

/* SIDEBAR */

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
font-size:15px;

}

.sidebar a:hover{

background:#457b9d;

}

/* MAIN */

.main{

flex:1;
padding:30px;

}

/* CARDS */

.cards{

display:flex;
gap:20px;
flex-wrap:wrap;

}

.card{

background:white;
padding:20px;
width:220px;
border-radius:10px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
transition:0.3s;

}

.card:hover{

transform:translateY(-5px);

}

.card h4{

margin:0;
color:#555;

}

.card p{

font-size:22px;
font-weight:bold;
color:#06a561;

}

/* BUTTONS */

.buttons{

margin-top:30px;

}

.btn{

display:inline-block;
padding:12px 20px;
margin-right:15px;
border-radius:6px;
text-decoration:none;
color:white;
font-weight:bold;
background:linear-gradient(135deg,#118ab2,#06d6a0);
transition:0.3s;

}

.btn:hover{

opacity:0.85;

}

.logout{

background:linear-gradient(135deg,#e63946,#d00000);

}

.logout:hover{

opacity:0.85;

}

/* SECTION */

.section-title{

margin-top:30px;
margin-bottom:15px;
font-size:20px;
font-weight:bold;

}

</style>

</head>

<body>

<div class="navbar">

🏦 Banking Admin Dashboard

</div>

<div class="container">

<div class="sidebar">

<h3>Admin Menu</h3>

<a href="dashboard.php">Dashboard</a>
<a href="admin_users.php">Manage Users</a>
<a href="admin_accounts.php">Manage Accounts</a>
<a href="admin_transactions.php">Transactions</a>
<a href="logout.php">Logout</a>

</div>

<div class="main">

<h2>Welcome, Admin 👋</h2>

<div class="section-title">System Reports</div>

<div class="cards">

<div class="card">
<h4>Total Users</h4>
<p><?= $total_users ?></p>
</div>

<div class="card">
<h4>Total Transactions</h4>
<p><?= $total_transactions ?></p>
</div>

<div class="card">
<h4>Total Balance</h4>
<p>₹<?= number_format($total_balance,2) ?></p>
</div>

<div class="card">
<h4>Highest Transaction</h4>
<p>₹<?= number_format($highest_transaction,2) ?></p>
</div>

<div class="card">
<h4>Average Transaction</h4>
<p>₹<?= number_format($average_transaction,2) ?></p>
</div>

</div>

<div class="section-title">Admin Controls</div>

<div class="buttons">

<a class="btn" href="admin_users.php">Manage Users</a>

<a class="btn" href="admin_accounts.php">Manage Accounts</a>

<a class="btn" href="admin_transactions.php">View Transactions</a>

<br><br>

<a class="btn logout" href="logout.php">Logout</a>

</div>

</div>

</div>

</body>

</html>