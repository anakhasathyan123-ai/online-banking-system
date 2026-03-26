<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$message = "";

/* CREATE ACCOUNT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = intval($_POST['user_id']);
    $account_no = trim($_POST['account_no']);
    $balance = floatval($_POST['balance']);

    $check = $conn->prepare("SELECT account_id FROM accounts WHERE account_no=?");
    $check->bind_param("s",$account_no);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0)
        $message="Account number already exists";
    else{

        $stmt=$conn->prepare("INSERT INTO accounts(user_id,account_no,balance) VALUES(?,?,?)");
        $stmt->bind_param("isd",$user_id,$account_no,$balance);

        if($stmt->execute())
            $message="Account created successfully";
        else
            $message="Error creating account";
    }
}

/* FETCH USERS */
$users=$conn->query("SELECT user_id,email FROM users WHERE role='customer'");

/* FETCH ACCOUNTS */
$accounts=$conn->query("
SELECT a.account_id,a.account_no,a.balance,u.email
FROM accounts a
JOIN users u ON a.user_id=u.user_id
ORDER BY a.account_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Manage Accounts</title>

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

}

.sidebar a:hover{

background:#457b9d;

}

/* MAIN */

.main{

flex:1;
padding:30px;

}

/* CARD FORM */

.card{

background:white;
padding:25px;
border-radius:10px;
width:350px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);

}

input,select{

width:100%;
padding:12px;
margin-top:10px;
border-radius:6px;
border:1px solid #ccc;

}

/* BUTTON */

.btn{

margin-top:15px;
background:linear-gradient(135deg,#06d6a0,#118ab2);
color:white;
border:none;
padding:12px;
width:100%;
border-radius:6px;
cursor:pointer;
font-weight:bold;

}

.btn:hover{

opacity:0.9;

}

/* TABLE */

.table-box{

margin-top:40px;
background:white;
border-radius:10px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);

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

/* MESSAGE */

.message{

color:green;
font-weight:bold;
margin-bottom:15px;

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

<h2>Manage Accounts</h2>

<?php if($message){ ?>
<div class="message"><?= $message ?></div>
<?php } ?>

<div class="card">

<h3>Create Account</h3>

<form method="POST">

<select name="user_id" required>

<option value="">Select User</option>

<?php while($u=$users->fetch_assoc()){ ?>

<option value="<?= $u['user_id'] ?>">
<?= htmlspecialchars($u['email']) ?>
</option>

<?php } ?>

</select>

<input type="text" name="account_no" placeholder="Account Number" required>

<input type="number" step="0.01" name="balance" placeholder="Initial Balance" required>

<button class="btn">Create Account</button>

</form>

</div>

<div class="table-box">

<table>

<tr>
<th>ID</th>
<th>Account No</th>
<th>User Email</th>
<th>Balance</th>
</tr>

<?php while($a=$accounts->fetch_assoc()){ ?>

<tr>

<td><?= $a['account_id'] ?></td>

<td><?= htmlspecialchars($a['account_no']) ?></td>

<td><?= htmlspecialchars($a['email']) ?></td>

<td>₹<?= number_format($a['balance'],2) ?></td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>

</html>