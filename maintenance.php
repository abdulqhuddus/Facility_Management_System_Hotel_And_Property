<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require "config.php";
include "css/header-en.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
//echo $_SESSION['name'];
$sql1 = "SELECT * FROM `maintenance` WHERE type='Fire' AND status='1'";
$query1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($query1);

$sql2 = "SELECT * FROM `maintenance` WHERE type='CCTV' AND status='1'";
$query2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($query2);

$sql3 = "SELECT * FROM `maintenance` WHERE type='Lift' AND status='1'";
$query3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($query3);

$sql4 = "SELECT * FROM `maintenance` WHERE type='Cleaning' AND status='1'";
$query4 = mysqli_query($conn, $sql4);
$row4 = mysqli_fetch_assoc($query4);

$sql11 = "SELECT sum(amount) as sum_amount FROM `maintenance` WHERE type='Fire' OR type='Fire_Balance'";
$query11 = mysqli_query($conn, $sql11);
$row11 = mysqli_fetch_assoc($query11);

$sql22 = "SELECT sum(amount) as sum_amount FROM `maintenance` WHERE type='CCTV' OR type='CCTV_Balance'";
$query22 = mysqli_query($conn, $sql22);
$row22 = mysqli_fetch_assoc($query22);

$sql33 = "SELECT sum(amount) as sum_amount FROM `maintenance` WHERE type='Lift' OR type='Lift_Balance'";
$query33 = mysqli_query($conn, $sql33);
$row33 = mysqli_fetch_assoc($query33);

$sql44 = "SELECT sum(amount) as sum_amount FROM `maintenance` WHERE type='Cleaning' OR type='Cleaning_Balance'";
$query44 = mysqli_query($conn, $sql44);
$row44 = mysqli_fetch_assoc($query44);

$sql55 = "SELECT sum(balance) as sum_balance FROM `maintenance`";
$query55 = mysqli_query($conn, $sql55);
$row55 = mysqli_fetch_assoc($query55);

$total_amount = $row11['sum_amount'] + $row22['sum_amount'] + $row33['sum_amount'] + $row44['sum_amount'];
$total_amount_format = number_format($total_amount);
$total_balance = $row55['sum_balance'];
$total_balance_format = $total_balance;
$total_paid = $total_amount - $total_balance;
$total_paid_format = $total_paid;

date_default_timezone_set('Asia/Dubai');
//echo $_SESSION['name'];
$date = isset($_POST['date']) ? $_POST['date'] : null;
$type = isset($_POST['type']) ? $_POST['type'] : null;
$sql = "SELECT * FROM maintenance_data";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-01-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql .= " WHERE date between '$date1' and '$date_till_midnight'";
}

