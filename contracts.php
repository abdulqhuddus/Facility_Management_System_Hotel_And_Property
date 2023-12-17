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
date_default_timezone_set('Asia/Dubai');
//echo $_SESSION['name'];
$date = isset($_POST['date']) ? $_POST['date'] : null;
$type = isset($_POST['pay_mode']) ? $_POST['pay_mode'] : null;
$sql = "SELECT * FROM contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql .= " AND pay_mode = '$type'";
}

$sql .= "AND status='Completed' ORDER BY id DESC";
$rs_transactions = mysqli_query($conn, $sql);

date_default_timezone_set('Asia/Dubai');
$sql2 = "SELECT sum(amount + if(cheque_2_status = 'Paid',cheque_2_amount,0) + if(cheque_3_status = 'Paid',cheque_3_amount,0) + if(cheque_4_status = 'Paid',cheque_4_amount,0)+ if(cheque_5_status = 'Paid',cheque_5_amount,0)+ if(cheque_6_status = 'Paid',cheque_6_amount,0)) as Rents FROM contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql2 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql2 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql2 .= " AND type = '$type'";
}
$sql2 .= "AND status='Completed'";
$rs_rents = mysqli_query($conn, $sql2);
$row_rents = mysqli_fetch_assoc($rs_rents);
$total_rents = number_format($row_rents['Rents']);


$sql4 = "SELECT sum(security) as 'Security' from contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql4 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql4 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql4 .= " AND type = '$type'";
}
$sql4 .= "AND status='Completed'";
$rs_security = mysqli_query($conn, $sql4);
$row_security = mysqli_fetch_assoc($rs_security);
$total_security = number_format($row_security['Security']);


$sql5 = "SELECT sum(insurance) as 'Insurance' from contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql5 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql5 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql5 .= " AND type = '$type'";
}
$sql5 .= "AND status='Completed'";
$rs_insurance = mysqli_query($conn, $sql5);
$row_insurance = mysqli_fetch_assoc($rs_insurance);
$total_insurance = number_format($row_insurance['Insurance']);


$sql6 = "SELECT sum(service_charge) as 'Service' from contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql6 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql6 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql6 .= " AND type = '$type'";
}
$sql6 .= "AND status='Completed'";
$rs_service = mysqli_query($conn, $sql6);
$row_service = mysqli_fetch_assoc($rs_service);
$total_service = number_format($row_service['Service']);


$sql8 = "SELECT sum(amount) as Parking FROM parkings";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql8 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql8 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql8 .= " AND type = '$type'";
}
$sql8 .= "AND status='Completed'";
$rs_parking = mysqli_query($conn, $sql8);
$row_parking = mysqli_fetch_assoc($rs_parking);
$total_parking = number_format($row_parking['Parking']);


$sql9 = "SELECT sum(amount) as Repairs FROM repairs";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql9 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql9 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql9 .= " AND type = '$type'";
}
$sql9 .= "AND status='Completed'";
$rs_repairs = mysqli_query($conn, $sql9);
$row_repairs = mysqli_fetch_assoc($rs_repairs);
$total_repairs = number_format($row_repairs['Parking']);


$sql7 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(cheque_5_amount != '',cheque_5_amount,0))+(if(cheque_6_amount != '',cheque_6_amount,0)) + insurance + security) as Total FROM contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql7 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql7 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql7 .= " AND type = '$type'";
}
$sql7 .= "AND status='Completed'";
$rs_total = mysqli_query($conn, $sql7);
$row_total = mysqli_fetch_assoc($rs_total);
$total_all = $row_total['Total'] + $row_parking['Parking'];
$total_all_format = number_format($total_all);


$sql3 = "SELECT sum(amount+(if(cheque_2_status = 'Paid',cheque_2_amount,0))+(if(cheque_3_status = 'Paid',cheque_3_amount,0))+(if(cheque_4_status = 'Paid',cheque_4_amount,0))+(if(cheque_5_status = 'Paid',cheque_5_amount,0))+(if(cheque_6_status = 'Paid',cheque_6_amount,0)) + security + insurance) as Received from contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql3 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-m-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql3 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql3 .= " AND type = '$type'";
}
$sql3 .= "AND status='Completed'";
$rs_received = mysqli_query($conn, $sql3);
$row_received = mysqli_fetch_assoc($rs_received);
$total_received = $row_received['Received'] + $row_parking['Parking'];
$total_received_format = number_format($total_received);


$balance = $total_all - $total_received;
$total_balance = number_format($balance);

