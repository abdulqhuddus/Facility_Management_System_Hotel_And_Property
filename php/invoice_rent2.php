<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require "../config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
$sql = "SELECT * FROM rents WHERE invoice_id='".$_GET['invoice_id']."'";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">


<title>Payment Invoice - Saleel Real Estate</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    	body{margin-top:20px;
background-color:#eee;
}

.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0 solid rgba(0,0,0,.125);
    border-radius: 1rem;
}
    </style>
</head>
<body>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
<div class="container" style="max-width:100%">
<div class="row">
<div class="col-lg-12">
<div class="card">
<div class="card-body">
<div class="invoice-title">
<h4 class="float-end font-size-15" style="margin-top:60px;">Invoice #<?php echo $_GET['invoice_id']; ?> <span class="badge bg-success font-size-12 ms-2">Paid</span></h4>
<div class="mb-4">
<img src="../images/logo_2.png" style="width:230px;">
</div>
<!-- <div class="text-muted">
<p class="mb-1">Rumaila, Ajman</p>
<p><i class="uil uil-phone me-1"></i> 067457451</p>
</div> -->
</div>
<hr class="my-4">
<div class="row">
    <div class="col-sm-6">
    <div class="text-muted">
    <h5 class="font-size-16 mb-3">Billed To:</h5>
    <h5 class="font-size-15 mb-2"><?php echo $row['name']; ?></h5>
    <p class="mb-1"><?php echo $row['mobile']; ?></p>
    <p class="mb-1">Apartment: <?php echo $row['apt_id']; ?></p>
    </div>
    </div>

    <div class="col-sm-6">
    <div class="text-muted text-sm-end">
    <div>
    <h5 class="font-size-15 mb-1">Payment Mode</h5>
    <p><?php echo $row['pay_mode']; ?></p>
    </div>
    <div class="mt-4">
    <h5 class="font-size-15 mb-1">Invoice Date:</h5>
    <p><?php echo $row['date']; ?></p>
    </div>
    <div class="mt-4">
    <h5 class="font-size-15 mb-1">Agent:</h5>
    <p><?php echo $row['updated_by']; ?></p>
    </div>
    </div>
</div>

</div>

<div class="py-2">
<h5 class="font-size-15">Bill Summary</h5>
<div class="table-responsive">
<table class="table align-middle table-nowrap table-centered mb-0">
<thead>
<tr>
<th style="width: 70px;">No.</th>
<th>Item</th>
<th>Price</th>
<th>Quantity</th>
<th class="text-end" style="width: 120px;">Total</th>
</tr>
</thead>
<tbody>
<tr>
<th scope="row">01</th>
<td>
<div>
<h5 class="text-truncate font-size-14 mb-1"><?php echo $_SESSION['maintenance_type']; ?> Rent <?php echo $row['type']; ?></h5>
</div>
</td>
<td>AED <?php echo $row['amount']; ?></td>
<td>1</td>
<td class="text-end">AED <?php echo $row['amount']; ?></td>
</tr>

<tr>
<th scope="row" colspan="4" class="text-end">Sub Total</th>
<td class="text-end">AED <?php echo $row['amount']; ?></td>
</tr>

<tr>
<th scope="row" colspan="4" class="border-0 text-end">
VAT</th>
<td class="border-0 text-end">AED 0</td>
</tr>

<tr>
<th scope="row" colspan="4" class="border-0 text-end">Total</th>
<td class="border-0 text-end" style="width: 200px;"><h4 class="m-0 fw-semibold">AED <?php echo $row['amount']; ?>.00</h4></td>
</tr>

</tbody>
</table>
</div>
<div class="d-print-none mt-4">
<div class="float-end" style="display:flex;">
<a href="javascript:window.print()" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
<!-- <a href="../apartment.php" class="btn btn-primary w-md">Home</a> -->
<form action="../apartment.php" method="POST">
    <input type="hidden" name="door" id="door" value="<?php echo $row['apt_id']; ?>" style="background:orange;color:black;border:2px solid white;">
    <button type="submit" name="submit" style="background: #0d73f8;color: white;border: 0px;line-height: 1.5;font-size: 1rem;height: 38px;margin: 0px;border-radius: 5px;margin-left: 4px;;">Back</button>
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>