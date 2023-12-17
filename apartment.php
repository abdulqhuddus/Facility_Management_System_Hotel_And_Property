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
if(!empty($_GET['apartment_id'])){$apartment_id = $_GET['apartment_id'];}else{$apartment_id = $_POST['door'];}
//echo $_SESSION['name'];
$sql = "SELECT * FROM `apartments` WHERE door=".$apartment_id."";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);
if (isset($_POST['submit'])) {

$get_eid = "SELECT * FROM contracts WHERE invoice_id='".$row['contract_number']."'";
$eid_result = mysqli_query($conn, $get_eid);
$eid_row = mysqli_fetch_assoc($eid_result);

$get_last = "SELECT * FROM transactions WHERE invoice_id='".$row['contract_number']."'";
$last_result = mysqli_query($conn, $get_last);
$last_row = mysqli_fetch_assoc($last_result);

$get_park = "SELECT * FROM parking_id WHERE apt_id='".$apartment_id."' AND status=1";
$park_result = mysqli_query($conn, $get_park);
$park_row = mysqli_fetch_assoc($park_result);
$park_row_num = mysqli_num_rows($park_result);

$get_parking = "SELECT * FROM parking_id WHERE apt_id=".$apartment_id." and status=1";
$parking_result = mysqli_query($conn, $get_parking);
$parking_row = mysqli_fetch_assoc($parking_result);

?>
<!-- Preview PDF before upload -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
<!-- Select Search -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/css/bootstrap-select.css" />

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
    width: 100%;
}

input,
select,
option {
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
[type=radio]+img {
    cursor: pointer;
}

/* CHECKED STYLES */
[type=radio]:checked+img {
    outline: 2px solid #f00;
}

.btn {
    border-radius: 0px;
    line-height: 2.5;
    border-radius: 5px;
    border: 1px solid lightgrey;
}

.modal-dialog {
    width: 100%;
    padding: 20px;
    margin: 0px;
}

.form-group {
    width: 19%;
    margin: 7px;
    min-width: 200px;
}

.form-group input {
    margin-bottom: 10px;
}

.dropdown {
    width: 100% !important;
}

.dropdown-menu {
    min-width: 285px !important;
    transform: none !important;
}
</style>
<div class="t-head">Apartment Details</div>
<div class="t-home" style="max-width:1400px !important;">
    <div class="t-home-1" style="max-width:400px;padding-right:5px;">
        <?php if($row['status']=== "1"){
        ?>
        <i class="fa-solid fa-door-open" style="color:#0c0c7e"></i>
        <p style="background:#0c0c7e">F<?php echo $row['floor']; ?> Door <?php echo $row['door']; ?></p>
        <?php } else{
        ?>
        <i class="fa-solid fa-door-closed"></i>
        <p>F<?php echo $row['floor']; ?> Door <?php echo $row['door']; ?> <?php echo $eid_row['contract_type']; ?></p>
        <?php
    } ?>
        <div class="services">
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal0" <?php if($row['status']=== "1"){
                echo "disabled";
                }else{
                    }
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i class="fa-solid fa-plus"
                            style="margin-bottom:10px;"></i></span><br>New Contract
                </button>
            </div>
        </div>
        <div class="services">
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal1" <?php if($row['status']=== "0"){
                    echo "disabled";
                }else{
                    if(($eid_row['total_cheques']=== "4") && ($eid_row['cheque_4_status']=== "Paid")){
                    echo "disabled";
                    }elseif(($eid_row['total_cheques']=== "6") && ($eid_row['cheque_6_status']=== "Paid")){
                        echo "disabled";
                    }
                }
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i class="fa-solid fa-credit-card"
                            style="margin-bottom:10px;"></i></span><br>Rent Payment
                </button>
            </div>
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2" <?php if($row['status']=== "0"){
                echo "disabled";
                }else{
                    if($eid_row['cheque_4_status'] === "Unpaid"){
                        echo "disabled";
                        }else{
                            if($eid_row['total_cheques'] === "4"){
                                if($eid_row['cheque_4_status'] === "Unpaid"){
                                    echo "disabled";
                                }else{}
                            }elseif($eid_row['total_cheques'] === "6"){
                                if($eid_row['cheque_6_status'] === "Unpaid"){
                                    echo "disabled";
                                }else{}
                            }
                        }
                    }  
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i
                            class="fa-solid fa-file-circle-plus" style="margin-bottom:10px;"></i></span><br>Renewal
                </button>
            </div>
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal3" <?php if($row['status']=== "0"){
                echo "disabled";
                }else{
                    date_default_timezone_set('Asia/Dubai');
                    $next = $row['next_pay_date']." 00:00:01";
                    $now = date("Y-m-d H:i:s");
                    $starttime1 = strtotime($now);
                    $starttime2 = strtotime($next);
                    $result_secs = $starttime2 - $starttime1;
                    $result_days = $result_secs / 86400;
                    if($result_days < 60){
                        echo "disabled";
                    }else{}
                }  
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i
                            class="fa-solid fa-file-circle-xmark" style="margin-bottom:10px;"></i></span><br>Close
                    Contract
                </button>
            </div>
        </div>
        <div class="services">
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal4">
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i
                            class="fa-solid fa-screwdriver-wrench"
                            style="margin-bottom:10px;"></i></span><br>Maintenance
                </button>
            </div>
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal5" <?php if($row['status']=== "0"){
                echo "disabled";
                }else{
                    if($park_row_num > 5 ){
                        echo "disabled";
                        }else{}
                    }  
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i class="fa-solid fa-square-parking"
                            style="margin-bottom:10px;"></i></span><br>Parking
                </button>
            </div>
            <div class="service-inner">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal6" <?php if($row['status']=== "0"){
                echo "disabled";
                }else{
                    }  
                ?>>
                    <span style="font-size:30px;font-weight:bold;line-height: 1;"><i class="fa-solid fa-pen-to-square"
                            style="margin-bottom:10px;"></i></span><br>Edit Information
                </button>
            </div>
        </div>
        <div class="services">
            <div class="service-inner">
                <p style="font-size:13px;">Last Updated on <?php echo $row['updated_at']; ?> by
                    <?php echo $row['updated_by']; ?></p>
            </div>
        </div>
    </div>
    <?php
    if($row['status']==='1'){
    ?>
    <div class="t-home-2" style="max-width:400px;">
        <p
            style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;max-width:400px;border-right: 5px solid;font-size: 25px;">
            Tenant Details</p>
        <table style="margin-top:0px;width:100%;">
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-person"></i> Full Name</th>
                <td><?php echo $row['name']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-mobile-screen-button"></i> Mobile</th>
                <td><?php echo $row['mobile']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-square-envelope"></i> Email</th>
                <td><?php echo $row['email']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-square-envelope"></i> Nationality</th>
                <td><?php 
                if($row["nationality"] == 1){echo "UAE";}
                elseif($row["nationality"] == 2){echo "Oman";}               
                elseif($row["nationality"] == 3){echo "Qatar";}
                elseif($row["nationality"] == 4){echo "Saudi Arabia";}
                elseif($row["nationality"] == 5){echo "Kuwait";}
                elseif($row["nationality"] == 6){echo "Syria";}
                elseif($row["nationality"] == 7){echo "Egypt";}
                elseif($row["nationality"] == 8){echo "Yemen";}
                elseif($row["nationality"] == 9){echo "India";}
                elseif($row["nationality"] == 10){echo "Lebanon";}
                elseif($row["nationality"] == 11){echo "Jordan";}
                elseif($row["nationality"] == 12){echo "Iraq";}
                elseif($row["nationality"] == 13){echo "Palestine";}
                elseif($row["nationality"] == 14){echo "Kosovo";}
                elseif($row["nationality"] == 15){echo "Bosnia";}
                elseif($row["nationality"] == 16){echo "Eritrea";}
                elseif($row["nationality"] == 17){echo "Sudan";}
                elseif($row["nationality"] == 19){echo "Bangladesh";}
                elseif($row["nationality"] == 21){echo "Senegal";}
                elseif($row["nationality"] == 22){echo "Somalia";}
                elseif($row["nationality"] == 24){echo "Kyrgyzstan";}
                elseif($row["nationality"] == 25){echo "Niger";}
                elseif($row["nationality"] == 26){echo "Pakistan";}
                elseif($row["nationality"] == 27){echo "Morocco";}
                elseif($row["nationality"] == 28){echo "Comoros Islands";}
                elseif($row["nationality"] == 29){echo "Indonesia";}
                elseif($row["nationality"] == 30){echo "Ethiopia";}
                elseif($row["nationality"] == 32){echo "Iran";}
                elseif($row["nationality"] == 33){echo "The Philippines";}
                elseif($row["nationality"] == 34){echo "Bahrain";}
                elseif($row["nationality"] == 35){echo "Tunisia";}
                elseif($row["nationality"] == 36){echo "Algeria";}
                elseif($row["nationality"] == 43){echo "Russia";}
                elseif($row["nationality"] == 42){echo "Sri Lanka";}
                elseif($row["nationality"] == 37){echo "Afghanistan";}
                elseif($row["nationality"] == 38){echo "Mauritania";}
                elseif($row["nationality"] == 39){echo "Kenya";}
                elseif($row["nationality"] == 40){echo "Canada";}
                elseif($row["nationality"] == 41){echo "Holland";}
                elseif($row["nationality"] == 44){echo "USA";}
                elseif($row["nationality"] == 45){echo "UK";}
                elseif($row["nationality"] == 46){echo "Australia";}
                elseif($row["nationality"] == 47){echo "New Zealand";}
                elseif($row["nationality"] == 48){echo "Other Middle East Nations";}
                elseif($row["nationality"] == 49){echo "Other American Nations";}
                elseif($row["nationality"] == 50){echo "Other European Nations";}
                elseif($row["nationality"] == 52){echo "Other African Nations";}
                elseif($row["nationality"] == 53){echo "Other Asian Nations";}
                elseif($row["nationality"] == 54){echo "Other";}
                ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-file-import"></i> Start</th>
                <td><?php echo $row['contract_from']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-regular fa-file-excel"></i> End</th>
                <td><?php echo $row['contract_to']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-square-parking"></i> Parking</th>
                <td><?php echo $row['parking'];?>
                    <?php
                if ($parking_row == true) {
                ?>
                    <a href="php/download.php?type=Parking&file_id=<?php echo $parking_row['id'] ?>"
                        style="font-size:12px;font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 3px;border-radius: 5px;"><i
                            class="fa-solid fa-download"></i> Download</a>
                    <?php
                }else{}
                ?>
                </td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-bed"></i> Bedrooms</th>
                <td><?php echo $row['bedroom']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-money-check-dollar"></i> Rent</th>
                <td><?php 
                $rent_amount = $eid_row['amount'] + $eid_row['cheque_2_amount'] + $eid_row['cheque_3_amount'] + $eid_row['cheque_4_amount'] + $eid_row['cheque_5_amount'] + $eid_row['cheque_6_amount'];
                echo $col_rent = number_format($rent_amount);
                ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-user-gear"></i> Agent</th>
                <td><?php echo $row['updated_by']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-hand-holding-dollar"></i> LastPayDate
                </th>
                <td><?php echo $row['last_pay_date']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-screwdriver-wrench"></i> LastRepair
                </th>
                <td><?php echo $row['last_repair_type']; ?></td>
            </tr>
            <tr>
                <th style="border-bottom: 1px solid grey;"><i class="fa-solid fa-calendar-days"></i> RepairDate</th>
                <td><?php echo $row['last_repair_date']; ?></td>
            </tr>
            <tr>
                <th><i class="fa-solid fa-check"></i> Status</th>
                <?php if($row['status']=== "1"){
                ?>
                <td style="background: #0c0c7e;">Occupied</td>
                <?php
                }else{
                    ?>
                <td style="background: #0f9347;">Available</td>
                <?php
                    }  
                ?>
            </tr>
        </table>
    </div>
    <div class="t-home-3" style="width:auto;">
        <p
            style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;max-width:400px;font-size: 25px;">
            Tenant ID</p>
        <!-- <embed src="attachments/SAL_DOC51401_eid.pdf" width="800px" height="2100px" /> -->
        <iframe src="attachments/<?php echo $eid_row['eid_name']; ?>#toolbar=0&navpanes=0&scrollbar=0" scrolling="auto"
            style="margin-top:6px;"></iframe>
    </div>
    <?php
        if($eid_row['cheque_1_name'] != ''){
        ?>
    <div class="t-home-3" id="cheque_1_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            1</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_1_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <?php
        }
    ?>
    <div class="t-home-3" id="cheque_2_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            2</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_2_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <div class="t-home-3" id="cheque_3_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            3</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_3_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <div class="t-home-3" id="cheque_4_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            4</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_4_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <div class="t-home-3" id="cheque_5_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            5</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_5_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <div class="t-home-3" id="cheque_6_view" style="display:none;border-left: 4px solid white;">
        <p style="background: grey;color: white;margin-top: 5px;margin-bottom: 0px;padding: 0px;font-size: 25px;">Cheque
            6</p>
        <iframe src="attachments/<?php echo $eid_row['cheque_6_name']; ?>#toolbar=0&navpanes=0&scrollbar=0"
            scrolling="auto" style="margin-top:6px;"></iframe>
    </div>
    <?php
    }else{}
    ?>
