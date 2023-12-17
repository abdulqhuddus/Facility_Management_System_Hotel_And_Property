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
$sql = "SELECT * FROM repairs";
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
if (!empty($type)) {
    $sql .= " AND pay_mode = '$type'";
}

$rs_transactions = mysqli_query($conn, $sql);

date_default_timezone_set('Asia/Dubai');
$sql2 = "SELECT sum(amount) as Total  FROM repairs";
if (!empty($date)) {
    $date = $_POST['date'];
    $date = explode(" - ", $date);
    // convert dd/mm/yyyy to yyyy-mm-dd
    $date1 = date('Y-m-d H:i:s', strtotime($date[0]));
    $date_till_midnight = date('Y-m-d 23:59:59', strtotime($date[1]));
    $sql2 .= " WHERE date between '$date1' and '$date_till_midnight'";
}else{
    $date1 = date("Y-01-01 00:00:01");
    $date_till_midnight = date("Y-m-d 23:59:59");
    $sql2 .= " WHERE date between '$date1' and '$date_till_midnight'";
}
if (!empty($type)) {
    $sql2 .= " AND type = '$type'";
}

$rs_total = mysqli_query($conn, $sql2);
$row_total = mysqli_fetch_assoc($rs_total);
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
<div class="t-head">Maintenances</div>
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
                                        <div class="col-md-6" style="margin-left: 25%;text-align: center;background: #87fa87;line-height: 2;margin-bottom: 16px;border-radius: 5px;font-size:25px">
                                                <span style="color:green;font-weight:bold">
                                                <?php echo $row_total['Total'];
                                                ?> AED
                                                </span>
                                            </div>
                                        </div>
                                </form>
</div>
<div class="t_table">
    <table id="example" class="display nowrap" style="width:100%;text-align:center;">
        <thead>
            <tr>
                <th style="text-align:center !important;">Id</th>
                <th style="text-align:center !important;">Apartment</th>
                <th style="text-align:center !important;">Type</th>
                <th style="text-align:center !important;">Customer</th>
                <th style="text-align:center !important;">Amount</th>
                <th style="text-align:center !important;">Pay Mode</th>
                <th style="text-align:center !important;">Date</th>
                <th style="text-align:center !important;">Invoice #</th>
                <th style="text-align:center !important;">Updated By</th>
                <th style="text-align:center !important;">Attachment</th>
            </tr>
        </thead>
        <tbody>
            <?php            
            while($row_transactions = mysqli_fetch_assoc($rs_transactions)){
            ?>
            <tr>
                <td><?php echo $row_transactions['id']; ?></td>
                <td><?php echo $row_transactions['apt_id']; ?></td>
                <td><?php echo $row_transactions['type']; ?></td>
                <td><?php echo $row_transactions['name']; ?></td>
                <td><?php 
                $total_amount = $row_transactions['amount'];
                echo $total_amount; ?></td>
                <td><?php echo $row_transactions['pay_mode']; ?></td>
                <td><?php echo $row_transactions['date']; ?></td>
                <td><?php echo $row_transactions['invoice_id']; ?><br>
                <a href="php/invoice_parking2.php?invoice_id=<?php echo $row_transactions['invoice_id'] ?>" target=“blank”  style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;line-height: 3;"><i class="fa-solid fa-receipt"></i> Print</a>
                </td>
                <td><?php echo $row_transactions['updated_by']; ?></td>
                <td style="text-align: left;">
                    <?php 
                    echo $row_transactions['file_name']; ?><br>
                    File Size: <?php 
                    echo floor($row_transactions['file_size'] / 1000) . ' KB'; ?><br>
                    File Downloads:  <?php 
                    echo $row_transactions['download_count']; ?><br><br>
                    <a href="php/download.php?type=Repair&file_id=<?php echo $row_transactions['id'] ?>" style="font-weight:bold;font-weight: bold;background: #337ab7;color: white;padding: 5px;border-radius: 5px;"><i class="fa-solid fa-download"></i> Download</a>
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