$sql .= "AND status='Completed' ORDER BY id DESC";
$rs_transactions = mysqli_query($conn, $sql);
?>
<style>
th i {
    background: grey;
    color: white;
    width: 30px;
    text-align: center;
    padding: 8px 2px 8px 2px;
    font-size: 15px;
}
.btn-primary {
    width: 35%;
}
input, select, option{
    height: 50px !important;
}
/* HIDE RADIO */
[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* IMAGE STYLES */
[type=radio] + img {
  cursor: pointer;
}

/* CHECKED STYLES */
[type=radio]:checked + img {
  outline: 2px solid #f00;
}
.btn{
    border-radius:0px;
    line-height: 1.5;
    border-radius: 5px;
    border: 1px solid lightgrey;
}
.modal-dialog{
    width:100%;
    padding:20px;
    margin:0px;
}
.form-group{
    width:19%;
    margin:7px;
    min-width: 200px;
}
.form-group input{
    margin-bottom:10px;
}
.dropdown{
    width:100% !important;
}
.dropdown-menu{
    min-width: 285px !important;
    transform:none !important;
}
</style>
<!-- Preview PDF before upload -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<!-- Select Search -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css" />
<div class="t-head">Maintenance Contracts</div>
<div class="card-body">
    <form action="" method="post" id="donation_form">
        <div class="row" style="margin-top:30px;">
        <div class="col-md-3">
        </div>
            <div class="col-md-3">
                <div class="form-group" style="width:100%;">
                    <label for="date">Date</label>
                    <input type="text" name="date" id="date" class="form-control" <?php
                    if (isset($_POST['date'])) {
                        echo "value=" . $_POST['date'];
                    } ?>>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group" style="width:100%;">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control"
                            onchange="this.form.submit()">
                        <option value="">Select Option</option>
                        <option <?php
                        if (isset($_POST['type']) && $_POST['type'] == "Fire") {
                            echo "selected";
                        }
                        ?>>Fire Contract
                        </option>
                        <option <?php
                        if (isset($_POST['type']) && $_POST['type'] == "CCTV") {
                            echo "selected";
                        }
                        ?>>CCTV Contract
                        </option>
                        <option <?php
                        if (isset($_POST['type']) && $_POST['type'] == "Lift") {
                            echo "selected";
                        }
                        ?>>Lift Contract
                        </option>
                        <option <?php
                        if (isset($_POST['type']) && $_POST['type'] == "Cleaning") {
                            echo "selected";
                        }
                        ?>>Cleaning Contract
                        </option>
                    </select>
                </div>
            </div>
        </div>
            <div class="col-md-12" style="display: flex;margin: auto;justify-content: center;margin-top:20px;">
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Fire Contracts
                    <p style="font-size:23px;color:white;"><?php echo $row11['sum_amount'];
                    ?> AED</p>
                    </span>
                </div>                                            
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">CCTV Contracts
                    <p style="font-size:23px;color:white;"><?php echo $row22['sum_amount'];
                    ?> AED</p>
                    </span>
                </div>
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Lift Contracts
                    <p style="font-size:23px;color:white;"><?php echo $row33['sum_amount'];
                    ?> AED</p>
                    </span>
                </div>
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Cleaning Contracts
                    <p style="font-size:23px;color:white;"><?php echo $row44['sum_amount'];
                    ?> AED</p>
                    </span>
                </div>
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: black;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Total Contracts
                    <p style="font-size:23px;color:white;"><?php echo $total_amount_format;
                    ?> AED</p>
                    </span>
                </div>
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: black;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Total Payments
                    <p style="font-size:23px;color:white;"><?php echo $total_paid_format;
                    ?> AED</p>
                    </span>
                </div>
                <div class="col-md-2" style="color:lightgrey;height:100px;width:250px;margin-left: 2%;text-align: center;background: black;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                    <span style="color:white;font-weight:bold">Balance Payments
                    <p style="font-size:23px;color:white;"><?php echo $total_balance_format;
                    ?> AED</p>
                    </span>
                </div>
            </div>
    </form>
</div>

<div class="t-service">
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Fire Contract</p>
    <p style="font-size:100px;font-weight:bold"><i class="fa-solid fa-fire-extinguisher"></i></p>
        <?php
        if(empty($row1['status'])){
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fire_new"> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fire_balance" disabled>    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row1['balance']); ?> AED</p>
            </button>
        <?php
        }
        else{
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fire_new" disabled> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#fire_balance">    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row1['balance']); ?> AED</p>
            </button>
        <?php
        }
        ?>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">CCTV Contract</p>
    <p style="font-size:100px;font-weight:bold"><i class="fa-solid fa-video"></i></p>
        <?php
        if(($row2['status'] === '0') || (empty($row2['status']))){
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cctv_new"> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cctv_balance" disabled>    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row2['balance']); ?> AED</p>
            </button>
        <?php
        }
        else{
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cctv_new" disabled> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cctv_balance">    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row2['balance']); ?> AED</p>
            </button>
        <?php
        }
        ?>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Lift Contract</p>
    <p style="font-size:100px;font-weight:bold"><i class="fa-solid fa-elevator"></i></p>
        <?php
        if(($row3['status'] === '0') || (empty($row3['status']))){
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lift_new"> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lift_balance" disabled>    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row3['balance']); ?> AED</p>
            </button>
        <?php
        }
        else{
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lift_new" disabled> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#lift_balance">    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row3['balance']); ?> AED</p>
            </button>
        <?php
        }
        ?>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Cleaning Contract</p>
    <p style="font-size:100px;font-weight:bold"><i class="fa-solid fa-broom"></i></p>
        <?php
        if(($row4['status'] === '0') || (empty($row4['status']))){
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleaning_new"> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleaning_balance" disabled>    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row4['balance']); ?> AED</p>
            </button>
        <?php
        }
        else{
        ?>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleaning_new" disabled> 
                <p style="color:white;">New Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold">+</p>
            </button>
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#cleaning_balance">    
                <p style="color:white;">balance Payment</p>
                <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row4['balance']); ?> AED</p>
            </button>
        <?php
        }
        ?>
    </div>
