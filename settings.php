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
$sql = "SELECT * FROM `apartments` WHERE status=0  AND bedroom=2 LIMIT 1";
$query = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($query);

$sql2 = "SELECT * FROM `apartments` WHERE status=0 AND bedroom=1 LIMIT 1";
$query2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($query2);

$sql3 = "SELECT * FROM `apartments` WHERE status=0 AND bedroom=0 LIMIT 1";
$query3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($query3);
?>
<style>
button{
    width:200px;
}
</style>
<div class="t-service">
    <div class="t-service-1">
        <p style="font-size:40px;font-weight:bold">Studio Apartment</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#studio">    
        <p style="color:white;">Annual Rent Studio</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row3['default_rent']); ?> AED</p>
        </button>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Single Bed Apartment</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#1bed">    
        <p style="color:white;">Annual Rent 1Bedroom</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row2['default_rent']); ?> AED</p>
        </button>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Two Bed Apartment</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#2bed">    
        <p style="color:white;">Annual Rent 2Bedroom</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row['default_rent']); ?> AED</p>
        </button>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Security Deposit</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal2">    
        <p style="color:white;">Security Deposit</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row['default_security']); ?> AED</p>
        </button>
    </div>
</div>
<div class="t-service">
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Insurance</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal3">    
        <p style="color:white;">Insurance</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row['default_insurance']); ?> AED</p>
        </button>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Service Charges</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal4">    
        <p style="color:white;">Service Charge</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row['default_service']); ?> AED</p>
        </button>
    </div>
    <div class="t-service-1">
    <p style="font-size:40px;font-weight:bold">Parking Rental</p>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal5">    
        <p style="color:white;">Parking Rent</p>
        <p style="color:white;font-size:25px;font-weight:bold"><?php echo number_format($row['default_parking']); ?> AED</p>
        </button>
    </div>
</div>

<!-- Edit Rental -->
<div class="modal" id="studio">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Studio Rental Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Annual Rent</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="rental" name="rental" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="rental0"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Rental -->
<div class="modal" id="1bed">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit 1Bedroom Rental Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Annual Rent</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="rental" name="rental" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="rental1"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Rental -->
<div class="modal" id="2bed">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit 2Bedroom Rental Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Annual Rent</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="rental" name="rental" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="rental2"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Security -->
<div class="modal" id="myModal2">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Security Deposit Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Security Deposit</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="security" name="security" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="security"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Insurance -->
<div class="modal" id="myModal3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Insurance Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Insurance</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="insurance" name="insurance" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="insurance"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Service -->
<div class="modal" id="myModal4">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Service Charge Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Service Charge</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="service" name="service" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="service"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>
<!-- Edit Parking -->
<div class="modal" id="myModal5">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Edit Parking Amount</h4>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <form id="quickForm" action="php/change_default.php" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Parking Rent</label>
                            <input type="number" class="form-control" placeholder="Enter Value" id="parking" name="parking" value=""
                                required>
                        </div>
                        <input type="hidden" class="form-control" id="type" name="type" value="parking"
                                required>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <center><button name="submit" type="submit" class="btn button" style="background:#337ab7 !important;line-height:2 !important;color:white;margin-bottom:20px;">Submit</button></center>
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
</div>