</div>
<?php
if($eid_row['cheque_2_status'] === "Paid"){$check_2 = $eid_row['cheque_2_amount'];}else{$check_2 = '0';}
if($eid_row['cheque_3_status'] === "Paid"){$check_3 = $eid_row['cheque_3_amount'];}else{$check_3 = '0';}
if($eid_row['cheque_4_status'] === "Paid"){$check_4 = $eid_row['cheque_4_amount'];}else{$check_4 = '0';}
if($eid_row['cheque_5_status'] === "Paid"){$check_5 = $eid_row['cheque_5_amount'];}else{$check_5 = '0';}
if($eid_row['cheque_6_status'] === "Paid"){$check_6 = $eid_row['cheque_6_amount'];}else{$check_6 = '0';}

$total_paid_amount = $eid_row['amount'] + $check_2 + $check_3 + $check_4 + $check_5 + $check_6;
$total_rent_amount = $eid_row['amount']+$eid_row['cheque_2_amount']+$eid_row['cheque_3_amount']+$eid_row['cheque_4_amount']+$eid_row['cheque_5_amount']+$eid_row['cheque_6_amount'];
$total_balance_amount = $total_rent_amount-$total_paid_amount;
$total_balance_amount_fmt = number_format($total_balance_amount);
if($row['status'] === '1'){
?>
<div
    style="width: 90%;margin: auto;line-height:2;max-width: 1400px;display: flex;justify-content: center;max-width:1390px;margin-bottom:5px;background: <?php if($total_balance_amount > 0){echo "red";}else{echo "darkblue";} ?>;color: white;font-size: 30px;border-radius: 5px;">
    Balance Amount AED <?php echo $total_balance_amount_fmt; ?>
</div>
<?php
}else{}
    if($row['status']==='1'){
    ?>
<div style="width:90%;margin:auto;max-width:1400px;display:flex;">
    <?php
    if(!empty($eid_row['cheque_1_number'])){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 1<p style="margin-top:10px;color:white;">Paid <?php echo $eid_row['amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['date']; ?></p>
        <p><a href="php/invoice_contract2.php?invoice_id=<?php echo $eid_row['invoice_id'] ?>" target=“blank”
                style="font-weight:bold;font-weight: bold;background: #fff;color: #337ab7;padding: 5px;border-radius: 5px;line-height: 1.5;font-size:12px;"><i
                    class="fa-solid fa-receipt"></i> Statement</a>
            <a href="php/download_contract.php?type=cheque_1&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #fff;color: #337ab7;padding: 5px;border-radius: 5px;font-size:12px;"><i
                    class="fa-solid fa-download"></i> Download</a>
        </p>
    </div>
    <?php
    }
    else{
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 1<p style="margin-top:10px;color:white;">Paid <?php echo $eid_row['amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['date']; ?></p>
        <p><a href="php/invoice_contract2.php?invoice_id=<?php echo $eid_row['invoice_id'] ?>" target=“blank”
                style="font-weight:bold;font-weight: bold;background: #fff;color: #337ab7;padding: 5px;border-radius: 5px;line-height: 1.5;"><i
                    class="fa-solid fa-receipt"></i> Statement</a></p>
    </div>
    <?php
    }
    if($eid_row['cheque_2_status'] === 'Paid'){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 2<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_2_status']; ?>
            #<?php echo $eid_row['cheque_2_number']; ?> <?php echo $eid_row['cheque_2_amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['cheque_2_date']; ?></p>
        <p><button onclick="cheque_2_open()"
                style="line-height: 1;font-weight: bold;background: #fff;color: #337ab7;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_2&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_2"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-xmark" style="font-size:20px;"></i></button>
                </p>
    </div>
    <?php
    }elseif($eid_row['cheque_2_status'] === 'Returned'){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 2<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_2_status']; ?>
            #<?php echo $eid_row['cheque_2_number']; ?> <?php echo $eid_row['cheque_2_amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['cheque_2_date']; ?></p>
        <p><button onclick="cheque_2_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;color:white;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_2&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a>
                <button class="btn btn-primary" data-toggle="modal" data-target="#new_cheque_2" 
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i class="fa-solid fa-plus"></i></button></p>
    </div>
    <?php
    }else{
        date_default_timezone_set('Asia/Dubai');
        $next = $row['next_pay_date']." 00:00:01";
        $now = date("Y-m-d H:i:s");
        $starttime1 = strtotime($now);
        $starttime2 = strtotime($next);
        $result_secs = $starttime2 - $starttime1;
        $result_days = $result_secs / 86400;
        if($result_days < 0){
        ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 2<p style="margin-top:10px;color:white;font-size:14px;"><?php echo $eid_row['cheque_2_status']; ?>
            #<?php echo $eid_row['cheque_2_number']; ?> <?php echo $eid_row['cheque_2_amount']; ?> AED</p>
        <p style="color:white;">Due Date <?php echo $eid_row['cheque_2_date']; ?></p>
        <p><button onclick="cheque_2_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_2&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_2" 
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_2"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
        }elseif($result_days < 20){
        ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:orange;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 2<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_2_status']; ?>
            #<?php echo $eid_row['cheque_2_number']; ?> <?php echo $eid_row['cheque_2_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_2_date']; ?></p>
        <p><button onclick="cheque_2_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_2&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_2" 
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_2"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
        }elseif($result_days > 20){
        ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 2<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_2_status']; ?>
            #<?php echo $eid_row['cheque_2_number']; ?> <?php echo $eid_row['cheque_2_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_2_date']; ?></p>
        <p><button onclick="cheque_2_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_2&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_2" 
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_2"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
        }
    }
    ?>
    <?php
    if($eid_row['cheque_3_status'] === 'Paid'){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 3<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #fff;color: #337ab7;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a>
                    </button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark" style="font-size:20px;"></i></button>
                </p>
    </div>
    <?php
    }elseif($eid_row['cheque_3_status'] === 'Returned'){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 3<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;color:white;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#new_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i class="fa-solid fa-plus"></i></button></p>
    </div>
    <?php
    }else{
        if($eid_row['cheque_2_status'] === 'Paid'){
            date_default_timezone_set('Asia/Dubai');
            $next = $row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 3<p style="margin-top:10px;color:white;font-size:14px;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p style="color:white;">Due Date <?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_3" 
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }elseif($result_days < 20){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:orange;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 3<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }elseif($result_days > 20){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 3<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }
        }else{
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 3<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_3_status']; ?>
            #<?php echo $eid_row['cheque_3_number']; ?> <?php echo $eid_row['cheque_3_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_3_date']; ?></p>
        <p><button onclick="cheque_3_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_3&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_3"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php 
        }
    }
    ?>
    <?php
    if($eid_row['cheque_4_status'] === 'Paid'){
    ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 4<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_4_status']; ?>
            #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
        <p style="color:white;"><?php echo $eid_row['cheque_4_date']; ?></p>
        <p><button onclick="cheque_4_open()"
                style="line-height: 1;font-weight: bold;background: #fff;color: #337ab7;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark" style="font-size:20px;"></i></button>
                </p>
    </div>
    <?php
    }elseif($eid_row['cheque_4_status'] === 'Returned'){
        ?>
        <div
            style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
            Quarter 4<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_4_status']; ?>
                #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
            <p style="color:white;"><?php echo $eid_row['cheque_4_date']; ?></p>
            <p><button onclick="cheque_4_open()"
                    style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;color:white;"><i
                        class="fa-solid fa-eye"></i> View</button><a
                    href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                    style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                        class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#new_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i class="fa-solid fa-plus"></i></button></p>
        </div>
        <?php
    }else{
        if($eid_row['cheque_3_status'] === 'Paid'){
            date_default_timezone_set('Asia/Dubai');
            $next = $row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 4<p style="margin-top:10px;color:white;font-size:14px;"><?php echo $eid_row['cheque_4_status']; ?>
            #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
        <p style="color:white;">Due Date <?php echo $eid_row['cheque_4_date']; ?></p>
        <p><button onclick="cheque_4_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }elseif($result_days < 20){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:orange;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 4<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_4_status']; ?>
            #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_4_date']; ?></p>
        <p><button onclick="cheque_4_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }elseif($result_days > 20){
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 4<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_4_status']; ?>
            #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_4_date']; ?></p>
        <p><button onclick="cheque_4_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
            }
        }else{
            ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 4<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_4_status']; ?>
            #<?php echo $eid_row['cheque_4_number']; ?> <?php echo $eid_row['cheque_4_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_4_date']; ?></p>
        <p><button onclick="cheque_4_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_4&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_4"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php 
        }
    }
    ?>
    <?php
    if($eid_row['total_cheques'] === '6'){
        if($eid_row['cheque_5_status'] === 'Paid'){
        ?>
        <div
            style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
            Quarter 5<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_5_status']; ?>
                #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
            <p style="color:white;"><?php echo $eid_row['cheque_5_date']; ?></p>
            <p><button onclick="cheque_5_open()"
                    style="line-height: 1;font-weight: bold;background: #fff;color: #337ab7;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                        class="fa-solid fa-eye"></i> View</button><a
                    href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                    style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                        class="fa-solid fa-download"></i></a>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark" style="font-size:20px;"></i></button>
                </p>
        </div>
        <?php
        }elseif($eid_row['cheque_5_status'] === 'Returned'){
            ?>
            <div
                style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
                Quarter 5<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_5_status']; ?>
                    #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
                <p style="color:white;"><?php echo $eid_row['cheque_5_date']; ?></p>
                <p><button onclick="cheque_5_open()"
                        style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;color:white;"><i
                            class="fa-solid fa-eye"></i> View</button><a
                        href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                        style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                            class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#new_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i class="fa-solid fa-plus"></i></button></p>
            </div>
            <?php
        }else{
            if($eid_row['cheque_4_status'] === 'Paid'){
                date_default_timezone_set('Asia/Dubai');
                $next = $row['next_pay_date']." 00:00:01";
                $now = date("Y-m-d H:i:s");
                $starttime1 = strtotime($now);
                $starttime2 = strtotime($next);
                $result_secs = $starttime2 - $starttime1;
                $result_days = $result_secs / 86400;
                if($result_days < 0){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 5<p style="margin-top:10px;color:white;font-size:14px;"><?php echo $eid_row['cheque_5_status']; ?>
            #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
        <p style="color:white;">Due Date <?php echo $eid_row['cheque_5_date']; ?></p>
        <p><button onclick="cheque_5_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }elseif($result_days < 20){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:orange;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 5<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_5_status']; ?>
            #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_5_date']; ?></p>
        <p><button onclick="cheque_5_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }elseif($result_days > 20){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 5<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_5_status']; ?>
            #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_5_date']; ?></p>
        <p><button onclick="cheque_5_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }
            }else{
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 5<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_5_status']; ?>
            #<?php echo $eid_row['cheque_5_number']; ?> <?php echo $eid_row['cheque_5_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_5_date']; ?></p>
        <p><button onclick="cheque_5_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_5&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_5"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php 
            }
        }
    }
    ?>
    <?php
    if($eid_row['total_cheques'] === '6'){
        if($eid_row['cheque_6_status'] === 'Paid'){
        ?>
        <div
            style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:#337ab7;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
            Quarter 6<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_6_status']; ?>
                #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
            <p style="color:white;"><?php echo $eid_row['cheque_6_date']; ?></p>
            <p><button onclick="cheque_6_open()"
                    style="line-height: 1;font-weight: bold;background: #fff;color: #337ab7;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                        class="fa-solid fa-eye"></i> View</button><a
                    href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                    style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                        class="fa-solid fa-download"></i></a>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark" style="font-size:20px;"></i></button>
                </p>
        </div>
        <?php   
        }elseif($eid_row['cheque_6_status'] === 'Returned'){
            ?>
            <div
                style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
                Quarter 6<p style="margin-top:10px;font-size:14px;color:white;"><?php echo $eid_row['cheque_6_status']; ?>
                    #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
                <p style="color:white;"><?php echo $eid_row['cheque_6_date']; ?></p>
                <p><button onclick="cheque_6_open()"
                        style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;color:white;"><i
                            class="fa-solid fa-eye"></i> View</button><a
                        href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                        style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                            class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#new_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i class="fa-solid fa-plus"></i></button></p>
            </div>
            <?php   
        }else{
            if($eid_row['cheque_5_status'] === 'Paid'){
                date_default_timezone_set('Asia/Dubai');
                $next = $row['next_pay_date']." 00:00:01";
                $now = date("Y-m-d H:i:s");
                $starttime1 = strtotime($now);
                $starttime2 = strtotime($next);
                $result_secs = $starttime2 - $starttime1;
                $result_days = $result_secs / 86400;
                if($result_days < 0){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:red;border-radius:5px;line-height: 1;margin:5px;padding:5px;color:white;">
        Quarter 6<p style="margin-top:10px;color:white;font-size:14px;"><?php echo $eid_row['cheque_6_status']; ?>
            #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
        <p style="color:white;">Due Date <?php echo $eid_row['cheque_6_date']; ?></p>
        <p><button onclick="cheque_6_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }elseif($result_days < 20){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:orange;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 6<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_6_status']; ?>
            #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_6_date']; ?></p>
        <p><button onclick="cheque_6_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }elseif($result_days > 20){
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 6<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_6_status']; ?>
            #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_6_date']; ?></p>
        <p><button onclick="cheque_6_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php
                }
            }else{
                ?>
    <div
        style="width:98%;height:125px;text-align:center;font-size:18px;font-weight:bold;background:lightgrey;border-radius:5px;line-height: 1;margin:5px;padding:5px;">
        Quarter 6<p style="margin-top:10px;font-size:14px;"><?php echo $eid_row['cheque_6_status']; ?>
            #<?php echo $eid_row['cheque_6_number']; ?> <?php echo $eid_row['cheque_6_amount']; ?> AED</p>
        <p>Due Date <?php echo $eid_row['cheque_6_date']; ?></p>
        <p><button onclick="cheque_6_open()"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;margin-right: 2px;"><i
                    class="fa-solid fa-eye"></i> View</button><a
                href="php/download_contract.php?type=cheque_6&file_id=<?php echo $eid_row['id'] ?>"
                style="line-height: 1.5;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i
                    class="fa-solid fa-download"></i></a><button class="btn btn-primary" data-toggle="modal" data-target="#replace_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-arrow-right-arrow-left"></i></button><button class="btn btn-primary" data-toggle="modal" data-target="#cancel_cheque_6"
                style="line-height: 1;font-weight: bold;background: #337ab7;color: white;padding: 6.5px;border-radius: 5px;border:0px;margin-left:2px;width:25px;"><i
                    class="fa-solid fa-xmark"></i></button></p>
    </div>
    <?php 
            }
        }
    }
    ?>
</div>
<?php
    }else{}
    ?>
<div class="t-head">Transaction Details</div>
<div class="t_table">
    <table id="example" class="display nowrap" style="width:100%;text-align:center;">
        <thead>
            <tr>
                <th style="text-align:center !important;">Type</th>
                <th style="text-align:center !important;">Apartment</th>
                <th style="text-align:center !important;">Customer</th>
                <th style="text-align:center !important;">Amount</th>
                <th style="text-align:center !important;">Pay Mode</th>
                <th style="text-align:center !important;">Date</th>
                <th style="text-align:center !important;">Invoice #</th>
                <th style="text-align:center !important;">Updated By</th>
                <!-- <th style="text-align:center !important;">Attachment</th>
                <th style="text-align:center !important;">Download</th> -->
                <th style="text-align:center !important;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $transactions = "SELECT * FROM transactions WHERE apt_id='".$apartment_id."' ORDER BY date DESC";
            $rs_transactions = mysqli_query($conn, $transactions);
            while($row_transactions = mysqli_fetch_assoc($rs_transactions)){
            ?>
            <tr>
                <td style="font-weight:bold"><?php echo $row_transactions['type']; ?></td>
                <td><?php echo $row_transactions['apt_id']; ?></td>
                <td><?php echo $row_transactions['name']; ?></td>
                <td><?php echo $row_transactions['amount']; ?></td>
                <td><?php echo $row_transactions['pay_mode']; ?></td>
                <td><?php echo $row_transactions['date']; ?></td>
                <td>
                    <?php 
                if($row_transactions['type'] === "Contract"){
                ?>
                    <a href="php/invoice_contract2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>"
                        target=“blank”
                        style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                            class="fa-solid fa-receipt"></i> Print</a>
                    <?php
                }elseif($row_transactions['type'] === "cancellation"){
                ?>
                    <a href="php/invoice2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”
                        style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                            class="fa-solid fa-receipt"></i> Print</a>
                    <?php
                }elseif($row_transactions['type'] === "Parking"){
                    ?>
                        <a href="php/invoice_parking2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”
                            style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                                class="fa-solid fa-receipt"></i> Print</a>
                        <?php
                }elseif($row_transactions['type'] === "Repairs"){
                    ?>
                        <a href="php/invoice_repair2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”
                            style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                                class="fa-solid fa-receipt"></i> Print</a>
                        <?php
                }elseif($row_transactions['type'] === "Rent"){
                    ?>
                        <a href="php/invoice_rent2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”
                            style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                                class="fa-solid fa-receipt"></i> Print</a>
                        <?php
                }elseif($row_transactions['type'] === "Renewal"){
                    ?>
                        <a href="php/invoice_contract2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”
                            style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i
                                class="fa-solid fa-receipt"></i> Print</a>
                        <?php
                }
                ?>
                </td>
                <td><?php echo $row_transactions['updated_by']; ?></td>
                <!-- <td style="text-align: left;">
                    <?php 
                    // echo $row_transactions['file_name']; ?><br>
                    File Size: <?php 
                    // echo floor($row_transactions['file_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    // echo $row_transactions['download_count']; ?><br>
                </td> -->
                <!-- <td><a href="php/download.php?file_id=<?php 
                // echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a></td> -->
                <?php 
                if($row_transactions['status'] === "Completed")
                {echo "<td style='background:#0c0c7e;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                elseif($row_transactions['status'] === "Failed")
                {echo "<td style='background:red;color:white;border-bottom: 1px solid;'>".$row_transactions['status']."</td>";}
                elseif($row_transactions['status'] === "Returned")
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

<!-- New Contract -->
<div class="modal" id="myModal0" style="padding-right:0px !important">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Annual Tenancy Contract</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="contract_form" action="php/transaction.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="Name" id="customer_name"
                                name="customer_name" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="Customer Mobile"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                minlength="10" maxlength="10" required="" id="customer_mobile" name="customer_mobile"
                                value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Email</label>
                            <input type="email" class="form-control" placeholder="Email" id="email" name="email"
                                value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name" style="width:100%">Nationality</label>
                            <select type="text" placeholder="Nationality" id="nationality" name="nationality"
                                class="selectpicker" data-show-subtext="true" data-live-search="true" required
                                style="width:100%">
                                <option default>Select Nationality</option>
                                <option value="1">UAE</option>
                                <option value="2">Oman</option>
                                <option value="3">Qatar</option>
                                <option value="4">Saudi Arabia</option>
                                <option value="5">Kuwait</option>
                                <option value="6">Syria</option>
                                <option value="7">Egypt</option>
                                <option value="8">Yemen</option>
                                <option value="9">India</option>
                                <option value="10">Lebanon</option>
                                <option value="11">Jordan</option>
                                <option value="12">Iraq</option>
                                <option value="13">Palestine</option>
                                <option value="14">Kosovo</option>
                                <option value="15">Bosnia</option>
                                <option value="16">Eritrea</option>
                                <option value="17">Sudan</option>
                                <option value="19">Bangladesh</option>
                                <option value="21">Senegal</option>
                                <option value="22">Somalia</option>
                                <option value="24">Kyrgyzstan</option>
                                <option value="25">Niger</option>
                                <option value="26">Pakistan</option>
                                <option value="27">Morocco</option>
                                <option value="28">Comoros Islands</option>
                                <option value="29">Indonesia</option>
                                <option value="30">Ethiopia</option>
                                <option value="32">Iran</option>
                                <option value="33">The Philippines</option>
                                <option value="34">Bahrain</option>
                                <option value="35">Tunisia</option>
                                <option value="36">Algeria</option>
                                <option value="43">Russia</option>
                                <option value="42">Sri Lanka</option>
                                <option value="37">Afghanistan</option>
                                <option value="38">Mauritania</option>
                                <option value="39">Kenya</option>
                                <option value="40">Canada</option>
                                <option value="41">Holland</option>
                                <option value="44">USA</option>
                                <option value="45">UK</option>
                                <option value="46">Australia</option>
                                <option value="47">New Zealand</option>
                                <option value="48">Other Middle East Nations</option>
                                <option value="49">Other American Nations</option>
                                <option value="50">Other European Nations</option>
                                <option value="51">Other African Nations</option>
                                <option value="52">Other Asian Nations</option>
                                <option value="53">Other</option>
                            </select>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Bedrooms</label>
                            <input type="number" class="form-control" placeholder="Bedrooms" id="bedroom" name="bedroom"
                                value="<?php echo $row['bedroom']; ?>" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From" id="contract_from"
                                name="contract_from" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Annual Rent</label>
                            <input type="number" class="form-control" placeholder="Annual Rent" id="" name=""
                                value="<?php echo $row['default_rent']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Security Deposit</label>
                            <input type="number" class="form-control" placeholder="Security Deposit" id="security"
                                name="security" value="<?php echo $row['default_security']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Insurance</label>
                            <input type="number" class="form-control" placeholder="Insurance" id="insurance"
                                name="insurance" value="<?php echo $row['default_insurance']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Service Charge</label>
                            <input type="number" class="form-control" placeholder="Service Charge" id="service_charge"
                                name="service_charge" value="<?php echo $row['default_service']; ?>" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;margin-bottom:10px;">
                        <div class="cheque_button" onclick="cheques_4()">
                            <input type="radio" id="total_cheques_4" name="total_cheques" value="4" />
                            <label class="btn btn-default" for="total_cheques_4">4 Cheque</label>
                        </div>
                        <div class="cheque_button" onclick="cheques_6()">
                            <input type="radio" id="total_cheques_6" name="total_cheques" value="6" />
                            <label class="btn btn-default" for="total_cheques_6">6 Cheque</label>
                        </div>
                        <div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <?php 
                            $default_first_rent = "4";
                            $quarter_1 = ($row['default_rent'] / $default_first_rent);
                            $quarter_1_fmt = number_format($quarter_1); 
                            ?>
                            <label for="name">Quarter 1</label><br>
                            <div class="cheque_button" onclick="cash1()" style="margin-bottom: 10px;">
                                <input type="radio" id="cash1" name="cash1" value="" />
                                <label class="btn btn-default">Cash</label>
                            </div>
                            <div class="cheque_button" onclick="cheque1()">
                                <input type="radio" id="cheque1" name="cheque1" value="" />
                                <label class="btn btn-default">Cheque</label>
                            </div>
                            <!-- <input type="hidden" class="form-control" placeholder="amount" id="amount" name="amount" value="<?php  //echo $quarter_1; ?>"> -->
                            <!-- <input type="text" class="form-control" id="" value="<?php  //echo $quarter_1_fmt; ?> AED" disabled style="height: 380px !important;text-align: center;font-size: 40px;background: #eee;"> -->
                            <input type="text" class="form-control" id="cash_1_amount" name="cash_1_amount"
                                placeholder="0 AED" value=""
                                style="height: 380px !important;text-align: center;font-size: 40px;background: #eee;">
                            <div id="cheque_1" class="form-group"
                                style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                                <label for="name">Quarter 1 Cheque Number</label>
                                <input type="number" class="form-control" placeholder="Cheque Number"
                                    id="cheque_1_number" name="cheque_1_number" value="" required>
                                <label for="name">Quarter 1 Cheque Amount</label>
                                <input type="number" class="form-control" placeholder="Cheque Amount"
                                    id="cheque_1_amount" name="cheque_1_amount" value="" required>
                                <label for="name">Quarter 1 Cheque Date</label>
                                <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_1_date"
                                    name="cheque_1_date" value="" required>
                                <label for="name">Quarter 1 Bank Name</label>
                                <input type="text" class="form-control" placeholder="Bank Name" id="cheque_1_bank"
                                    name="cheque_1_bank" value="" required>
                                <label for="name">Quarter 1 Cheque File</label>
                                <input type="file" id="cheque_1_file" name="cheque_1_file" required
                                    style="height: 22px !important;">
                                <canvas id="pdfViewer1"
                                    style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                            </div>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 2 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number" id="cheque_2_number"
                                name="cheque_2_number" value="" required>
                            <label for="name">Quarter 2 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="cheque_2_amount"
                                name="cheque_2_amount" value="" required>
                            <label for="name">Quarter 2 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_2_date"
                                name="cheque_2_date" value="" required>
                            <label for="name">Quarter 2 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_2_bank"
                                name="cheque_2_bank" value="" required>
                            <label for="name">Quarter 2 Cheque File</label>
                            <input type="file" id="cheque_2_file" name="cheque_2_file" required
                                style="height: 22px !important;">
                            <canvas id="pdfViewer2"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 3 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number" id="cheque_3_number"
                                name="cheque_3_number" value="" required>
                            <label for="name">Quarter 3 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="cheque_3_amount"
                                name="cheque_3_amount" value="" required>
                            <label for="name">Quarter 3 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_3_date"
                                name="cheque_3_date" value="" required>
                            <label for="name">Quarter 3 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_3_bank"
                                name="cheque_3_bank" value="" required>
                            <label for="name">Quarter 3 Cheque File</label>
                            <input type="file" id="cheque_3_file" name="cheque_3_file" required
                                style="height: 22px !important;">
                            <canvas id="pdfViewer3"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 4 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number" id="cheque_4_number"
                                name="cheque_4_number" value="" required>
                            <label for="name">Quarter 4 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="cheque_4_amount"
                                name="cheque_4_amount" value="" required>
                            <label for="name">Quarter 4 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_4_date"
                                name="cheque_4_date" value="" required>
                            <label for="name">Quarter 4 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_4_bank"
                                name="cheque_4_bank" value="" required>
                            <label for="name">Quarter 4 Cheque File</label>
                            <input type="file" id="cheque_4_file" name="cheque_4_file" required
                                style="height: 22px !important;">
                            <canvas id="pdfViewer4"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div id="cheque_5" class="form-group"
                            style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;display:none">
                            <label for="name">Quarter 5 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number" id="cheque_5_number"
                                name="cheque_5_number" value="" required>
                            <label for="name">Quarter 5 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="cheque_5_amount"
                                name="cheque_5_amount" value="" required>
                            <label for="name">Quarter 5 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_5_date"
                                name="cheque_5_date" value="" required>
                            <label for="name">Quarter 5 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_5_bank"
                                name="cheque_5_bank" value="" required>
                            <label for="name">Quarter 5 Cheque File</label>
                            <input type="file" id="cheque_5_file" name="cheque_5_file" required
                                style="height: 22px !important;">
                            <canvas id="pdfViewer5"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div id="cheque_6" class="form-group"
                            style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;display:none">
                            <label for="name">Quarter 6 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number" id="cheque_6_number"
                                name="cheque_6_number" value="" required>
                            <label for="name">Quarter 6 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="cheque_6_amount"
                                name="cheque_6_amount" value="" required>
                            <label for="name">Quarter 6 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_6_date"
                                name="cheque_6_date" value="" required>
                            <label for="name">Quarter 6 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_6_bank"
                                name="cheque_6_bank" value="" required>
                            <label for="name">Quarter 6 Cheque File</label>
                            <input type="file" id="cheque_6_file" name="cheque_6_file" required
                                style="height: 22px !important;">
                            <canvas id="pdfViewer6"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Emirates ID</label>
                            <input type="text" class="form-control" placeholder="Emirates ID"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                minlength="15" maxlength="15" required="" id="eid" name="eid" value="" required>
                            <label for="name">EID Expiry</label>
                            <input type="date" class="form-control" placeholder="Emirates ID Expiry" id="eid_expiry"
                                name="eid_expiry" value="" required>
                            <label for="name">Emirates ID File</label>
                            <input type="file" id="eid_file" name="eid_file" required style="height: 22px !important;">
                            <canvas id="eid_file_viewer"
                                style="width: auto;height: 185px;max-width: 220px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <!-- <div class="form-group" style="width: 25%;margin: auto;text-align: center;font-size:20px;font-weight:bold;color: black;">
                            <label for="name">TOTAL INVOICE AMOUNT</label>
                        </div> -->
                        <?php
                        // $total_amount = $quarter_1 + $row['default_insurance']+ $row['default_security']+ $row['default_service'];
                        // $total_amount_fmt = number_format($total_amount);
                        ?>
                        <!-- <div class="form-group" style="width:100%">
                            <p style="background:#337ab7;font-weight:bold;font-size:40px;text-align:center;color:black;border-radius:5px;"><?php echo $total_amount_fmt; ?> AED</p>
                        </div> -->
                    </div>
                    <label>
                        <input type="hidden" name="pay_mode" value="Cash" checked>
                        <!-- <img src="images/cash.png" alt="Option 1" style="width:150px;"> -->
                    </label>

                    <!-- <label>
                        <input type="radio" name="pay_mode" value="Card">
                        <img src="images/card.png" alt="Option 2" style="width:150px;">
                        </label>

                        <label>
                        <input type="radio" name="pay_mode" value="Cheque">
                        <img src="images/cheque.png" alt="Option 3" style="width:150px;">
                        </label> -->

                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="type" name="type" value="Contract" required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button"
                                style="width: 300px;background: #0c0c7e;color: white;height: 50px;border-radius: 5px;font-size: 20px;line-height: 1;">Submit</button>
                        </center>
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

<!-- Rent Payment -->
<div class="modal" id="myModal1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Rent Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/transaction.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="quarter">Rent Quarter</label>
                            <?php 
                             if(($eid_row['cheque_2_status'] === 'Unpaid') || ($eid_row['cheque_2_status'] === 'Returned')){$quarter = "quarter_2"; $cheque_payment = $eid_row['cheque_2_amount'];}
                             else{
                                if(($eid_row['cheque_3_status'] === 'Unpaid') || ($eid_row['cheque_3_status'] === 'Unpaid')){$quarter = "quarter_3"; $cheque_payment = $eid_row['cheque_3_amount'];}
                                else{
                                    if(($eid_row['cheque_4_status'] === 'Unpaid') || ($eid_row['cheque_4_status'] === 'Unpaid')){$quarter = "quarter_4"; $cheque_payment = $eid_row['cheque_4_amount'];}
                                    else{
                                        if(($eid_row['cheque_5_status'] === 'Unpaid') || ($eid_row['cheque_5_status'] === 'Unpaid')){$quarter = "quarter_5"; $cheque_payment = $eid_row['cheque_5_amount'];}
                                        else{
                                            if(($eid_row['cheque_6_status'] === 'Unpaid') || ($eid_row['cheque_6_status'] === 'Unpaid')){$quarter = "quarter_6"; $cheque_payment = $eid_row['cheque_6_amount'];}
                                            else{}
                                        }
                                    }
                                }
                             }                             
                             ?>
                            <input type="text" class="form-control" placeholder="Quarter" id="quarter" name="quarter"
                                value="<?php echo $quarter; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <!-- <input type="number" class="form-control" placeholder="customer_name" id="amount" name="amount" value="<?php echo ($row['rent'] / 4); ?>" readonly> -->
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $cheque_payment; ?>" readonly>
                        </div>
                    </div>
                    <label>
                        <input type="radio" name="pay_mode" value="Cash" checked>
                        <img src="images/cash.png" alt="Option 1" style="width:150px;">
                    </label>

                    <label>
                        <input type="radio" name="pay_mode" value="Card">
                        <img src="images/card.png" alt="Option 2" style="width:150px;">
                    </label>

                    <label>
                        <input type="radio" name="pay_mode" value="Cheque">
                        <img src="images/cheque.png" alt="Option 3" style="width:150px;">
                    </label>

                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque Deposit OR Cash Receipt]</label>
                        <input type="file" id="rent_file" name="myfile" required style="margin:auto;">
                        <canvas id="rent_file_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="Rent" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
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

<!-- Renewal -->
<div class="modal" id="myModal2" style="padding-right:0px !important">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tenancy Contract Renewal</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="contract_form" action="php/transaction.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="Name" id="renewal_name"
                                name="renewal_name" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="Customer Mobile"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                minlength="10" maxlength="10" required="" id="renewal_mobile" name="renewal_mobile"
                                value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Email</label>
                            <input type="email" class="form-control" placeholder="Email" id="renewal_email"
                                name="renewal_email" value="<?php echo $row['email']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name" style="width:100%">Nationality</label>
                            <input type="text" class="form-control" placeholder="Nationality" id="renewal_nationality"
                                name="renewal_nationality" value="<?php echo $row['nationality']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Bedrooms</label>
                            <input type="number" class="form-control" placeholder="Bedrooms" id="renewal_bedroom"
                                name="renewal_bedroom" value="<?php echo $row['bedroom']; ?>" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <?php
                        $renewal_from = date('Y-m-d', strtotime($row['contract_to']. ' + 1 days'));
                        ?>
                        <div class="form-group" style="width:100%">
                            <label for="name">Contract From</label>
                            <input type="date" class="form-control" placeholder="Contract From"
                                id="renewal_contract_from" name="renewal_contract_from"
                                value="<?php echo $renewal_from; ?>">
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Annual Rent</label>
                            <input type="number" class="form-control" placeholder="Annual Rent" id="" name=""
                                value="<?php echo $row['default_rent']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Security Deposit</label>
                            <input type="number" class="form-control" placeholder="Security Deposit"
                                id="renewal_security" name="renewal_security"
                                value="<?php echo $row['default_security']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Insurance</label>
                            <input type="number" class="form-control" placeholder="Insurance" id="renewal_insurance"
                                name="renewal_insurance" value="<?php echo $row['default_insurance']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Service Charge</label>
                            <input type="number" class="form-control" placeholder="Service Charge"
                                id="renewal_service_charge" name="renewal_service_charge"
                                value="<?php echo $row['default_service']; ?>" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;margin-bottom:10px;">
                        <div class="cheque_button" onclick="renewal_cheques_4()">
                            <input type="radio" id="renewal_total_cheques_4" name="renewal_total_cheques" value="4" />
                            <label class="btn btn-default" for="renewal_total_cheques_4">4 Cheque</label>
                        </div>
                        <div class="cheque_button" onclick="renewal_cheques_6()">
                            <input type="radio" id="renewal_total_cheques_6" name="renewal_total_cheques" value="6" />
                            <label class="btn btn-default" for="renewal_total_cheques_6">6 Cheque</label>
                        </div>
                        <div>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <?php 
                            $default_first_rent = "4";
                            $quarter_1 = ($row['default_rent'] / $default_first_rent);
                            $quarter_1_fmt = number_format($quarter_1); 
                            ?>
                            <label for="name">Quarter 1</label><br>
                            <div class="cheque_button" onclick="renewal_cash1()" style="margin-bottom: 10px;">
                                <input type="radio" id="renewal_cash1" name="renewal_cash1" value="" />
                                <label class="btn btn-default">Cash</label>
                            </div>
                            <div class="cheque_button" onclick="renewal_cheque1()">
                                <input type="radio" id="renewal_cheque1" name="renewal_cheque1" value="" />
                                <label class="btn btn-default">Cheque</label>
                            </div>
                            <!-- <input type="hidden" class="form-control" placeholder="amount" id="amount" name="amount" value="<?php  //echo $quarter_1; ?>"> -->
                            <!-- <input type="text" class="form-control" id="" value="<?php  //echo $quarter_1_fmt; ?> AED" disabled style="height: 380px !important;text-align: center;font-size: 40px;background: #eee;"> -->
                            <input type="text" class="form-control" id="renewal_amount" name="renewal_amount"
                                placeholder="0 AED" value=""
                                style="height: 380px !important;text-align: center;font-size: 40px;background: #eee;">
                            <div id="renewal_cheque_1" class="form-group"
                                style="width:100%;padding: 0px;border-radius: 5px;display:none;margin:0px;">
                                <label for="name">Quarter 1 Cheque Number</label>
                                <input type="number" class="form-control" placeholder="Cheque Number"
                                    id="renewal_cheque_1_number" name="renewal_cheque_1_number" value="" required>
                                <label for="name">Quarter 1 Cheque Amount</label>
                                <input type="number" class="form-control" placeholder="Cheque Amount"
                                    id="renewal_cheque_1_amount" name="renewal_cheque_1_amount" value="" required>
                                <label for="name">Quarter 1 Cheque Date</label>
                                <input type="date" class="form-control" placeholder="Cheque Date"
                                    id="renewal_cheque_1_date" name="renewal_cheque_1_date" value="" required>
                                <label for="name">Quarter 1 Bank Name</label>
                                <input type="text" class="form-control" placeholder="Bank Name"
                                    id="renewal_cheque_1_bank" name="renewal_cheque_1_bank" value="" required>
                                <label for="name">Quarter 1 Cheque File</label>
                                <input type="file" id="renewal_cheque_1_file" name="renewal_cheque_1_file" required
                                    style="height: 22px !important;">
                                <canvas id="renewal_pdfViewer1"
                                    style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                            </div>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 2 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="renewal_cheque_2_number" name="renewal_cheque_2_number" value="" required>
                            <label for="name">Quarter 2 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="renewal_cheque_2_amount" name="renewal_cheque_2_amount" value="" required>
                            <label for="name">Quarter 2 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="renewal_cheque_2_date"
                                name="renewal_cheque_2_date" value="" required>
                            <label for="name">Quarter 2 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="renewal_cheque_2_bank"
                                name="renewal_cheque_2_bank" value="" required>
                            <label for="name">Quarter 2 Cheque File</label>
                            <input type="file" id="renewal_cheque_2_file" name="renewal_cheque_2_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_pdfViewer2"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 3 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="renewal_cheque_3_number" name="renewal_cheque_3_number" value="" required>
                            <label for="name">Quarter 3 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="renewal_cheque_3_amount" name="renewal_cheque_3_amount" value="" required>
                            <label for="name">Quarter 3 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="renewal_cheque_3_date"
                                name="renewal_cheque_3_date" value="" required>
                            <label for="name">Quarter 3 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="renewal_cheque_3_bank"
                                name="renewal_cheque_3_bank" value="" required>
                            <label for="name">Quarter 3 Cheque File</label>
                            <input type="file" id="renewal_cheque_3_file" name="renewal_cheque_3_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_pdfViewer3"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Quarter 4 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="renewal_cheque_4_number" name="renewal_cheque_4_number" value="" required>
                            <label for="name">Quarter 4 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="renewal_cheque_4_amount" name="renewal_cheque_4_amount" value="" required>
                            <label for="name">Quarter 4 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="renewal_cheque_4_date"
                                name="renewal_cheque_4_date" value="" required>
                            <label for="name">Quarter 4 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="renewal_cheque_4_bank"
                                name="renewal_cheque_4_bank" value="" required>
                            <label for="name">Quarter 4 Cheque File</label>
                            <input type="file" id="renewal_cheque_4_file" name="renewal_cheque_4_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_pdfViewer4"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div id="renewal_cheque_5" class="form-group"
                            style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;display:none">
                            <label for="name">Quarter 5 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="renewal_cheque_5_number" name="renewal_cheque_5_number" value="" required>
                            <label for="name">Quarter 5 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="renewal_cheque_5_amount" name="renewal_cheque_5_amount" value="" required>
                            <label for="name">Quarter 5 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="renewal_cheque_5_date"
                                name="renewal_cheque_5_date" value="" required>
                            <label for="name">Quarter 5 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="renewal_cheque_5_bank"
                                name="renewal_cheque_5_bank" value="" required>
                            <label for="name">Quarter 5 Cheque File</label>
                            <input type="file" id="renewal_cheque_5_file" name="renewal_cheque_5_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_pdfViewer5"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div id="renewal_cheque_6" class="form-group"
                            style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;display:none">
                            <label for="name">Quarter 6 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="renewal_cheque_6_number" name="renewal_cheque_6_number" value="" required>
                            <label for="name">Quarter 6 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="renewal_cheque_6_amount" name="renewal_cheque_6_amount" value="" required>
                            <label for="name">Quarter 6 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="renewal_cheque_6_date"
                                name="renewal_cheque_6_date" value="" required>
                            <label for="name">Quarter 6 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="renewal_cheque_6_bank"
                                name="renewal_cheque_6_bank" value="" required>
                            <label for="name">Quarter 6 Cheque File</label>
                            <input type="file" id="renewal_cheque_6_file" name="renewal_cheque_6_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_pdfViewer6"
                                style="width: auto;height: 100px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <div class="form-group" style="border: 1px solid lightgrey;padding: 10px;border-radius: 5px;">
                            <label for="name">Emirates ID</label>
                            <input type="text" class="form-control" placeholder="Emirates ID"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                minlength="15" maxlength="15" required="" id="renewal_eid" name="renewal_eid" value=""
                                required>
                            <label for="name">EID Expiry</label>
                            <input type="date" class="form-control" placeholder="Emirates ID Expiry"
                                id="renewal_eid_expiry" name="renewal_eid_expiry" value="" required>
                            <label for="name">Emirates ID File</label>
                            <input type="file" id="renewal_eid_file" name="renewal_eid_file" required
                                style="height: 22px !important;">
                            <canvas id="renewal_eid_file_viewer"
                                style="width: auto;height: 185px;max-width: 220px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                    </div>
                    <label>
                        <input type="hidden" name="pay_mode" value="Cash" checked>
                        <!-- <img src="images/cash.png" alt="Option 1" style="width:150px;"> -->
                    </label>

                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="invoice_id" name="invoice_id"
                        value="<?php echo $eid_row['invoice_id']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="Renewal" required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button"
                                style="width: 300px;background: #0c0c7e;color: white;height: 50px;border-radius: 5px;font-size: 20px;line-height: 1;">Submit</button>
                        </center>
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


<!-- Cancel Contract -->
<div class="modal" id="myModal3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Agreement</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/transaction.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Cancellation Type</label>
                            <select class="form-control" id="cancellation_type" name="cancellation_type" value=""
                                required>
                                <option value="Clean">Clean</option>
                                <option value="Dispute">Dispute</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Parking</label>
                            <input type="number" class="form-control" placeholder="Parking Number" id="parking"
                                name="parking" value="<?php echo $row['parking']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Rent Balance <span style="color:royalblue">(Incoming)</span></label>
                            <input type="number" class="form-control" placeholder="Closing Amount" id="amount"
                                name="amount" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Service Charges <span style="color:royalblue">(Incoming)</span></label>
                            <input type="number" class="form-control" placeholder="Service Charges" id="service_charge"
                                name="service_charge" value="<?php echo $eid_row['service_charge']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Maintenance Charges <span style="color:royalblue">(Incoming)</span></label>
                            <input type="number" class="form-control" placeholder="Maintenance Charges" id="maintenance_charge"
                                name="maintenance_charge" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Security Deposit <span style="color:red"></span></label>
                            <input type="number" class="form-control" placeholder="Security Deposit" id="security"
                                name="security" value="<?php echo $eid_row['security']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Any Refund <span style="color:red">(Outgoing)</span></label>
                            <input type="number" class="form-control" placeholder="Any Refund" id="refund" name="refund"
                                value="" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <label>
                            <input type="radio" name="pay_mode" value="Cash" checked>
                            <img src="images/cash.png" alt="Option 1" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Card">
                            <img src="images/card.png" alt="Option 2" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Cheque">
                            <img src="images/cheque.png" alt="Option 3" style="width:150px;">
                        </label>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                            <label for="name">Attachment [Cheque Deposit OR Cash Receipt]</label>
                            <input type="file" id="cancel_file" name="myfile" required>
                            <canvas id="cancel_file_viewer"
                                style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                            required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                            value="<?php echo $row['contract_number']; ?>" required>
                        <input type="hidden" class="form-control" id="type" name="type" value="cancellation" required>
                    </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
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

<!-- Maintenance -->
<div class="modal" id="myModal4">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Maintenance Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/transaction.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Maintenance Type</label>
                            <select class="form-control" id="maintenance_type" name="maintenance_type" value=""
                                required>
                                <option value="AC">AC</option>
                                <option value="Plumbing">Plumbing</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Carpentry">Carpentry</option>
                                <option value="Metal/Aluminium">Metal/Aluminium</option>
                                <option value="Tiling">Tiling</option>
                                <option value="Painting">Painting</option>
                                <option value="False-Ceiling">False-Ceiling</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Amount</label>
                            <input type="number" class="form-control" placeholder="Amount" id="amount" name="amount"
                                value="" required>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <label>
                            <input type="radio" name="pay_mode" value="Cash" checked>
                            <img src="images/cash.png" alt="Option 1" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Card">
                            <img src="images/card.png" alt="Option 2" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Cheque">
                            <img src="images/cheque.png" alt="Option 3" style="width:150px;">
                        </label>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                            <label for="name">Attachment [Cheque Deposit OR Cash Receipt]</label>
                            <input type="file" id="repair_file" name="myfile" required>
                            <canvas id="repair_file_viewer"
                                style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                            required>
                        <input type="hidden" class="form-control" id="type" name="type" value="Repair" required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button"
                                style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
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


<!-- Parking -->
<div class="modal" id="myModal5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Parking Payment</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/transaction.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Rent From</label>
                            <input type="date" class="form-control" placeholder="Rent From" id="contract_from"
                                name="contract_from" value="<?php echo $row['contract_from']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label for="name">Rent To</label>
                            <input type="date" class="form-control" placeholder="Rent To" id="contract_to"
                                name="contract_to" value="<?php echo $row['contract_to']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Amount</label>
                            <input type="number" class="form-control" placeholder="Amount" id="amount" name="amount"
                                value="<?php echo $row['default_parking']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name" style="width:100%">Parking Number</label>
                            <select type="text" placeholder="Parking Number" id="parking_number" name="parking_number"
                                class="selectpicker" data-show-subtext="true" data-live-search="true" required
                                style="width:100%">
                                <?php
                                $get_parking = "SELECT parking_number from parking_id WHERE status=0";
                                $result_parking = mysqli_query($conn, $get_parking);
                                while($row_parking = mysqli_fetch_assoc($result_parking)){
                                ?>
                                <option value="<?php echo $row_parking['parking_number']; ?>">
                                    <?php echo $row_parking['parking_number']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <label>
                            <input type="radio" name="pay_mode" value="Cash" checked>
                            <img src="images/cash.png" alt="Option 1" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Card">
                            <img src="images/card.png" alt="Option 2" style="width:150px;">
                        </label>

                        <label>
                            <input type="radio" name="pay_mode" value="Cheque">
                            <img src="images/cheque.png" alt="Option 3" style="width:150px;">
                        </label>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group" style="margin-top:20px;">
                            <label for="name">Attachment [Cheque Deposit OR Cash Receipt]</label>
                            <input type="file" id="parking_file" name="myfile" required>
                            <canvas id="parking_file_viewer"
                                style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        </div>
                        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                            required>
                        <input type="hidden" class="form-control" id="type" name="type" value="Parking" required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                            value="<?php echo $row['contract_number']; ?>" required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button"
                                style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
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

<!-- Edit Information -->
<div class="modal" id="myModal6">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Information</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="parking_form" action="php/apartment_edit.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input class="form-control" placeholder="Enter Name" id="name" name="name"
                                value="<?php echo $row['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Mobile</label>
                            <input type="number" class="form-control" placeholder="Enter Mobile" id="mobile"
                                name="mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input class="form-control" placeholder="Enter Email" id="email" name="email"
                                value="<?php echo $row['email']; ?>" required>
                        </div>
                        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['id']; ?>"
                            required>
                    </div>
                    <div class="card-body" style="display:flex;justify-content:center;">
                        <div class="form-group">
                        <label for="name">Emirates ID</label>
                            <input type="text" class="form-control" placeholder="Emirates ID"
                                onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"
                                minlength="15" maxlength="15" required="" id="eid" name="eid" value="" required>
                        </div>
                        <div class="form-group">
                        <label for="name">EID Expiry</label>
                            <input type="date" class="form-control" placeholder="Emirates ID Expiry" id="eid_expiry"
                                name="eid_expiry" value="" required>
                        </div>
                    </div>
                        <div style="text-align:center;">
                        <label for="name">Emirates ID File</label>
                        <input type="file" id="eid_file" name="eid_file" required style="height: 22px !important;margin:auto;margin-bottom:10px;">
                        <canvas id="eid_replacement_viewer"
                            style="width: auto;height: 185px;max-width: 220px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                        <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                            required>
                        <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button"
                                style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
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

<!-- Replace Cheque 2-->
<div class="modal" id="replace_cheque_2">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Replace Cheque 2</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_replacement_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_2_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_2_number" name="cheque_2_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_2_amount" name="cheque_2_amount" value="<?php echo $eid_row['cheque_2_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_2_date"
                                name="cheque_2_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_2_bank"
                                name="cheque_2_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="replace_2_cheque" name="replace_2_cheque" required style="margin:auto;">
                        <canvas id="replace_2_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="replace_cheque_2" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Cancel Cheque 2-->
<div class="modal" id="cancel_cheque_2">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Cheque 2</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_cancellation_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_2_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Bank Letter]</label>
                        <input type="file" id="cancel_2_cheque" name="cancel_2_cheque" required style="margin:auto;">
                        <canvas id="cancel_2_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="cancel_cheque_2" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- Replace Cheque 3-->
<div class="modal" id="replace_cheque_3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Replace Cheque 3</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_replacement_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_3_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_3_number" name="cheque_3_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_1_amount" name="cheque_1_amount" value="<?php echo $eid_row['cheque_3_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_3_date"
                                name="cheque_3_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_3_bank"
                                name="cheque_3_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="replace_3_cheque" name="replace_3_cheque" required style="margin:auto;">
                        <canvas id="replace_3_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="replace_cheque_3" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Cancel Cheque 3-->
<div class="modal" id="cancel_cheque_3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Cheque 3</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_cancellation_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_3_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Bank Letter]</label>
                        <input type="file" id="cancel_3_cheque" name="cancel_3_cheque" required style="margin:auto;">
                        <canvas id="cancel_3_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="cancel_cheque_3" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- Replace Cheque 4-->
<div class="modal" id="replace_cheque_4">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Replace Cheque 4</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_replacement_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_4_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_4_number" name="cheque_4_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_4_amount" name="cheque_4_amount" value="<?php echo $eid_row['cheque_4_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_4_date"
                                name="cheque_4_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_4_bank"
                                name="cheque_4_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="replace_4_cheque" name="replace_4_cheque" required style="margin:auto;">
                        <canvas id="replace_4_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="replace_cheque_4" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Cancel Cheque 4-->
<div class="modal" id="cancel_cheque_4">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Cheque 4</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_cancellation_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_4_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Bank Letter]</label>
                        <input type="file" id="cancel_4_cheque" name="cancel_4_cheque" required style="margin:auto;">
                        <canvas id="cancel_4_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="cancel_cheque_4" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- Replace Cheque 5-->
<div class="modal" id="replace_cheque_5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Replace Cheque 5</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_replacement_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_5_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_5_number" name="cheque_5_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_5_amount" name="cheque_5_amount" value="<?php echo $eid_row['cheque_5_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_5_date"
                                name="cheque_5_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_5_bank"
                                name="cheque_5_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="replace_5_cheque" name="replace_5_cheque" required style="margin:auto;">
                        <canvas id="replace_5_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="replace_cheque_5" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Cancel Cheque 5-->
<div class="modal" id="cancel_cheque_5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Cheque 5</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_cancellation_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_5_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Bank Letter]</label>
                        <input type="file" id="cancel_5_cheque" name="cancel_5_cheque" required style="margin:auto;">
                        <canvas id="cancel_5_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="cancel_cheque_5" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- Replace Cheque 6-->
<div class="modal" id="replace_cheque_6">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Replace Cheque 6</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_replacement_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_6_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_6_number" name="cheque_6_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_6_amount" name="cheque_6_amount" value="<?php echo $eid_row['cheque_6_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_6_date"
                                name="cheque_6_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_6_bank"
                                name="cheque_6_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="replace_6_cheque" name="replace_6_cheque" required style="margin:auto;">
                        <canvas id="replace_6_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="replace_cheque_6" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Cancel Cheque 6-->
<div class="modal" id="cancel_cheque_6">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Cancel Cheque 6</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_cancellation_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_6_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Bank Letter]</label>
                        <input type="file" id="cancel_6_cheque" name="cancel_6_cheque" required style="margin:auto;">
                        <canvas id="cancel_6_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="cancel_cheque_6" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- New Cheque 2-->
<div class="modal" id="new_cheque_2">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Cheque 2</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_new_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_2_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_2_number" name="cheque_2_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_2_amount" name="cheque_2_amount" value="<?php echo $eid_row['cheque_2_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_2_date"
                                name="cheque_2_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 2 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_2_bank"
                                name="cheque_2_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="new_2_cheque" name="new_2_cheque" required style="margin:auto;">
                        <canvas id="new_2_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="new_cheque_2" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- Replace Cheque 3-->
<div class="modal" id="new_cheque_3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Cheque 3</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_new_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_3_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_3_number" name="cheque_3_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_1_amount" name="cheque_1_amount" value="<?php echo $eid_row['cheque_3_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_3_date"
                                name="cheque_3_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 3 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_3_bank"
                                name="cheque_3_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="new_3_cheque" name="new_3_cheque" required style="margin:auto;">
                        <canvas id="new_3_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="new_cheque_3" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Replace Cheque 4-->
<div class="modal" id="new_cheque_4">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Cheque 4</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_new_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_4_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_4_number" name="cheque_4_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_4_amount" name="cheque_4_amount" value="<?php echo $eid_row['cheque_4_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_4_date"
                                name="cheque_4_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 4 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_4_bank"
                                name="cheque_4_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="new_4_cheque" name="new_4_cheque" required style="margin:auto;">
                        <canvas id="new_4_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="new_cheque_4" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Replace Cheque 5-->
<div class="modal" id="new_cheque_5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Cheque 5</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_new_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_5_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_5_number" name="cheque_5_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_5_amount" name="cheque_5_amount" value="<?php echo $eid_row['cheque_5_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_5_date"
                                name="cheque_5_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 5 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_5_bank"
                                name="cheque_5_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="new_5_cheque" name="new_5_cheque" required style="margin:auto;">
                        <canvas id="new_5_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="new_cheque_5" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- Replace Cheque 6-->
<div class="modal" id="new_cheque_6">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">New Cheque 6</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="rent_form" action="php/cheque_new_process.php" method="POST" enctype="multipart/form-data"
                    style="text-align:center">
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Name</label>
                            <input type="text" class="form-control" placeholder="customer_name" id="customer_name"
                                name="customer_name" value="<?php echo $row['name']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Customer Mobile</label>
                            <input type="text" class="form-control" placeholder="customer_mobile" id="customer_mobile"
                                name="customer_mobile" value="<?php echo $row['mobile']; ?>" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Rent Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount" id="amount"
                                name="amount" value="<?php echo $eid_row['cheque_6_amount']; ?>" readonly>
                        </div>
                    </div>
                    <div class="card-body" style="display:flex">
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Number</label>
                            <input type="number" class="form-control" placeholder="Cheque Number"
                                id="cheque_6_number" name="cheque_6_number" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Amount</label>
                            <input type="number" class="form-control" placeholder="Cheque Amount"
                                id="cheque_6_amount" name="cheque_6_amount" value="<?php echo $eid_row['cheque_6_amount']; ?>" readonly>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Cheque Date</label>
                            <input type="date" class="form-control" placeholder="Cheque Date" id="cheque_6_date"
                                name="cheque_6_date" value="" required>
                        </div>
                        <div class="form-group" style="width:100%">
                            <label for="name">Quarter 6 Bank Name</label>
                            <input type="text" class="form-control" placeholder="Bank Name" id="cheque_6_bank"
                                name="cheque_6_bank" value="" required>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:20px;width:100%;">
                        <label for="name">Attachment [Cheque]</label>
                        <input type="file" id="new_6_cheque" name="new_6_cheque" required style="margin:auto;">
                        <canvas id="new_6_viewer"
                            style="width: auto;height: 185px;max-width: 320px;border: 1px solid lightgrey;border-radius: 5px;"></canvas>
                    </div>
                    <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $row['door']; ?>"
                        required>
                    <input type="hidden" class="form-control" id="contract_number" name="contract_number"
                        value="<?php echo $row['contract_number']; ?>" required>
                    <input type="hidden" class="form-control" id="type" name="type" value="new_cheque_6" required>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <center><button name="submit" type="submit" class="btn button"
                        style="width:150px;background:#0c0c7e;color:white">Submit</button></center>
            </div>
            </form>
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>


<!-- PDF Preview -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.3/js/bootstrap-select.js"></script>
<!-- Form Validation -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cheque_1_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer1")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#cheque_2_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer2")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#cheque_3_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer3")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#cheque_4_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer4")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#cheque_5_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer5")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#cheque_6_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#pdfViewer6")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#eid_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#eid_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_1_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer1")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_2_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer2")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_3_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer3")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_4_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer4")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_5_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer5")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_cheque_6_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_pdfViewer6")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#renewal_eid_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#renewal_eid_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#rent_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#rent_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
                // PDF loading error
                console.error(reason);
            });
        };
        fileReader.readAsArrayBuffer(file);
    }
});
</script>

