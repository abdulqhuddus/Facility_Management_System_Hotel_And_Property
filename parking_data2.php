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
$get_vac = "SELECT count(id) as Vacant FROM parking_id WHERE status=0";
$res_vac = mysqli_query($conn, $get_vac);
$row_vac = mysqli_fetch_assoc($res_vac);

$get_occ = "SELECT count(id) as Occupied FROM parking_id WHERE status=1";
$res_occ = mysqli_query($conn, $get_occ);
$row_occ = mysqli_fetch_assoc($res_occ);
?>
<div class="t-head">Al Ihsan Tower Parking</div>
<div style="display:flex;width:100%;">
<div class="dash-chart">
<?php
$dataPoints = array( 
	array("label"=>"Vacant", "color"=>"green", "y"=>$row_vac['Vacant']),
	array("label"=>"Occupied", "color"=>"#0c0c7e", "y"=>$row_occ['Occupied']),
)
 
?>
<script>
window.onload = function () {
///////////////Chart 1/////////////////	
var chart1 = new CanvasJS.Chart("chartContainer1", {
	animationEnabled: true,
	exportEnabled: true,
	title:{
		text: "Parking Status Chart"
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
		text:"Parking Status Chart"
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
		color: "#014D65",
        indexLabel: "{y}",
        indexLabelFontColor: "black",
		dataPoints: [
			{ y: <?php echo $row_vac['Vacant']; ?>, color: "green", label: "Vacant" },
			{ y: <?php echo $row_occ['Occupied']; ?>, color: "#0c0c7e", label: "Occupied" },
		]
	}]
});
chart2.render();

}
</script>
<div id="chartContainer1" style="height: 370px; width: 100%;"></div>
<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
<script type="text/javascript" src="canvas/canvasjs.min.js"></script>
</div>
<div class="dash-home">
    <table>
        <tr style="display: flex;flex-wrap: wrap;justify-content:center">
            <?php
        $f1 = "SELECT * from parking_id";
        $f1_rs = mysqli_query($conn, $f1);
        while($f1_row = mysqli_fetch_assoc($f1_rs)){
        ?>
            <td>
                <?php           
            if($f1_row['status'] === "1"){
                ?><form action="" method="POST">
                    <button type="submit" name="submit" style="background:#0c0c7e;">
                    <i class="fa-solid fa-car" style="font-size:25px;color:white;"></i>
                    <p style="font-size:15px;color:lightgrey;"><span style="color:orange"><i class="fa-solid fa-square-parking"></i> <?php echo $f1_row['parking_number']; ?></span>     <i class="fa-solid fa-door-open"></i> <?php echo $f1_row['apt_id']; ?></p>
                    </button>
                </form>
                <?php
            }else{
            ?><form action="" method="POST">
                    <button type="submit" name="submit" style="background:green;">
                    <i class="fa-solid fa-car" style="font-size:25px;color:white;"></i>
                    <p style="font-size:15px;color:lightgrey;"><span style="color:orange"><i class="fa-solid fa-square-parking"></i> <?php echo $f1_row['parking_number']; ?></span>     <i class="fa-solid fa-door-closed"></i> <?php echo $f1_row['apt_id']; ?></p>
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
<div class="t-service">
<div class="t-service-3">
        <a href="parking_data.php">
        <i class="fa-solid fa-square-parking"></i>
        <p>Parking View</p>
        </a>
    </div>
    <div class="t-service-2">
        <a href="repairs.php">
        <i class="fa-solid fa-screwdriver-wrench"></i>
        <p>Maintenance Service</p>
        </a>
    </div>
    <div class="t-service-3">
        <a href="parkings.php">
        <i class="fa-solid fa-square-parking"></i>
        <p>Parking Data</p>
        </a>
    </div>
</div>
<?php include "css/footer-en.php";?>