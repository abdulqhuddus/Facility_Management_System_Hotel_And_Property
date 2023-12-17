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
    // $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date1 = date('Y-m-d', strtotime($date[0]));
    // $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $date_till_midnight = date('Y-m-d', strtotime($date[1]));
    $sql .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}else{
    // $date1 = date("Y-m-d 00:00:01");
    $date1 = date("Y-m-d");
    // $date_till_midnight = date("Y-m-d 23:59:59");
    $date_till_midnight = date("Y-m-d");
    $sql .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}
if (!empty($type)) {
    $sql .= " AND pay_mode = '$type'";
}

$sql .= "AND status='Completed' ORDER BY id DESC";
$rs_transactions = mysqli_query($conn, $sql);

date_default_timezone_set('Asia/Dubai');
$sql2 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + insurance + security + service_charge) + sum((Case When cheque_5_amount = 'NULL' Then 0 Else cheque_5_amount End) + (Case When cheque_6_amount = 'NULL' Then 0 Else cheque_6_amount End)) as Total FROM contracts";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    // $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date1 = date('Y-m-d', strtotime($date[0]));
    // $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $date_till_midnight = date('Y-m-d', strtotime($date[1]));
    $sql2 .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}else{
    // $date1 = date("Y-m-d 00:00:01");
    $date1 = date("Y-m-d");
    // $date_till_midnight = date("Y-m-d 23:59:59");
    $date_till_midnight = date("Y-m-d");
    $sql2 .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}
if (!empty($type)) {
    $sql2 .= " AND type = '$type'";
}

$sql2 .= "AND status='Completed'";
$rs_total = mysqli_query($conn, $sql2);
$row_total = mysqli_fetch_assoc($rs_total);
$total_value = number_format($row_total['Total']);

$sql3 = "SELECT sum(amount + ('type'='Contract' OR 'type'='Rent')) as Received FROM transactions";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    // $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date1 = date('Y-m-d', strtotime($date[0]));
    // $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $date_till_midnight = date('Y-m-d', strtotime($date[1]));
    $sql3 .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}else{
    // $date1 = date("Y-m-d 00:00:01");
    $date1 = date("Y-m-d");
    // $date_till_midnight = date("Y-m-d 23:59:59");
    $date_till_midnight = date("Y-m-d");
    $sql3 .= " WHERE contract_from>='$date1' AND contract_to<='$date_till_midnight'";
}
if (!empty($type)) {
    $sql3 .= " AND type = '$type'";
}

$sql3 .= "AND status='Completed'";
$rs_received = mysqli_query($conn, $sql3);
$row_received = mysqli_fetch_assoc($rs_received);
$total_received = number_format($row_received['Received']);
$balance = $row_total['Total'] - $row_received['Received'];
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
                                        <div class="col-md-3">
                                        </div>
                                        <div class="col-md-6" style="width:23%;margin-left: 14%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:25px">
                                                <span style="color:white;font-weight:bold">Total Contracts
                                                <?php echo $total_value;
                                                ?> AED
                                                </span>
                                            </div>
                                        <div class="col-md-6" style="width:23%;margin-left: 2%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:25px">
                                                <span style="color:white;font-weight:bold">Total Received
                                                <?php echo $total_received;
                                                ?> AED
                                                </span>
                                            </div>
                                        <div class="col-md-6" style="width:23%;margin-left: 2%;text-align: center;background: #000;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:25px">
                                                <span style="color:white;font-weight:bold">Total Balance
                                                <?php echo $total_balance;
                                                ?> AED
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
                <th style="text-align:center !important;">Id</th>
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
                <td><?php echo $row_transactions['id']; ?></td>
                <td><?php echo $row_transactions['contract_type']; ?></td>
                <td><?php echo $row_transactions['apt_id']; ?></td>
                <td><?php echo $row_transactions['name']; ?></td>
                <td>
                <?php 
                $total_amount = $row_transactions['amount'] + $row_transactions['cheque_2_amount'] + $row_transactions['cheque_3_amount'] + $row_transactions['cheque_4_amount'] + $row_transactions['cheque_5_amount'] + $row_transactions['cheque_6_amount'] + $row_transactions['security'] + $row_transactions['insurance'] + $row_transactions['service_charge'];
                $cheques_amount = $row_transactions['cheque_2_amount'] + $row_transactions['cheque_3_amount'] + $row_transactions['cheque_4_amount'] + $row_transactions['cheque_5_amount'] + $row_transactions['cheque_6_amount'];
                ?>
                    <div style="display:flex;width:200px;flex-wrap: wrap;">
                        <div style="width:50%;">First Payment</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['amount']; ?></div>
                        <div style="width:50%;">Security</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['security']; ?></div>
                        <div style="width:50%;">Insurance</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['insurance']; ?></div>
                        <div style="width:50%;">Service</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $row_transactions['service_charge']; ?></div>
                        <div style="width:50%;">Cheques</div><div style="width:50%;background:lightgrey;padding:3px;"><?php echo $cheques_amount; ?></div>
                        <div style="width:50%;font-weight:bold">Total</div><div style="width:50%;background:grey;padding:3px;color:white;font-weight:bold;"><?php echo $total_amount; ?> AED</div>
                    </div>
                </td>
                <td><?php echo $row_transactions['pay_mode']; ?></td>
                <td><?php echo $row_transactions['date']; ?></td>
                <td><?php echo $row_transactions['invoice_id']; ?><br>
                <a href="php/invoice_contract2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”  style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i class="fa-solid fa-receipt"></i> Print</a>
                </td>
                <td><?php echo $row_transactions['updated_by']; ?></td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_2_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_2_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_2_bank']; echo $row_transactions['cheque_2_date'];?> <br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_2_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_2&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
                </td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_3_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_3_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_3_bank'];  echo $row_transactions['cheque_3_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_3_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_3&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
                </td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['cheque_4_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_4_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_4_bank'];  echo $row_transactions['cheque_4_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_4_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_4&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
                </td>
                <td style="text-align: left;">
                    EID<br>
                    <?php 
                    echo $row_transactions['eid']; ?><br>
                    <?php 
                    echo $row_transactions['eid_expiry']; ?><br>
                    File Size: <?php 
                    echo floor($row_transactions['eid_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=eid&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
                </td>
                <td style="text-align: left;">
                <?php
                if($row_transactions['cheque_5_name'] === NULL){}else{
                    echo $row_transactions['cheque_5_amount']; ?> AED<br>
                    #<?php 
                    echo $row_transactions['cheque_5_number']; ?><br>
                    <?php 
                    echo $row_transactions['cheque_5_bank'];  echo $row_transactions['cheque_5_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_5_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_5&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
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
                    echo $row_transactions['cheque_6_bank'];  echo $row_transactions['cheque_6_date'];?><br>
                    File Size: <?php 
                    echo floor($row_transactions['cheque_6_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download_contract.php?type=cheque_6&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
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