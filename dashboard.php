<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if($_GET['tower'] === '1'){$_SESSION['tower'] = '1';}
elseif($_GET['tower'] === '2'){$_SESSION['tower'] = '2';}
elseif($_GET['tower'] === '3'){$_SESSION['tower'] = '3';}
require "config.php";
include "css/header-en.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
//echo $_SESSION['name'];
$get_vac = "SELECT count(id) as Vacant FROM apartments WHERE status=0";
$res_vac = mysqli_query($conn, $get_vac);
$row_vac = mysqli_fetch_assoc($res_vac);

$get_occ = "SELECT count(id) as Occupied FROM apartments WHERE status=1";
$res_occ = mysqli_query($conn, $get_occ);
$row_occ = mysqli_fetch_assoc($res_occ);

$get_clr = "SELECT count(next_pay_date) as 'Cleared' FROM apartments WHERE status=1 AND contract_to >= CURDATE() AND next_pay_date > DATE_ADD(CURRENT_DATE(), INTERVAL 365 DAY)";
$res_clr = mysqli_query($conn, $get_clr);
$row_clr = mysqli_fetch_assoc($res_clr);

// $get_del = "SELECT count(next_pay_date) as 'Delayed' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 11 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 20 DAY)";
$get_del = "SELECT count(next_pay_date) as 'Delayed' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 0 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 20 DAY)";
$res_del = mysqli_query($conn, $get_del);
$row_del = mysqli_fetch_assoc($res_del);

// $get_non = "SELECT count(next_pay_date) as 'UnPaid' FROM apartments WHERE status=1 AND next_pay_date BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL 0 DAY) AND DATE_ADD(CURRENT_DATE(), INTERVAL 10 DAY)";
$get_non = "SELECT count(next_pay_date) as 'UnPaid' FROM apartments WHERE status=1 AND next_pay_date < CURRENT_DATE()";
$res_non = mysqli_query($conn, $get_non);
$row_non = mysqli_fetch_assoc($res_non);

$get_expired = "SELECT count(id) as Expired FROM apartments WHERE status=1 AND contract_to <= CURDATE() AND next_pay_date > DATE_ADD(CURRENT_DATE(), INTERVAL 365 DAY)";
$res_expired = mysqli_query($conn, $get_expired);
$row_expired = mysqli_fetch_assoc($res_expired);

$Paid = $row_occ['Occupied'] - ($row_clr['Cleared'] + $row_del['Delayed'] + $row_non['UnPaid'] + $row_expired['Expired']);

?>
<div class="t-head">Al Ihsan Tower Dashboard</div>
<?php
$sql2 = "SELECT sum(amount + if(cheque_2_status = 'Paid',cheque_2_amount,0) + if(cheque_3_status = 'Paid',cheque_3_amount,0) + if(cheque_4_status = 'Paid',cheque_4_amount,0)+ if(cheque_5_status = 'Paid',cheque_5_amount,0)+ if(cheque_6_status = 'Paid',cheque_6_amount,0)) as Rents FROM contracts WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31');";
$rs_rents = mysqli_query($conn, $sql2);
$row_rents = mysqli_fetch_assoc($rs_rents);
$total_rents = number_format($row_rents['Rents']);

$sql4 = "SELECT sum(security) as 'Security' from contracts WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_security = mysqli_query($conn, $sql4);
$row_security = mysqli_fetch_assoc($rs_security);
$total_security = number_format($row_security['Security']);


$sql5 = "SELECT sum(insurance) as 'Insurance' from contracts WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_insurance = mysqli_query($conn, $sql5);
$row_insurance = mysqli_fetch_assoc($rs_insurance);
$total_insurance = number_format($row_insurance['Insurance']);


$sql6 = "SELECT sum(service_charge) as 'Service' from contracts WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_service = mysqli_query($conn, $sql6);
$row_service = mysqli_fetch_assoc($rs_service);
$total_service = number_format($row_service['Service']);