?>
<style>
th i {
    background: grey;
    color: white;
    width: 30px;
    text-align: center;
    padding: 10px 2px 10px 2px;
    font-size: 17px;
}
.btn-primary {
    width: 100%;
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
}
.daterangepicker .drp-buttons .btn{
    margin-left: 8px;
    font-size: 12px;
    font-weight: bold;
    padding: 4px 8px;
    margin-top: 10px;
    width: 100px;
}
</style>
<div class="t-head">Tenancy Contracts</div>
<div class="card-body">
                                <form action="" method="post" id="donation_form">
                                    <div class="row" style="margin-top:30px;">
                                    <div class="col-md-3">
                                    </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="date">Date</label>
                                                <input type="text" name="date" id="date" class="form-control" <?php
                                                if (isset($_POST['date'])) {
                                                    echo "value=" . $_POST['date'];
                                                } ?>>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="pay_mode">Type</label>
                                                <select name="pay_mode" id="pay_mode" class="form-control"
                                                        onchange="this.form.submit()">
                                                    <option value="">Select Option</option>
                                                    <option <?php
                                                    if (isset($_POST['pay_mode']) && $_POST['pay_mode'] == "Cash") {
                                                        echo "selected";
                                                    }
                                                    ?>>Cash
                                                    </option>
                                                    <option <?php
                                                    if (isset($_POST['pay_mode']) && $_POST['pay_mode'] == "Card") {
                                                        echo "selected";
                                                    }
                                                    ?>>Card
                                                    </option>
                                                    <option <?php
                                                    if (isset($_POST['pay_mode']) && $_POST['pay_mode'] == "Cheque") {
                                                        echo "selected";
                                                    }
                                                    ?>>Cheque
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                        <div class="col-md-12" style="display: flex;margin: auto;justify-content: center;margin-top:20px;">
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Rents
                                                <p style="font-size:23px;color:white;"><?php echo $total_rents;
                                                ?> AED</p>
                                                </span>
                                            </div>                                            
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Security Deposit
                                                <p style="font-size:23px;color:white;"><?php echo $total_security;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Insurance
                                                <p style="font-size:23px;color:white;"><?php echo $total_insurance;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Parking
                                                <p style="font-size:23px;color:white;"><?php echo $total_parking;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Total
                                                <p style="font-size:23px;color:white;"><?php echo $total_all_format;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Received
                                                <p style="font-size:23px;color:white;"><?php echo $total_received_format;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Balance
                                                <p style="font-size:23px;color:white;"><?php echo $total_balance;
                                                ?> AED</p>
                                                </span>
                                            </div>                                            
                                        </div>
                                        <div class="col-md-12" style="display: flex;margin: auto;justify-content: center;margin-top:20px;">
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Admin Fee
                                                <p style="font-size:23px;color:white;"><?php echo $total_service;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                            <div class="col-md-2" style="color:lightgrey;height:100px;width:200px;margin-left: 2%;text-align: center;background: grey;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:22px">
                                                <span style="color:white;font-weight:bold">Repairs
                                                <p style="font-size:23px;color:white;"><?php echo $total_repairs;
                                                ?> AED</p>
                                                </span>
                                            </div>
                                        </div>
                                </form>