</div>

<div class="t-head">Transaction Details</div>
<div class="t_table" style="width: 95% !important;max-width:95% !important;" >
    <table id="example" class="display nowrap" style="width:100%;text-align:center;">
        <thead>
            <tr>
                <th style="text-align:center !important;">Type</th>
                <th style="text-align:center !important;">Supplier</th>
                <th style="text-align:center !important;">Amount</th>
                <th style="text-align:center !important;">Pay Mode</th>
                <th style="text-align:center !important;">Cheque No</th>
                <th style="text-align:center !important;">Date</th>
                <th style="text-align:center !important;">Invoice #</th>
                <th style="text-align:center !important;">Updated By</th>
                <th style="text-align:center !important;">Attachment</th>
                <th style="text-align:center !important;">Download</th>
                <th style="text-align:center !important;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            while($row_transactions = mysqli_fetch_assoc($rs_transactions)){
            ?>
            <tr>
                <td style="font-weight:bold"><?php echo $row_transactions['type']; ?></td>
                <td><?php echo $row_transactions['supplier_name']; ?></td>
                <td>
                    <div style="display:flex;width:200px;flex-wrap: wrap;">
                        <?php
                        $get_balance = "SELECT amount FROM maintenance_data WHERE id='".$row_transactions['id']."' LIMIT 1";
                        $get_balance_rs = mysqli_query($conn, $get_balance);
                        while($get_balance_rw = mysqli_fetch_assoc($get_balance_rs)){
                        ?>
                        <div style="width:50%;">Payment</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $get_balance_rw['amount']; ?></div>
                        <?php
                        }
                        ?>
                        <?php
                        $get_total = "SELECT * FROM maintenance WHERE invoice_id='".$row_transactions['contract_number']."'";
                        $get_total_rs = mysqli_query($conn, $get_total);
                        $get_total_rw = mysqli_fetch_assoc($get_total_rs);
                        ?>
                        <div style="width:50%;">Balance</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $get_total_rw['balance']; ?></div>
                        <div style="width:50%;font-weight:bold;margin: auto;">Total</div><div style="width:50%;background:grey;padding:3px;color:white;font-weight:bold;"><?php echo $get_total_rw['amount']; ?> AED</div>
                    </div>
                </td>
                <td><?php echo $row_transactions['pay_mode']; ?></td>
                <td>
                    <?php
                    if($row_transactions['cheque_number']!=''){
                    ?>
                    <div style="display:flex;width:200px;flex-wrap: wrap;">
                        <div style="width:50%;">Number</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['cheque_number']; ?></div>
                        <div style="width:50%;">Date</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['cheque_date']; ?></div>
                        <div style="width:50%;">Bank</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['cheque_bank']; ?></div>
                        <div style="width:50%;font-weight:bold;margin: auto;">Amount</div><div style="width:50%;background:royalblue;padding:3px;color:white;font-weight:bold;"><?php echo $row_transactions['amount']; ?> AED</div>
                    <?php
                    }else{
                    ?>
                    <div style="display:flex;width:200px;flex-wrap: wrap;">
                        <div style="width:50%;font-weight:bold;margin: auto;">Amount</div><div style="width:50%;background:royalblue;padding:3px;color:white;font-weight:bold;"><?php echo $row_transactions['amount']; ?> AED</div>
                    <?php 
                    }
                    ?>
                    </div>
                </td>
                <td><?php echo $row_transactions['date']; ?></td>
                <td><?php echo $row_transactions['contract_number']; ?></td>
                <td><?php echo $row_transactions['updated_by']; ?></td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['file_name']; ?><br>
                    File Size: <?php 
                    echo floor($row_transactions['file_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                </td>
                <td>
                    <a href="php/download.php?file_id=<?php echo $row_transactions['id']; ?>&type=Maintenance" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
                    <a href="../attachments/<?php echo $row_transactions['file_name']; ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                </td>
                <?php 
                if($row_transactions['status'] === "Completed")
                {echo "<td style='background:#0c0c7e;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                elseif($row_transactions['status'] === "Failed")
                {echo "<td style='background:red;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                ?></td>
            </tr>
            <?php
            }
            ?>
        </tbody>
        </tfoot>
    </table>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- Fire System New -->
<div class="modal" id="fire_new">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Fire System</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/fire_new_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From" id="contract_from" name="contract_from" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract To</label>
                            <input type="date" class="form-control" placeholder="Contract To" id="contract_to" name="contract_to" value=""
                            required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value=""
                            required>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="fire_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="fire_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="fire_cheque()">
                            <input type="radio" id="fire_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="fire_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="fire_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="fire_cheque_amount" name="cheque_amount" value=""
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="fire_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="fire_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="fire_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="fire_cash_amount" name="cash_amount" value=""
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="fire" name="myfile" required>
                            <canvas id="fire_new_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Fire"
                                required>
                        <!-- <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php //echo $row['contract_number']; ?>"
                                required> -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- New Fire System -->

<!-- Balance Fire System -->
<div class="modal" id="fire_balance">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Fire System - Balance Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/fire_balance_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value="<?php echo $row1['supplier_name']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value="<?php echo $row1['contact_person']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value="<?php echo $row1['contact_number']; ?>"
                            readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value="<?php echo $row1['amount']; ?>"
                            readonly>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="fire_bal_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="fire_bal_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="fire_bal_cheque()">
                            <input type="radio" id="fire_bal_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="fire_bal_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="fire_bal_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="fire_bal_cheque_amount" name="cheque_amount" max="<?php echo $row1['balance']; ?>" value="<?php echo $row1['balance']; ?>"
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="fire_bal_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="fire_bal_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="fire_bal_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="fire_bal_cash_amount" name="cash_amount" max="<?php echo $row1['balance']; ?>" value="<?php echo $row1['balance']; ?>"
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="fire_balance" name="myfile" required>
                            <canvas id="fire_balance_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Fire_Balance"
                                required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php echo $row1['invoice_id']; ?>"
                                required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Balance Fire System -->


<!-- CCTV New -->
<div class="modal" id="cctv_new">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">CCTV System</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/cctv_new_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From" id="contract_from" name="contract_from" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract To</label>
                            <input type="date" class="form-control" placeholder="Contract To" id="contract_to" name="contract_to" value=""
                            required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value=""
                            required>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="cctv_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="cctv_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="cctv_cheque()">
                            <input type="radio" id="cctv_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="cctv_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="cctv_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="cctv_cheque_amount" name="cheque_amount" value=""
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="cctv_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="cctv_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="cctv_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="cctv_cash_amount" name="cash_amount" value=""
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="cctv" name="myfile" required>
                            <canvas id="cctv_new_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="CCTV"
                                required>
                        <!-- <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php //echo $row['contract_number']; ?>"
                                required> -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- New CCTV System -->

<!-- Balance CCTV System -->
<div class="modal" id="cctv_balance">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">cctv System - Balance Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/cctv_balance_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value="<?php echo $row2['supplier_name']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value="<?php echo $row2['contact_person']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value="<?php echo $row2['contact_number']; ?>"
                            readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value="<?php echo $row2['amount']; ?>"
                            readonly>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="cctv_bal_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="cctv_bal_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="cctv_bal_cheque()">
                            <input type="radio" id="cctv_bal_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="cctv_bal_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="cctv_bal_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="cctv_bal_cheque_amount" name="cheque_amount" max="<?php echo $row2['balance']; ?>" value="<?php echo $row2['balance']; ?>"
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="cctv_bal_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="cctv_bal_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="cctv_bal_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="cctv_bal_cash_amount" name="cash_amount" max="<?php echo $row2['balance']; ?>" value="<?php echo $row2['balance']; ?>"
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="cctv_balance" name="myfile" required>
                            <canvas id="cctv_balance_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="CCTV_Balance"
                                required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php echo $row2['invoice_id']; ?>"
                                required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Balance CCTV System -->


<!-- Lift System New -->
<div class="modal" id="lift_new">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">lift System</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/lift_new_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From" id="contract_from" name="contract_from" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract To</label>
                            <input type="date" class="form-control" placeholder="Contract To" id="contract_to" name="contract_to" value=""
                            required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value=""
                            required>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="lift_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="lift_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="lift_cheque()">
                            <input type="radio" id="lift_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="lift_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="lift_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="lift_cheque_amount" name="cheque_amount" value=""
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="lift_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="lift_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="lift_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="lift_cash_amount" name="cash_amount" value=""
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="lift" name="myfile" required>
                            <canvas id="lift_new_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Lift"
                                required>
                        <!-- <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php //echo $row['contract_number']; ?>"
                                required> -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- New Lift System -->

<!-- Balance Lift System -->
<div class="modal" id="lift_balance">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">lift System - Balance Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/lift_balance_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value="<?php echo $row3['supplier_name']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value="<?php echo $row3['contact_person']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value="<?php echo $row3['contact_number']; ?>"
                            readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value="<?php echo $row3['amount']; ?>"
                            readonly>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="lift_bal_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="lift_bal_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="lift_bal_cheque()">
                            <input type="radio" id="lift_bal_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="lift_bal_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="lift_bal_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="lift_bal_cheque_amount" name="cheque_amount" max="<?php echo $row3['balance']; ?>" value="<?php echo $row3['balance']; ?>"
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="lift_bal_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="lift_bal_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="lift_bal_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="lift_bal_cash_amount" name="cash_amount" max="<?php echo $row3['balance']; ?>" value="<?php echo $row3['balance']; ?>"
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="lift_balance" name="myfile" required>
                            <canvas id="lift_balance_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Lift_Balance"
                                required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php echo $row3['invoice_id']; ?>"
                                required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Balance Lift System -->


<!-- Cleaning System New -->
<div class="modal" id="cleaning_new">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">cleaning System</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/cleaning_new_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value=""
                                required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From" id="contract_from" name="contract_from" value=""
                            required>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract To</label>
                            <input type="date" class="form-control" placeholder="Contract To" id="contract_to" name="contract_to" value=""
                            required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value=""
                            required>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="cleaning_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="cleaning_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="cleaning_cheque()">
                            <input type="radio" id="cleaning_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="cleaning_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="cleaning_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="cleaning_cheque_amount" name="cheque_amount" value=""
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="cleaning_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="cleaning_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="cleaning_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="cleaning_cash_amount" name="cash_amount" value=""
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="cleaning" name="myfile" required>
                            <canvas id="cleaning_new_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Cleaning"
                                required>
                        <!-- <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php //echo $row['contract_number']; ?>"
                                required> -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- New Cleaning System -->

<!-- Balance Cleaning System -->
<div class="modal" id="cleaning_balance">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">cleaning System - Balance Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/cleaning_balance_process.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" placeholder="supplier name" id="supplier_name" name="supplier_name" value="<?php echo $row4['supplier_name']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Person</label>
                            <input type="text" class="form-control" placeholder="contact person" id="contact_person" name="contact_person" value="<?php echo $row4['contact_person']; ?>"
                            readonly>
                        </div>
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contact Number</label>
                            <input type="text" class="form-control" placeholder="contact number" id="contact_number" name="contact_number" value="<?php echo $row4['contact_number']; ?>"
                            readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;">
                            <label for="name">Contract Amount</label>
                            <input type="number" class="form-control" placeholder="Contract Amount" id="contract_amount" name="contract_amount" value="<?php echo $row4['amount']; ?>"
                            readonly>
                        </div>                        
                    </div>  
                    <div style="display:flex;justify-content:center;"> 
                    <!-- <label for="name">Select Payment Type</label>                  -->
                        <div class="cheque_button" onclick="cleaning_bal_cash()" style="margin-bottom: 10px;">
                            <input type="radio" id="cleaning_bal_cash" name="cash" value="cash" checked />
                            <label class="btn btn-default">Cash</label>
                        </div>
                        <div class="cheque_button" onclick="cleaning_bal_cheque()">
                            <input type="radio" id="cleaning_bal_cheque" name="cheque" value="cheque" />
                            <label class="btn btn-default">Cheque</label>
                        </div>
                    </div>
                        <div id="cleaning_bal_cheque_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                            <div style="display:flex;justify-content:center;">
                                    <label for="name" style="padding: 5px;">Cheque Number</label>
                                    <input type="number" class="form-control" placeholder="Cheque Number" id="cleaning_bal_cheque_number" name="cheque_number" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Cheque Amount</label>
                                    <input type="number" class="form-control" placeholder="Cheque Amount" id="cleaning_bal_cheque_amount" name="cheque_amount" max="<?php echo $row4['balance']; ?>" value="<?php echo $row4['balance']; ?>"
                                    required>
                                    <label for="name" style="padding: 5px;">Cheque Date</label>
                                    <input type="date" class="form-control" placeholder="Cheque Date" id="cleaning_bal_cheque_date" name="cheque_date" value=""
                                        required>
                                    <label for="name" style="padding: 5px;">Bank Name</label>
                                    <input type="text" class="form-control" placeholder="Bank Name" id="cleaning_bal_cheque_bank" name="cheque_bank" value=""
                                        required>
                            </div>
                        </div>
                        <div id="cleaning_bal_cash_selected" class="form-group" style="width:100%;padding: 0px;border-radius: 5px;margin:0px;">
                            <div class="form-group" style="margin: auto;margin-top: 10px;text-align: center;">
                                    <label for="name">Payment Amount</label>
                                    <input type="number" class="form-control" placeholder="Payment Amount" id="cleaning_bal_cash_amount" name="cash_amount" max="<?php echo $row4['balance']; ?>" value="<?php echo $row4['balance']; ?>"
                                    required>
                            </div>  
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin:5px;width:80%">
                            <label for="name">Notes</label>
                            <input type="text" class="form-control" placeholder="Notes" id="notes" name="notes" value="">
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                        <label for="name">Attachment [Invoice & Receipt Voucher & Cheque]</label>
                            <input type="file" id="cleaning_balance" name="myfile" required>
                            <canvas id="cleaning_balance_viewer" style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="Cleaning_Balance"
                                required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number" value="<?php echo $row4['invoice_id']; ?>"
                                required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Balance Cleaning System -->



<!-- PDF Preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/js/bootstrap-select.js"></script>
<!-- Form Validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    $('#example').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'excel', 'print'
        ]
    });
});
</script>
<script>
$('#date').daterangepicker();