$sql8 = "SELECT sum(amount) as Parking FROM parkings WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_parking = mysqli_query($conn, $sql8);
$row_parking = mysqli_fetch_assoc($rs_parking);
$total_parking = number_format($row_parking['Parking']);


$sql9 = "SELECT sum(amount) as Repairs FROM repairs WHERE (date BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_repairs = mysqli_query($conn, $sql9);
$row_repairs = mysqli_fetch_assoc($rs_repairs);
$total_repairs = number_format($row_repairs['Parking']);


$sql7 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(total_cheques = 6,cheque_5_amount,0))+(if(total_cheques = 6,cheque_6_amount,0)) + insurance + security) as Total FROM contracts  WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_total = mysqli_query($conn, $sql7);
$row_total = mysqli_fetch_assoc($rs_total);
$total_all = $row_total['Total'];
$total_all_format = number_format($total_all);


$sql3 = "SELECT sum(amount+(if(cheque_2_status = 'Paid',cheque_2_amount,0))+(if(cheque_3_status = 'Paid',cheque_3_amount,0))+(if(cheque_4_status = 'Paid',cheque_4_amount,0))+(if(cheque_5_status = 'Paid',cheque_5_amount,0))+(if(cheque_6_status = 'Paid',cheque_6_amount,0))) as Received from contracts WHERE (contract_from BETWEEN '2023-01-01' AND '2023-12-31')";
$rs_received = mysqli_query($conn, $sql3);
$row_received = mysqli_fetch_assoc($rs_received);
$total_received = $row_received['Received'];
$total_received_format = number_format($total_received);


$balance = $total_all - $total_received;
$total_balance = number_format($balance);

$total_2021 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(total_cheques = 6,cheque_5_amount,0))+(if(total_cheques = 6,cheque_6_amount,0)) + insurance + security) as Total FROM contracts  WHERE (contract_from BETWEEN '2021-01-01' AND '2021-12-31')";
$rs_total_2021 = mysqli_query($conn, $total_2021);
$row_total_2021 = mysqli_fetch_assoc($rs_total_2021);
$total_all_2021 = $row_total_2021['Total'];
$total_all_2021_format = number_format($total_all_2021);


$received_2021 = "SELECT sum(amount+(if(cheque_2_status = 'Paid',cheque_2_amount,0))+(if(cheque_3_status = 'Paid',cheque_3_amount,0))+(if(cheque_4_status = 'Paid',cheque_4_amount,0))+(if(cheque_5_status = 'Paid',cheque_5_amount,0))+(if(cheque_6_status = 'Paid',cheque_6_amount,0))) as Received from contracts WHERE (contract_from BETWEEN '2021-01-01' AND '2021-12-31')";
$rs_received_2021 = mysqli_query($conn, $received_2021);
$row_received_2021 = mysqli_fetch_assoc($rs_received_2021);
$total_received_2021 = $row_received_2021['Received'];
$total_received_2021_format = number_format($total_received_2021);


$balance_2021 = $total_all_2021 - $total_received_2021;
$total_balance_2021 = number_format($balance_2021);

$total_2022 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(total_cheques = 6,cheque_5_amount,0))+(if(total_cheques = 6,cheque_6_amount,0)) + insurance + security) as Total FROM contracts  WHERE (contract_from BETWEEN '2022-01-01' AND '2022-12-31')";
$rs_total_2022 = mysqli_query($conn, $total_2022);
$row_total_2022 = mysqli_fetch_assoc($rs_total_2022);
$total_all_2022 = $row_total_2022['Total'];
$total_all_2022_format = number_format($total_all_2022);


$received_2022 = "SELECT sum(amount+(if(cheque_2_status = 'Paid',cheque_2_amount,0))+(if(cheque_3_status = 'Paid',cheque_3_amount,0))+(if(cheque_4_status = 'Paid',cheque_4_amount,0))+(if(cheque_5_status = 'Paid',cheque_5_amount,0))+(if(cheque_6_status = 'Paid',cheque_6_amount,0))) as Received from contracts WHERE (contract_from BETWEEN '2022-01-01' AND '2022-12-31')";
$rs_received_2022 = mysqli_query($conn, $received_2022);
$row_received_2022 = mysqli_fetch_assoc($rs_received_2022);
$total_received_2022 = $row_received_2022['Received'];
$total_received_2022_format = number_format($total_received_2022);