<script>
$(document).ready(function() {
    $("#contract_form").validate();
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#cancel_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#cancel_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
                // PDF loading error
                console.error(reason);
            });
        };
        fileReader.readAsArrayBuffer(file);
    }
});
</script>

<script>
$(document).ready(function() {
    $("#contract_form").validate();
});
</script>

<script>
// Loaded via <script> tag, create shortcut to access PDF.js exports.
var pdfjsLib = window['pdfjs-dist/build/pdf'];
// The workerSrc property shall be specified.
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

$("#parking_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#parking_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
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

$("#repair_file").on("change", function(e) {
    var file = e.target.files[0]
    if (file.type == "application/pdf") {
        var fileReader = new FileReader();
        fileReader.onload = function() {
            var pdfData = new Uint8Array(this.result);
            // Using DocumentInitParameters object to load binary data.
            var loadingTask = pdfjsLib.getDocument({
                data: pdfData
            });
            loadingTask.promise.then(function(pdf) {
                console.log('PDF loaded');

                // Fetch the first page
                var pageNumber = 1;
                pdf.getPage(pageNumber).then(function(page) {
                    console.log('Page loaded');

                    var scale = 1.5;
                    var viewport = page.getViewport({
                        scale: scale
                    });

                    // Prepare canvas using PDF page dimensions
                    var canvas = $("#repair_file_viewer")[0];
                    var context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    // Render PDF page into canvas context
                    var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    var renderTask = page.render(renderContext);
                    renderTask.promise.then(function() {
                        console.log('Page rendered');
                    });
                });
            }, function(reason) {
                // PDF loading error
                console.error(reason);
            });
        };
        fileReader.readAsArrayBuffer(file);
    }
});
</script>

<script>
$(document).ready(function() {
    $("#contract_form").validate();
});
</script>

<script>
jQuery(document).ready(function() {
    jQuery("#contract_form").validate({
        rules: {

        },
        submitHandler: function(form) {
            form.submit();
        }
    });
});
</script>
<script>
function cash1() {
    document.getElementById("cash_1_amount").style.display = 'block';
    document.getElementById("cheque_1").style.display = 'none';
    document.getElementById("cheque_1_number").required = false;
    document.getElementById("cheque_1_amount").required = false;
    document.getElementById("cheque_1_date").required = false;
    document.getElementById("cheque_1_file").required = false;
}
</script>
<script>
function cheque1() {
    document.getElementById("cash_1_amount").style.display = 'none';
    document.getElementById("cheque_1").style.display = 'block';
    document.getElementById("cheque_1_number").required = true;
    document.getElementById("cheque_1_amount").required = true;
    document.getElementById("cheque_1_date").required = true;
    document.getElementById("cheque_1_file").required = true;
}
</script>
<script>
function renewal_cash1() {
    document.getElementById("renewal_amount").style.display = 'block';
    document.getElementById("renewal_cheque_1").style.display = 'none';
    document.getElementById("renewal_cheque_1_number").required = false;
    document.getElementById("renewal_cheque_1_amount").required = false;
    document.getElementById("renewal_cheque_1_date").required = false;
    document.getElementById("renewal_cheque_1_file").required = false;
    document.getElementById("renewal_cheque_1_bank").required = false;
}
</script>
<script>
function renewal_cheque1() {
    document.getElementById("renewal_amount").style.display = 'none';
    document.getElementById("renewal_cheque_1").style.display = 'block';
    document.getElementById("renewal_cheque_1_number").required = true;
    document.getElementById("renewal_cheque_1_amount").required = true;
    document.getElementById("renewal_cheque_1_date").required = true;
    document.getElementById("renewal_cheque_1_file").required = true;
    document.getElementById("renewal_cheque_1_bank").required = true;
}
</script>
<script>
function cheques_4() {
    document.getElementById("cheque_5").style.display = 'none';
    document.getElementById("cheque_6").style.display = 'none';
    document.getElementById("cheque_5_number").required = false;
    document.getElementById("cheque_5_amount").required = false;
    document.getElementById("cheque_5_date").required = false;
    document.getElementById("cheque_5_file").required = false;
    document.getElementById("cheque_5_bank").required = false;
    document.getElementById("cheque_6_number").required = false;
    document.getElementById("cheque_6_amount").required = false;
    document.getElementById("cheque_6_date").required = false;
    document.getElementById("cheque_6_file").required = false;
    document.getElementById("cheque_6_bank").required = false;
    document.getElementById("cheque_1_file").required = false;
    document.getElementById("cheque_1_bank").required = false;
}
</script>
<script>
function renewal_cheques_4() {
    document.getElementById("renewal_cheque_5").style.display = 'none';
    document.getElementById("renewal_cheque_6").style.display = 'none';
    document.getElementById("renewal_cheque_5_number").required = false;
    document.getElementById("renewal_cheque_5_amount").required = false;
    document.getElementById("renewal_cheque_5_date").required = false;
    document.getElementById("renewal_cheque_5_file").required = false;
    document.getElementById("renewal_cheque_5_bank").required = false;
    document.getElementById("renewal_cheque_6_number").required = false;
    document.getElementById("renewal_cheque_6_amount").required = false;
    document.getElementById("renewal_cheque_6_date").required = false;
    document.getElementById("renewal_cheque_6_file").required = false;
    document.getElementById("renewal_cheque_6_bank").required = false;
    document.getElementById("renewal_cheque_1_file").required = false;
    document.getElementById("renewal_cheque_1_bank").required = false;
}
</script>
<script>
function cheques_6() {
    document.getElementById("cheque_5").style.display = 'block';
    document.getElementById("cheque_6").style.display = 'block';
}
</script>
<script>
function renewal_cheques_6() {
    document.getElementById("renewal_cheque_5").style.display = 'block';
    document.getElementById("renewal_cheque_6").style.display = 'block';
}
</script>
<script>
function cheque_1_open() {
    document.getElementById("cheque_1_view").style.display = 'block'
    document.getElementById("cheque_2_view").style.display = 'none';
    document.getElementById("cheque_3_view").style.display = 'none';
    document.getElementById("cheque_4_view").style.display = 'none';
    document.getElementById("cheque_5_view").style.display = 'none';
    document.getElementById("cheque_6_view").style.display = 'none';
}

function cheque_2_open() {
    document.getElementById("cheque_2_view").style.display = 'block';
    document.getElementById("cheque_3_view").style.display = 'none';
    document.getElementById("cheque_4_view").style.display = 'none';
    document.getElementById("cheque_5_view").style.display = 'none';
    document.getElementById("cheque_6_view").style.display = 'none';
}

function cheque_3_open() {
    document.getElementById("cheque_3_view").style.display = 'block';
    document.getElementById("cheque_2_view").style.display = 'none';
    document.getElementById("cheque_4_view").style.display = 'none';
    document.getElementById("cheque_5_view").style.display = 'none';
    document.getElementById("cheque_6_view").style.display = 'none';
}

function cheque_4_open() {
    document.getElementById("cheque_4_view").style.display = 'block';
    document.getElementById("cheque_2_view").style.display = 'none';
    document.getElementById("cheque_3_view").style.display = 'none';
    document.getElementById("cheque_5_view").style.display = 'none';
    document.getElementById("cheque_6_view").style.display = 'none';
}

function cheque_5_open() {
    document.getElementById("cheque_5_view").style.display = 'block';
    document.getElementById("cheque_2_view").style.display = 'none';
    document.getElementById("cheque_3_view").style.display = 'none';
    document.getElementById("cheque_4_view").style.display = 'none';
    document.getElementById("cheque_6_view").style.display = 'none';
}

function cheque_6_open() {
    document.getElementById("cheque_6_view").style.display = 'block';
    document.getElementById("cheque_2_view").style.display = 'none';
    document.getElementById("cheque_3_view").style.display = 'none';
    document.getElementById("cheque_4_view").style.display = 'none';
    document.getElementById("cheque_5_view").style.display = 'none';
}
</script>
<?php include "css/footer-en.php";
}else{
    header("location: dashboard.php");
}
?>