<?php
if (isset($date)) {
    echo " $('#date').daterangepicker({ startDate: '$date[0]', endDate: '$date[1]' });";
}
?>
$('#date').on('apply.daterangepicker', function (ev, picker) {

    $('#donation_form').submit();
});
</script>
<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#fire").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#fire_new_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#fire_balance").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#fire_balance_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cctv").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#cctv_new_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cctv_balance").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#cctv_balance_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#lift").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#lift_new_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#lift_balance").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#lift_balance_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cleaning").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#cleaning_new_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cleaning_balance").on("change", function(e){
	var file = e.target.files[0]
	if(file.type == "application/pdf"){
		var fileReader = new FileReader();  
		fileReader.onload = function() {
			var pdfData = new Uint8Array(this.result);
			// Using DocumentInitParameters object to load binary data.
			var loadingTask = pdfjsLib.getDocument({data: pdfData});
			loadingTask.promise.then(function(pdf) {
			  console.log('PDF loaded');
			  
			  // Fetch the first page
			  var pageNumber = 1;
			  pdf.getPage(pageNumber).then(function(page) {
				console.log('Page loaded');
				
				var scale = 1.5;
				var viewport = page.getViewport({scale: scale});

				// Prepare canvas using PDF page dimensions
				var canvas = $("#cleaning_balance_viewer")[0];
				var context = canvas.getContext('2d');
				canvas.height = viewport.height;
				canvas.width = viewport.width;

				// Render PDF page into canvas context
				var renderContext = {
				  canvasContext: context,
				  viewport: viewport
				};
				var renderTask = page.render(renderContext);
				renderTask.promise.then(function () {
				  console.log('Page rendered');
				});
			  });
			}, function (reason) {
			  // PDF loading error
			  console.error(reason);
			});
		};
		fileReader.readAsArrayBuffer(file);
	}
});
</script>