$balance_2022 = $total_all_2022 - $total_received_2022;
$total_balance_2022 = number_format($balance_2022);

$total_2024 = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(total_cheques = 6,cheque_5_amount,0))+(if(total_cheques = 6,cheque_6_amount,0)) + insurance + security) as Total FROM contracts  WHERE (contract_from BETWEEN '2024-01-01' AND '2024-12-31')";
$rs_total_2024 = mysqli_query($conn, $total_2024);
$row_total_2024 = mysqli_fetch_assoc($rs_total_2024);
$total_all_2024 = $row_total_2024['Total'];
$total_all_2024_format = number_format($total_all_2024);


$received_2024 = "SELECT sum(amount+(if(cheque_2_status = 'Paid',cheque_2_amount,0))+(if(cheque_3_status = 'Paid',cheque_3_amount,0))+(if(cheque_4_status = 'Paid',cheque_4_amount,0))+(if(cheque_5_status = 'Paid',cheque_5_amount,0))+(if(cheque_6_status = 'Paid',cheque_6_amount,0))) as Received from contracts WHERE (contract_from BETWEEN '2024-01-01' AND '2024-12-31')";
$rs_received_2024 = mysqli_query($conn, $received_2024);
$row_received_2024 = mysqli_fetch_assoc($rs_received_2024);
$total_received_2024 = $row_received_2024['Received'];
$total_received_2024_format = number_format($total_received_2024);


$balance_2024 = $total_all_2024 - $total_received_2024;
$total_balance_2024 = number_format($balance_2024);

$total_all_years = "SELECT sum(amount + cheque_2_amount + cheque_3_amount + cheque_4_amount + (if(total_cheques = 6,cheque_5_amount,0))+(if(total_cheques = 6,cheque_6_amount,0)) + insurance + security) as Total FROM contracts";
$rs_total_all_years = mysqli_query($conn, $total_all_years);
$row_total_all_years = mysqli_fetch_assoc($rs_total_all_years);
$total_all_years = $row_total_all_years['Total'];
$total_all_years_format = number_format($total_all_years);
?>
<div style="display:flex;width:100%;justify-content:center">
<span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Rents 2023<p style="font-size:23px;color:white;"><?php echo $total_rents; ?> AED</p></span>
<!-- <span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Admin Fee 2023<p style="font-size:23px;color:white;"><?php echo $total_service; ?> AED</p></span> -->
<span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Deposits 2023<p style="font-size:23px;color:white;"><?php echo $total_security; ?> AED</p></span>
<span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Insurance 2023<p style="font-size:23px;color:white;"><?php echo $total_insurance; ?> AED</p></span>
<span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Parkings 2023<p style="font-size:23px;color:white;"><?php echo $total_parking; ?> AED</p></span>
<span style="width:250px;height:100px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Repairs 2023<p style="font-size:23px;color:white;"><?php echo $total_repairs; ?> AED</p></span>
<span style="width:250px;height:100px;background:black;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Contracts 2023<p style="font-size:23px;color:white;"><?php echo $total_all_format; ?> AED</p></span>
<span style="width:250px;height:100px;background:blue;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Received 2023<p style="font-size:23px;color:white;"><?php echo $total_received_format; ?> AED</p></span>
<span style="width:250px;height:100px;background:<?php if($balance > 0){echo "red";}else{echo "black";} ?>;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Balance 2023<p style="font-size:23px;color:white;"><?php echo $total_balance; ?> AED</p></span>
</div>
<div style="display:flex;width:73.3%;justify-content:center;float:right;margin-right:1%;">
<a href="dashboard.php?tower=1" style="width:250px;height:50px;background:grey;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid black;">SHOW ALL</a>
<a href="vacant.php?tower=1" style="width:250px;height:50px;background:green;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">VACANT</a>
<a href="occupied.php?tower=1" style="width:250px;height:50px;background:darkblue;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">OCCUPIED</a>
<a href="cleared.php?tower=1" style="width:250px;height:50px;background:purple;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">CLEARED</a>
<a href="expired.php?tower=1" style="width:250px;height:50px;background:yellow;color:black;font-size:25px;text-align:center;line-height:2;border: 2px solid white;">EXPIRED</a>
<a href="delayed.php?tower=1" style="width:250px;height:50px;background:orange;color:black;font-size:25px;text-align:center;line-height:2;border: 2px solid white;">UPCOMING</a>
<a href="unpaid.php?tower=1" style="width:250px;height:50px;background:red;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">UNPAID</a>
</div>
<div style="display:flex;width:100%;">
<div class="dash-chart">
<?php
$dataPoints = array( 
	array("label"=>"Vacant", "color"=>"green", "y"=>$row_vac['Vacant']),
	array("label"=>"Paid", "color"=>"#0c0c7e", "y"=>$Paid),
	array("label"=>"All Cleared", "color"=>"purple", "y"=>$row_clr['Cleared']),
	array("label"=>"Upcoming", "color"=>"orange", "y"=>$row_del['Delayed']),
	array("label"=>"UnPaid", "color"=>"red", "y"=>$row_non['UnPaid']),
	array("label"=>"Expired", "color"=>"yellow", "y"=>$row_expired['Expired']),
)
 
