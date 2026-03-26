<?php
session_start();
require 'db.php';

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$message = "";

/* GET USER ACCOUNT */
$stmt = $conn->prepare("SELECT account_id, account_no, balance FROM accounts WHERE user_id=?");
$stmt->bind_param("i",$user_id);
$stmt->execute();
$result = $stmt->get_result();
$from = $result->fetch_assoc();

if(!$from){
    die("No account found.");
}

$from_id = $from['account_id'];
$from_no = $from['account_no'];
$balance = $from['balance'];


/* TRANSFER PROCESS */
if($_SERVER['REQUEST_METHOD']=="POST"){

$to_acc_no = $_POST['to_account'];
$amount = floatval($_POST['amount']);

if($amount <= 0){
$message="Invalid amount.";
}
elseif($amount > $balance){
$message="Insufficient balance.";
}
else{

/* GET RECEIVER */
$stmt = $conn->prepare("SELECT account_id FROM accounts WHERE account_no=?");
$stmt->bind_param("s",$to_acc_no);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows == 0){
$message="Receiver account not found.";
}
else{

$to = $res->fetch_assoc();
$to_id = $to['account_id'];

$conn->begin_transaction();

try{

/* DEDUCT */
$update1 = $conn->prepare("UPDATE accounts SET balance=balance-? WHERE account_id=?");
$update1->bind_param("di",$amount,$from_id);
$update1->execute();

/* ADD */
$update2 = $conn->prepare("UPDATE accounts SET balance=balance+? WHERE account_id=?");
$update2->bind_param("di",$amount,$to_id);
$update2->execute();

/* INSERT TRANSACTION */
$insert = $conn->prepare("
INSERT INTO transactions(from_account,to_account,amount)
VALUES(?,?,?)
");

$insert->bind_param("iid",$from_id,$to_id,$amount);
$insert->execute();

$conn->commit();

$message="Transfer Successful.";

$balance -= $amount;

}
catch(Exception $e){
$conn->rollback();
$message="Transfer Failed.";
}

}

}

}

?>

<!DOCTYPE html>
<html>
<head>

<title>Transfer Money</title>

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
display:flex;
justify-content:center;
align-items:center;
height:90vh;
}

.card{
background:white;
padding:30px;
width:400px;
border-radius:12px;
box-shadow:0 6px 18px rgba(0,0,0,0.1);
}

.card h2{
text-align:center;
margin-bottom:20px;
color:#1d3557;
}

/* ACCOUNT INFO */

.info{
background:#edf2f7;
padding:15px;
border-radius:8px;
margin-bottom:20px;
}

/* FORM */

input{
width:100%;
padding:10px;
margin-bottom:15px;
border:1px solid #ccc;
border-radius:6px;
}

/* BUTTON */

button{
width:100%;
padding:12px;
background:linear-gradient(135deg,#118ab2,#06d6a0);
color:white;
border:none;
border-radius:6px;
font-size:16px;
cursor:pointer;
}

button:hover{
opacity:0.9;
}

/* MESSAGE */

.success{
color:green;
margin-bottom:10px;
}

.error{
color:red;
margin-bottom:10px;
}

.back{
text-align:center;
margin-top:15px;
}

.back a{
text-decoration:none;
color:#118ab2;
}

</style>

</head>

<body>

<div class="navbar">

🏦 Banking System

<a class="logout" href="logout.php">Logout</a>

</div>

<div class="container">

<div class="sidebar">

<h3>User Menu</h3>

<a href="user_dashboard.php">Dashboard</a>

<a href="transactions.php">Transaction History</a>

<a href="transfer.php">Transfer Money</a>

</div>

<div class="main">

<div class="card">

<h2>Transfer Money</h2>

<div class="info">

<b>From Account:</b> <?= $from_no ?><br>
<b>Balance:</b> ₹<?= number_format($balance,2) ?>

</div>

<?php if($message!=""){ ?>

<div class="<?= strpos($message,'Successful')!==false?'success':'error' ?>">

<?= $message ?>

</div>

<?php } ?>

<form method="POST">

<input type="text" name="to_account" placeholder="Receiver Account Number" required>

<input type="number" step="0.01" name="amount" placeholder="Amount" required>

<button type="submit">Transfer Money</button>

</form>

<div class="back">

<a href="user_dashboard.php">← Back to Dashboard</a>

</div>

</div>

</div>

</div>

</body>
</html>