<script>
function fire_cash() {
    document.getElementById("fire_cash_selected").style.display = 'block';
    document.getElementById("fire_cheque_selected").style.display = 'none';
    document.getElementById("fire_cheque_number").required = false;
    document.getElementById("fire_cheque_amount").required = false;
    document.getElementById("fire_cheque_date").required = false;
    document.getElementById("fire_cheque_bank").required = false;
    document.getElementById("fire_ash_amount").required = true;
    document.getElementById("fire_cash").checked = true;
    document.getElementById("fire_cheque").checked = false;
}
</script>
<script>
function fire_cheque() {
    document.getElementById("fire_cash_selected").style.display = 'none';
    document.getElementById("fire_cheque_selected").style.display = 'block';
    document.getElementById("fire_cheque_number").required = true;
    document.getElementById("fire_cheque_amount").required = true;
    document.getElementById("fire_cheque_date").required = true;
    document.getElementById("fire_cheque_bank").required = true;
    document.getElementById("fire_cash_amount").required = false;
    document.getElementById("fire_cheque").checked = true;
    document.getElementById("fire_cash").checked = false;
}
</script>
<script>
function fire_bal_cash() {
    document.getElementById("fire_bal_cash_selected").style.display = 'block';
    document.getElementById("fire_bal_cheque_selected").style.display = 'none';
    document.getElementById("fire_bal_cheque_number").required = false;
    document.getElementById("fire_bal_cheque_amount").required = false;
    document.getElementById("fire_bal_cheque_date").required = false;
    document.getElementById("fire_bal_cheque_bank").required = false;
    document.getElementById("fire_bal_ash_amount").required = true;
    document.getElementById("fire_bal_cash").checked = true;
    document.getElementById("fire_bal_cheque").checked = false;
}
</script>
<script>
function fire_bal_cheque() {
    document.getElementById("fire_bal_cash_selected").style.display = 'none';
    document.getElementById("fire_bal_cheque_selected").style.display = 'block';
    document.getElementById("fire_bal_cheque_number").required = true;
    document.getElementById("fire_bal_cheque_amount").required = true;
    document.getElementById("fire_bal_cheque_date").required = true;
    document.getElementById("fire_bal_cheque_bank").required = true;
    document.getElementById("fire_bal_cash_amount").required = false;
    document.getElementById("fire_bal_cheque").checked = true;
    document.getElementById("fire_bal_cash").checked = false;
}
</script>



