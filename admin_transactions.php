<?php
session_start();
require 'db.php';

/* ADMIN CHECK */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* FETCH TRANSACTIONS (CORRECT COLUMN: transaction_date) */

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
ORDER BY t.transaction_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Transaction History</title>

<style>

body{
margin:0;
font-family:Segoe UI;
background:#f4f7fc;
}

.navbar{
background:linear-gradient(135deg,#1d3557,#457b9d);
color:white;
padding:20px 30px;
font-size:22px;
font-weight:bold;
}

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

.main{
flex:1;
padding:30px;
}

.title{
font-size:26px;
margin-bottom:20px;
}

.table-card{
background:white;
border-radius:10px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);
overflow:hidden;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#1d3557;
color:white;
padding:15px;
}

td{
padding:12px;
border-bottom:1px solid #eee;
}

tr:hover{
background:#f1f7ff;
}

.amount{
color:#06a561;
font-weight:bold;
}

.btn{
display:inline-block;
margin-top:20px;
padding:10px 20px;
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
🏦 Banking Admin Panel
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

<div class="title">Transaction History</div>

<div class="table-card">

<table>

<tr>
<th>ID</th>
<th>From Account</th>
<th>To Account</th>
<th>Amount</th>
<th>Date</th>
</tr>

<?php while($r = $q->fetch_assoc()) { ?>

<tr>

<td><?= $r['transaction_id'] ?></td>

<td><?= htmlspecialchars($r['from_acc']) ?></td>

<td><?= htmlspecialchars($r['to_acc']) ?></td>

<td class="amount">₹<?= number_format($r['amount'],2) ?></td>

<td><?= $r['transaction_date'] ?></td>

</tr>

<?php } ?>

</table>

</div>

<a class="btn" href="dashboard.php">← Back to Dashboard</a>

</div>

</div>

</body>

</html>