</div>
<div class="t_table" style="width:100% !important; max-width:100% !important;">
    <table id="example" class="display nowrap" style="width:100%;text-align:center;">
        <thead>
            <tr>
                <th style="text-align:center !important;">Status</th>
                <!-- <th style="text-align:center !important;">Id</th> -->
                <th style="text-align:center !important;">Type</th>
                <th style="text-align:center !important;">Apartment</th>
                <th style="text-align:center !important;">Customer</th>
                <th style="text-align:center !important;">Total Amount</th>
                <th style="text-align:center !important;">Pay Mode</th>
                <th style="text-align:center !important;">Date</th>
                <th style="text-align:center !important;">Invoice #</th>
                <th style="text-align:center !important;">Updated By</th>
                <th style="text-align:center !important;">Cheque 2</th>
                <th style="text-align:center !important;">Cheque 3</th>
                <th style="text-align:center !important;">Cheque 4</th>
                <th style="text-align:center !important;">Emirates ID</th>
                <th style="text-align:center !important;">Cheque 5</th>
                <th style="text-align:center !important;">Cheque 6</th>                
            </tr>
        </thead>
        <tbody>
            <?php            
            while($row_transactions = mysqli_fetch_assoc($rs_transactions)){
            ?>
            <tr>
                <?php 
                if($row_transactions['status'] === "Completed")
                {echo "<td style='background:#0c0c7e;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                elseif($row_transactions['status'] === "Failed")
                {echo "<td style='background:red;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                ?>
                </td>
                <!-- <td><?php //echo $row_transactions['id']; ?></td> -->
                <td><?php echo $row_transactions['contract_type']; ?></td>
                <td><?php echo $row_transactions['apt_id']; ?></td>
                <td><?php echo $row_transactions['name']; ?></td>
                <td>
                <?php 
                $total_amount = $row_transactions['amount'] + $row_transactions['cheque_2_amount'] + $row_transactions['cheque_3_amount'] + $row_transactions['cheque_4_amount'] + $row_transactions['cheque_5_amount'] + $row_transactions['cheque_6_amount'];
                $cheques_amount = $row_transactions['cheque_2_amount'] + $row_transactions['cheque_3_amount'] + $row_transactions['cheque_4_amount'] + $row_transactions['cheque_5_amount'] + $row_transactions['cheque_6_amount'];
                if($row_transactions['cheque_2_status'] === "Paid"){$check_2 = $row_transactions['cheque_2_amount'];}else{$check_2 = '0';}
                if($row_transactions['cheque_3_status'] === "Paid"){$check_3 = $row_transactions['cheque_3_amount'];}else{$check_3 = '0';}
                if($row_transactions['cheque_4_status'] === "Paid"){$check_4 = $row_transactions['cheque_4_amount'];}else{$check_4 = '0';}
                if($row_transactions['cheque_5_status'] === "Paid"){$check_5 = $row_transactions['cheque_5_amount'];}else{$check_5 = '0';}
                if($row_transactions['cheque_6_status'] === "Paid"){$check_6 = $row_transactions['cheque_6_amount'];}else{$check_6 = '0';}

                $total_paid_amount = $row_transactions['amount'] + $check_2 + $check_3 + $check_4 + $check_5 + $check_6;
                $total_rent_amount = $row_transactions['amount']+$row_transactions['cheque_2_amount']+$row_transactions['cheque_3_amount']+$row_transactions['cheque_4_amount']+$row_transactions['cheque_5_amount']+$row_transactions['cheque_6_amount'];
                $total_balance_amount = $total_rent_amount-$total_paid_amount;
                ?>
                    <div style="display:flex;width:200px;flex-wrap: wrap;">
                        <div style="width:50%;">First Payment</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['amount']; ?></div>
                        <div style="width:50%;">Cheques</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $cheques_amount; ?></div>
                        <div style="width:50%;">Deposit</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['security']; ?></div>
                        <div style="width:50%;">Insurance</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['insurance']; ?></div>
                        <div style="width:50%;">Admin Fee</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['service_charge']; ?></div>
                        <div style="width:50%;font-weight:bold;border-top:1px solid lightgrey">Contract</div><div style="width:50%;background:grey;padding:3px;color:white;font-weight:bold;"><?php echo $total_amount; ?> AED</div>
                        <div style="width:50%;font-weight:bold;border-top:1px solid lightgrey">Received</div><div style="width:50%;background:royalblue;padding:3px;color:white;font-weight:bold;"><?php echo $total_paid_amount; ?> AED</div>
                        <div style="width:50%;font-weight:bold;border-top:1px solid lightgrey;border-bottom:1px solid lightgrey">Balance</div><div style="width:50%;background:red;padding:3px;color:white;font-weight:bold;"><?php echo $total_balance_amount; ?> AED</div>
                    </div>
                </td>
                <td><?php echo $row_transactions['pay_mode']; ?></td>
                <td><?php echo $row_transactions['date']; ?></td>
                <td><?php echo $row_transactions['invoice_id']; ?><br>
                <a href="php/invoice_contract2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target='blank'  style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i class="fa-solid fa-receipt"></i> Statement</a>
                </td>
                <td><?php echo $row_transactions['updated_by']; ?></td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_2_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_2_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_2_bank']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_2_date'];?> <br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_2_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_2&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['cheque_2_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                </td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_3_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_3_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_3_bank']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_3_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_3_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_3&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['cheque_3_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                </td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_4_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_4_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_4_bank']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_4_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_4_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_4&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['cheque_4_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                </td>
                <td style="text-align: left;">
                    <br><br>
                    <?php 
                    echo $row_transactions['eid']; ?><br>
                    <?php 
                    echo $row_transactions['eid_expiry']; ?><br>
                    File Size: <?php 
                    echo floor($row_transactions['eid_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=eid&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['eid_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                </td>
                <td style="text-align: left;">
                <?php
                if($row_transactions['cheque_5_name'] === NULL){}else{
                    echo $row_transactions['cheque_5_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_5_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_5_bank']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_5_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_5_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_5&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['cheque_5_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                <?php
                }
                ?>
                </td>
                <td style="text-align: left;">
                <?php
                if($row_transactions['cheque_6_name'] === NULL){}else{
                    echo $row_transactions['cheque_6_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_6_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_6_bank']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_6_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_6_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_6&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a><br><br>
                    <a href="../attachments/<?php echo $row_transactions['cheque_6_name'] ?>" target='blank' style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-eye"></i> View</a>
                <?php
                }
                ?>
                </td>
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
<?php include "css/footer-en.php";
    header("location: dashboard.php");
?>