<script>
function cctv_cash() {
    document.getElementById("cctv_cash_selected").style.display = 'block';
    document.getElementById("cctv_cheque_selected").style.display = 'none';
    document.getElementById("cctv_cheque_number").required = false;
    document.getElementById("cctv_cheque_amount").required = false;
    document.getElementById("cctv_cheque_date").required = false;
    document.getElementById("cctv_cheque_bank").required = false;
    document.getElementById("cctv_ash_amount").required = true;
    document.getElementById("cctv_cash").checked = true;
    document.getElementById("cctv_cheque").checked = false;
}
</script>
<script>
function cctv_cheque() {
    document.getElementById("cctv_cash_selected").style.display = 'none';
    document.getElementById("cctv_cheque_selected").style.display = 'block';
    document.getElementById("cctv_cheque_number").required = true;
    document.getElementById("cctv_cheque_amount").required = true;
    document.getElementById("cctv_cheque_date").required = true;
    document.getElementById("cctv_cheque_bank").required = true;
    document.getElementById("cctv_cash_amount").required = false;
    document.getElementById("cctv_cheque").checked = true;
    document.getElementById("cctv_cash").checked = false;
}
</script>
<script>
function cctv_bal_cash() {
    document.getElementById("cctv_bal_cash_selected").style.display = 'block';
    document.getElementById("cctv_bal_cheque_selected").style.display = 'none';
    document.getElementById("cctv_bal_cheque_number").required = false;
    document.getElementById("cctv_bal_cheque_amount").required = false;
    document.getElementById("cctv_bal_cheque_date").required = false;
    document.getElementById("cctv_bal_cheque_bank").required = false;
    document.getElementById("cctv_bal_ash_amount").required = true;
    document.getElementById("cctv_bal_cash").checked = true;
    document.getElementById("cctv_bal_cheque").checked = false;
}
</script>
<script>
function cctv_bal_cheque() {
    document.getElementById("cctv_bal_cash_selected").style.display = 'none';
    document.getElementById("cctv_bal_cheque_selected").style.display = 'block';
    document.getElementById("cctv_bal_cheque_number").required = true;
    document.getElementById("cctv_bal_cheque_amount").required = true;
    document.getElementById("cctv_bal_cheque_date").required = true;
    document.getElementById("cctv_bal_cheque_bank").required = true;
    document.getElementById("cctv_bal_cash_amount").required = false;
    document.getElementById("cctv_bal_cheque").checked = true;
    document.getElementById("cctv_bal_cash").checked = false;
}
</script>


