<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

/* ADD USER */
if (isset($_POST['add_user'])) {

    $name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (full_name,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$name,$email,$password,$role);
    $stmt->execute();
}

/* DELETE USER */
if (isset($_GET['delete'])) {

    $uid = $_GET['delete'];

    $check = $conn->query("SELECT * FROM accounts WHERE user_id=$uid");

    if($check->num_rows == 0)
        $conn->query("DELETE FROM users WHERE user_id=$uid");
}

$users = $conn->query("SELECT user_id, full_name, email, role FROM users");
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Panel - Manage Users</title>

<style>

/* GLOBAL */

body{
margin:0;
font-family:Segoe UI;
background:#f4f7fc;
}

/* HEADER */

.header{

background:linear-gradient(135deg,#1d3557,#457b9d);
color:white;
padding:20px 40px;
font-size:24px;
font-weight:bold;
box-shadow:0 2px 8px rgba(0,0,0,0.2);

}

/* LAYOUT */

.container{

display:flex;

}

/* SIDEBAR */

.sidebar{

width:220px;
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
padding:40px;

}

/* CARD */

.card{

background:white;
padding:25px;
border-radius:10px;
width:350px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);

}

/* FORM */

input,select{

width:100%;
padding:12px;
margin-top:10px;
border-radius:6px;
border:1px solid #ccc;
font-size:14px;

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
font-size:16px;
font-weight:bold;

}

.btn:hover{

opacity:0.9;

}

/* TABLE */

.table-container{

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

/* DELETE BUTTON */

.delete{

color:red;
font-weight:bold;
text-decoration:none;

}

.delete:hover{

color:darkred;

}

</style>

</head>

<body>

<div class="header">

🏦 Admin Banking Panel

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

<h2>Manage Users</h2>

<div class="card">

<h3>Add New User</h3>

<form method="POST">

<input type="text" name="full_name" placeholder="Full Name" required>

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<select name="role">

<option value="customer">Customer</option>
<option value="admin">Admin</option>

</select>

<button class="btn" name="add_user">Create User</button>

</form>

</div>

<div class="table-container">

<table>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Action</th>

</tr>

<?php while($row = $users->fetch_assoc()) { ?>

<tr>

<td><?= $row['user_id'] ?></td>

<td><?= htmlspecialchars($row['full_name']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td><?= $row['role'] ?></td>

<td>

<?php if($row['role']!='admin'){ ?>

<a class="delete" href="?delete=<?= $row['user_id'] ?>">Delete</a>

<?php } else echo "-"; ?>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</div>

</body>

</html>