?>
<script>
window.onload = function () {
///////////////Chart 1/////////////////	
var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	exportEnabled: true,
	title:{
		text: "Apartments Current Status Chart"
	},
	subtitles: [{
		// text: "Currency Used: Thai Baht"
	}],
	data: [{
		type: "pie",
	
		legendText: "{label}",
		indexLabelFontSize: 11,
		indexLabel: "{label} - #percent%",
		yValueFormatString: "#,##0",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart1.render();

///////////////Chart 2/////////////////	
var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	
	title:{
		text:"Apartments Status Chart"
	},
	axisX:{
		interval: 1
	},
	axisY2:{
		interlacedColor: "rgba(1,77,101,.2)",
		gridColor: "rgba(1,77,101,.1)",
		// title: "Number of Companies"
	},
	data: [{
		type: "bar",
		name: "companies",
		axisYType: "secondary",
        indexLabel: "{y}",
        indexLabelFontColor: "black",
		color: "#014D65",
		dataPoints: [
			{ y: <?php echo $row_vac['Vacant']; ?>, color: "green", label: "Vacant" },
			{ y: <?php echo $Paid; ?>, color: "#0c0c7e", label: "Paid" },
			{ y: <?php echo $row_clr['Cleared']; ?>, color: "purple", label: "All Cleared" },
			{ y: <?php echo $row_del['Delayed']; ?>, color: "orange", label: "Upcoming" },
			{ y: <?php echo $row_non['UnPaid']; ?>, color: "red", label: "UnPaid" },
			{ y: <?php echo $row_expired['Expired']; ?>, color: "yellow", label: "Expired" },
		]
	}]
});
chart2.render();

var chart3 = new CanvasJS.Chart("chartContainer3", {
	animationEnabled: true,
	theme: "light1", // "light1", "light2", "dark1", "dark2"
	title:{
		text: "Annual Statistics (Total <?php echo $total_all_years_format; ?> AED)"
	},
	axisY: {
		// title: "Total Number"
	},
	data: [{
    type: "stackedColumn",
    showInLegend: true,
    color: "blue",
    // indexLabelFontSize: 22,
    indexLabelFontColor: "white",
    name: "Good",
		legendText: "Cleared",
		dataPoints: [      
			{ y: <?php echo $total_received_2021; ?>, label: "2021", indexLabel: "<?php echo $total_received_2021_format; ?>" },
			{ y: <?php echo $total_received_2022; ?>, label: "2022", indexLabel: "<?php echo $total_received_2022_format; ?>" },
			{ y: <?php echo $total_received; ?>, label: "2023", indexLabel: "<?php echo $total_received_format; ?>" },
			{ y: 0, label: "2024", indexLabel: "0" },
		] 
    },
    {
    type: "stackedColumn",
    showInLegend: true,
    color: "red",
    // indexLabelFontSize: 22,
    indexLabelFontColor: "white",
    name: "Poor",
		legendText: "Balance",
		dataPoints: [      
			{ y: <?php echo $balance_2021; ?>, label: "2021 (<?php echo $total_all_2021_format; ?> AED)", indexLabel: "<?php echo $total_balance_2021; ?>" },
			{ y: <?php echo $balance_2022; ?>, label: "2022 (<?php echo $total_all_2022_format; ?> AED)", indexLabel: "<?php echo $total_balance_2022; ?>" },
			{ y: <?php echo $balance; ?>, label: "2023 (<?php echo $total_all_format; ?> AED)", indexLabel: "<?php echo $total_balance; ?>" },
			{ y: 0, label: "2024 (0 AED)", indexLabel: "0" },
		] 
    }
	]
});
chart3.render();

}
</script>
<div id="chartContainer1" style="height: 370px; width: 100%;display:none"></div>
<div id="chartContainer3" style="height: 370px; width: 100%;"></div>
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
<div style="margin-top:20px;margin-left:15%;width:82%;height:100px;background:black;color:white;font-size:25px;text-align:center;line-height:2;border: 2px solid;">Admin Fee 2023<p style="font-size:23px;color:white;"><?php echo $total_service; ?> AED</p></div>
<script type="text/javascript" src="canvas/canvasjs.min.js"></script>
</div>
<div class="dash-home">
    <table>
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 1</th>
            <?php
        $f1 = "SELECT * from apartments WHERE floor='1'";
        $f1_rs = mysqli_query($conn, $f1);
        while($f1_row = mysqli_fetch_assoc($f1_rs)){
        ?>
            <td>
                <?php           
            if($f1_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f1_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                    <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f1_row['door']; ?>
                        <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }elseif($result_days < 20){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                        <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f1_row['door']; ?>
                            <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f1_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }elseif($result_days > 300){
                $date_now = date('Y-m-d');
                if($f1_row['contract_to'] < $date_now){
                    ?><form action="apartment.php" method="POST">
                            <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                            <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                                <i class='fa-solid fa-door-open'></i>
                                <?php echo $f1_row['door']; ?>
                                <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                                <?php 
                                if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                                elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                                elseif($f1_row['bedroom'] === "0"){} 
                                ?></p>
                            </button>
                        </form>
                        <?php
                }else{
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                        <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f1_row['door']; ?>
                            <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                            <?php 
                            if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                            elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                            elseif($f1_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
                }
            }else{
                ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                    <button type="submit" name="submit" style="background:#0c0c7e;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f1_row['door']; ?>
                        <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                        <?php 
                        if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
                }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f1_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f1_row['door']; ?>
                        <p><?php if($f1_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f1_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f1_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 2</th>
            <?php
        $f2 = "SELECT * from apartments WHERE floor='2'";
        $f2_rs = mysqli_query($conn, $f2);
        while($f2_row = mysqli_fetch_assoc($f2_rs)){
        ?>
            <td>
                <?php
            if($f2_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f2_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f2_row['door']; ?>
                    <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f2_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f2_row['door']; ?>
                        <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f2_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f2_row['door']; ?>
                            <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f2_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f2_row['door']; ?>
                        <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f2_row['door']; ?>
                    <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f2_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f2_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f2_row['door']; ?>
                        <p><?php if($f2_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f2_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f2_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 3</th>
            <?php
        $f3 = "SELECT * from apartments WHERE floor='3'";
        $f3_rs = mysqli_query($conn, $f3);
        while($f3_row = mysqli_fetch_assoc($f3_rs)){
        ?>
            <td>
                <?php
            if($f3_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f3_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f3_row['door']; ?>
                    <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f3_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f3_row['door']; ?>
                        <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f3_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f3_row['door']; ?>
                            <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f3_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f3_row['door']; ?>
                        <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f3_row['door']; ?>
                    <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f3_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f3_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f3_row['door']; ?>
                        <p><?php if($f3_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f3_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f3_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 4</th>
            <?php
        $f4 = "SELECT * from apartments WHERE floor='4'";
        $f4_rs = mysqli_query($conn, $f4);
        while($f4_row = mysqli_fetch_assoc($f4_rs)){
        ?>
            <td>
                <?php
            if($f4_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f4_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f4_row['door']; ?>
                    <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f4_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f4_row['door']; ?>
                        <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f4_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f4_row['door']; ?>
                            <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f4_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f4_row['door']; ?>
                        <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f4_row['door']; ?>
                    <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f4_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f4_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f4_row['door']; ?>
                        <p><?php if($f4_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f4_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f4_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 5</th>
            <?php
        $f5 = "SELECT * from apartments WHERE floor='5'";
        $f5_rs = mysqli_query($conn, $f5);
        while($f5_row = mysqli_fetch_assoc($f5_rs)){
        ?>
            <td>
                <?php
            if($f5_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f5_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f5_row['door']; ?>
                    <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f5_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f5_row['door']; ?>
                        <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f5_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f5_row['door']; ?>
                            <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f5_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f5_row['door']; ?>
                        <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f5_row['door']; ?>
                    <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f5_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f5_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f5_row['door']; ?>
                        <p><?php if($f5_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f5_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f5_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 6</th>
            <?php
        $f6 = "SELECT * from apartments WHERE floor='6'";
        $f6_rs = mysqli_query($conn, $f6);
        while($f6_row = mysqli_fetch_assoc($f6_rs)){
        ?>
            <td>
                <?php
            if($f6_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f6_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f6_row['door']; ?>
                    <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f6_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f6_row['door']; ?>
                        <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f6_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f6_row['door']; ?>
                            <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f6_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f6_row['door']; ?>
                        <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f6_row['door']; ?>
                    <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f6_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f6_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f6_row['door']; ?>
                        <p><?php if($f6_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f6_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f6_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 7</th>
            <?php
        $f7 = "SELECT * from apartments WHERE floor='7'";
        $f7_rs = mysqli_query($conn, $f7);
        while($f7_row = mysqli_fetch_assoc($f7_rs)){
        ?>
            <td>
                <?php
            if($f7_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f7_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f7_row['door']; ?>
                    <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f7_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f7_row['door']; ?>
                        <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f7_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f7_row['door']; ?>
                            <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f7_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f7_row['door']; ?>
                        <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f7_row['door']; ?>
                    <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f7_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f7_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f7_row['door']; ?>
                        <p><?php if($f7_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f7_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f7_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 8</th>
            <?php
        $f8 = "SELECT * from apartments WHERE floor='8'";
        $f8_rs = mysqli_query($conn, $f8);
        while($f8_row = mysqli_fetch_assoc($f8_rs)){
        ?>
            <td>
                <?php
            if($f8_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f8_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f8_row['door']; ?>
                    <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f8_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f8_row['door']; ?>
                        <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f8_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f8_row['door']; ?>
                            <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f8_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f8_row['door']; ?>
                        <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f8_row['door']; ?>
                    <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f8_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f8_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f8_row['door']; ?>
                        <p><?php if($f8_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f8_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f8_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 9</th>
            <?php
        $f9 = "SELECT * from apartments WHERE floor='9'";
        $f9_rs = mysqli_query($conn, $f9);
        while($f9_row = mysqli_fetch_assoc($f9_rs)){
        ?>
            <td>
                <?php
            if($f9_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f9_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f9_row['door']; ?>
                    <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f9_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f9_row['door']; ?>
                        <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f9_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f9_row['door']; ?>
                            <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f9_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f9_row['door']; ?>
                        <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f9_row['door']; ?>
                    <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f9_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f9_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f9_row['door']; ?>
                        <p><?php if($f9_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f9_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f9_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 10</th>
            <?php
        $f10 = "SELECT * from apartments WHERE floor='10'";
        $f10_rs = mysqli_query($conn, $f10);
        while($f10_row = mysqli_fetch_assoc($f10_rs)){
        ?>
            <td>
                <?php
            if($f10_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f10_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f10_row['door']; ?>
                    <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f10_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f10_row['door']; ?>
                        <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f10_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f10_row['door']; ?>
                            <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f10_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f10_row['door']; ?>
                        <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f10_row['door']; ?>
                    <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f10_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f10_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f10_row['door']; ?>
                        <p><?php if($f10_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f10_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f10_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 11</th>
            <?php
        $f11 = "SELECT * from apartments WHERE floor='11'";
        $f11_rs = mysqli_query($conn, $f11);
        while($f11_row = mysqli_fetch_assoc($f11_rs)){
        ?>
            <td>
                <?php
            if($f11_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f11_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f11_row['door']; ?>
                    <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f11_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f11_row['door']; ?>
                        <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f11_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f11_row['door']; ?>
                            <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f11_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f11_row['door']; ?>
                        <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f11_row['door']; ?>
                    <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f11_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
        }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f11_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f11_row['door']; ?>
                        <p><?php if($f11_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f11_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f11_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
        }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 12</th>
            <?php
        $f12 = "SELECT * from apartments WHERE floor='12'";
        $f12_rs = mysqli_query($conn, $f12);
        while($f12_row = mysqli_fetch_assoc($f12_rs)){
        ?>
            <td>
                <?php
            if($f12_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f12_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f12_row['door']; ?>
                    <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f12_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f12_row['door']; ?>
                        <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f12_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f12_row['door']; ?>
                            <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f12_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f12_row['door']; ?>
                        <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f12_row['door']; ?>
                    <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f12_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f12_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f12_row['door']; ?>
                        <p><?php if($f12_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f12_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f12_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 13</th>
            <?php
        $f13 = "SELECT * from apartments WHERE floor='13'";
        $f13_rs = mysqli_query($conn, $f13);
        while($f13_row = mysqli_fetch_assoc($f13_rs)){
        ?>
            <td>
                <?php
            if($f13_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f13_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f13_row['door']; ?>
                    <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f13_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f13_row['door']; ?>
                        <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f13_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f13_row['door']; ?>
                            <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f13_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f13_row['door']; ?>
                        <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f13_row['door']; ?>
                    <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f13_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f13_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f13_row['door']; ?>
                        <p><?php if($f13_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f13_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f13_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 14</th>
            <?php
        $f14 = "SELECT * from apartments WHERE floor='14'";
        $f14_rs = mysqli_query($conn, $f14);
        while($f14_row = mysqli_fetch_assoc($f14_rs)){
        ?>
            <td>
                <?php
            if($f14_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f14_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f14_row['door']; ?>
                    <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f14_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f14_row['door']; ?>
                        <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f14_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f14_row['door']; ?>
                            <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f14_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f14_row['door']; ?>
                        <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f14_row['door']; ?>
                    <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f14_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f14_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f14_row['door']; ?>
                        <p><?php if($f14_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f14_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f14_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
        <!--------------->
        <!--------------->
        <tr>
            <th><i class="fa-solid fa-stairs"></i> Floor 15</th>
            <?php
        $f15 = "SELECT * from apartments WHERE floor='15'";
        $f15_rs = mysqli_query($conn, $f15);
        while($f15_row = mysqli_fetch_assoc($f15_rs)){
        ?>
            <td>
                <?php
            if($f15_row['status'] === "1"){
            date_default_timezone_set('Asia/Dubai');
            $next = $f15_row['next_pay_date']." 00:00:01";
            $now = date("Y-m-d H:i:s");
            $starttime1 = strtotime($now);
            $starttime2 = strtotime($next);
            $result_secs = $starttime2 - $starttime1;
            $result_days = $result_secs / 86400;
            if($result_days < 0){
                ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                <button type="submit" name="submit" style="background:red;color:white;border:2px solid white;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f15_row['door']; ?>
                    <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                    <?php 
                    if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                    elseif($f15_row['bedroom'] === "0"){} 
                    ?></p>
                </button>
            </form>
            <?php
        }elseif($result_days < 20){
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                    <button type="submit" name="submit" style="background:orange;color:black;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f15_row['door']; ?>
                        <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                        <?php 
                        if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";} 
                        ?></p>
                    </button>
                </form>
                <?php
        }elseif($result_days > 300){
            $date_now = date('Y-m-d');
            if($f15_row['contract_to'] < $date_now){
                ?><form action="apartment.php" method="POST">
                        <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                        <button type="submit" name="submit" style="background:yellow;color:black;border:2px solid white;">
                            <i class='fa-solid fa-door-open'></i>
                            <?php echo $f15_row['door']; ?>
                            <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:black'></i>";} ?>
                            <?php 
                            if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:black;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:black;font-size:13px'></i>";}
                            elseif($f15_row['bedroom'] === "0"){} 
                            ?></p>
                        </button>
                    </form>
                    <?php
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                    <button type="submit" name="submit" style="background:purple;color:white;border:2px solid white;">
                        <i class='fa-solid fa-door-open'></i>
                        <?php echo $f15_row['door']; ?>
                        <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
        }else{
            ?><form action="apartment.php" method="POST">
                <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class='fa-solid fa-door-open'></i>
                    <?php echo $f15_row['door']; ?>
                    <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:orange'></i>";} ?>
                    <?php 
                    if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";}
                    elseif($f15_row['bedroom'] === "0"){echo "<i class='fa-solid fa-bed' style='color:orange;font-size:13px'></i>";} 
                    ?></p>
                </button>
            </form>
            <?php
        }
            }else{
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="door" id="door" value="<?php echo $f15_row['door']; ?>">
                    <button type="submit" name="submit" style="background:green;">
                        <i class="fa-solid fa-door-closed"></i>
                        <?php echo $f15_row['door']; ?>
                        <p><?php if($f15_row['parking'] != "0"){echo "<i class='fa-solid fa-square-parking' style='color:white'></i>";} ?>
                        <?php 
                        if($f15_row['bedroom'] === "1"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "2"){echo "<i class='fa-solid fa-bed' style='color:white;font-size:13px'></i> <i class='fa-solid fa-bed' style='color:white;font-size:13px'></i>";}
                        elseif($f15_row['bedroom'] === "0"){} 
                        ?></p>
                    </button>
                </form>
                <?php
            }
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
    </table>
</div>
</div>
<!-- <div class="dash-chart">
<div id="chartContainer3" style="height: 370px; width: 100%;"></div>
</div> -->
<div class="t-service">
    <div class="t-service-3">
        <a href="cancellations.php">
        <i class="fa-solid fa-rectangle-xmark"></i>
        <p>Contract Cancellations</p>
        </a>
    </div>
    <div class="t-service-2">
        <a href="repairs.php">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        <p>Repairs Data</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="parkings.php">
        <i class="fa-solid fa-square-parking"></i>
        <p>Parking Data</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="cheque_replacements.php">
        <i class="fa-solid fa-money-bill-transfer"></i>
        <p>Cheque Replacements</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="cheque_cancellations.php">
        <i class="fa-regular fa-rectangle-xmark"></i>
        <p>Cheque Cancellations</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="cheque_updates.php">
        <i class="fa-solid fa-folder-plus"></i>
        <p>Cheque Updates</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="contact_updates.php">
        <i class="fa-solid fa-id-card"></i>
        <p>Contact Updates</p>
        </a>
    </div>
</div>
<?php include "css/footer-en.php";?>