<script>
function lift_cash() {
    document.getElementById("lift_cash_selected").style.display = 'block';
    document.getElementById("lift_cheque_selected").style.display = 'none';
    document.getElementById("lift_cheque_number").required = false;
    document.getElementById("lift_cheque_amount").required = false;
    document.getElementById("lift_cheque_date").required = false;
    document.getElementById("lift_cheque_bank").required = false;
    document.getElementById("lift_ash_amount").required = true;
    document.getElementById("lift_cash").checked = true;
    document.getElementById("lift_cheque").checked = false;
}
</script>
<script>
function lift_cheque() {
    document.getElementById("lift_cash_selected").style.display = 'none';
    document.getElementById("lift_cheque_selected").style.display = 'block';
    document.getElementById("lift_cheque_number").required = true;
    document.getElementById("lift_cheque_amount").required = true;
    document.getElementById("lift_cheque_date").required = true;
    document.getElementById("lift_cheque_bank").required = true;
    document.getElementById("lift_cash_amount").required = false;
    document.getElementById("lift_cheque").checked = true;
    document.getElementById("lift_cash").checked = false;
}
</script>
<script>
function lift_bal_cash() {
    document.getElementById("lift_bal_cash_selected").style.display = 'block';
    document.getElementById("lift_bal_cheque_selected").style.display = 'none';
    document.getElementById("lift_bal_cheque_number").required = false;
    document.getElementById("lift_bal_cheque_amount").required = false;
    document.getElementById("lift_bal_cheque_date").required = false;
    document.getElementById("lift_bal_cheque_bank").required = false;
    document.getElementById("lift_bal_ash_amount").required = true;
    document.getElementById("lift_bal_cash").checked = true;
    document.getElementById("lift_bal_cheque").checked = false;
}
</script>
<script>
function lift_bal_cheque() {
    document.getElementById("lift_bal_cash_selected").style.display = 'none';
    document.getElementById("lift_bal_cheque_selected").style.display = 'block';
    document.getElementById("lift_bal_cheque_number").required = true;
    document.getElementById("lift_bal_cheque_amount").required = true;
    document.getElementById("lift_bal_cheque_date").required = true;
    document.getElementById("lift_bal_cheque_bank").required = true;
    document.getElementById("lift_bal_cash_amount").required = false;
    document.getElementById("lift_bal_cheque").checked = true;
    document.getElementById("lift_bal_cash").checked = false;
}
</script>


<script>
function cleaning_cash() {
    document.getElementById("cleaning_cash_selected").style.display = 'block';
    document.getElementById("cleaning_cheque_selected").style.display = 'none';
    document.getElementById("cleaning_cheque_number").required = false;
    document.getElementById("cleaning_cheque_amount").required = false;
    document.getElementById("cleaning_cheque_date").required = false;
    document.getElementById("cleaning_cheque_bank").required = false;
    document.getElementById("cleaning_ash_amount").required = true;
    document.getElementById("cleaning_cash").checked = true;
    document.getElementById("cleaning_cheque").checked = false;
}
</script>
<script>
function cleaning_cheque() {
    document.getElementById("cleaning_cash_selected").style.display = 'none';
    document.getElementById("cleaning_cheque_selected").style.display = 'block';
    document.getElementById("cleaning_cheque_number").required = true;
    document.getElementById("cleaning_cheque_amount").required = true;
    document.getElementById("cleaning_cheque_date").required = true;
    document.getElementById("cleaning_cheque_bank").required = true;
    document.getElementById("cleaning_cash_amount").required = false;
    document.getElementById("cleaning_cheque").checked = true;
    document.getElementById("cleaning_cash").checked = false;
}
</script>
<script>
function cleaning_bal_cash() {
    document.getElementById("cleaning_bal_cash_selected").style.display = 'block';
    document.getElementById("cleaning_bal_cheque_selected").style.display = 'none';
    document.getElementById("cleaning_bal_cheque_number").required = false;
    document.getElementById("cleaning_bal_cheque_amount").required = false;
    document.getElementById("cleaning_bal_cheque_date").required = false;
    document.getElementById("cleaning_bal_cheque_bank").required = false;
    document.getElementById("cleaning_bal_ash_amount").required = true;
    document.getElementById("cleaning_bal_cash").checked = true;
    document.getElementById("cleaning_bal_cheque").checked = false;
}
</script>
<script>
function cleaning_bal_cheque() {
    document.getElementById("cleaning_bal_cash_selected").style.display = 'none';
    document.getElementById("cleaning_bal_cheque_selected").style.display = 'block';
    document.getElementById("cleaning_bal_cheque_number").required = true;
    document.getElementById("cleaning_bal_cheque_amount").required = true;
    document.getElementById("cleaning_bal_cheque_date").required = true;
    document.getElementById("cleaning_bal_cheque_bank").required = true;
    document.getElementById("cleaning_bal_cash_amount").required = false;
    document.getElementById("cleaning_bal_cheque").checked = true;
    document.getElementById("cleaning_bal_cash").checked = false;
}
</script>