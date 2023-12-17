<?php
require_once "config.php";
session_start();
// echo $_POST['mobile'];
date_default_timezone_set('Asia/Dubai');
$date = date("Y-m-d H:i:sa");
$_SESSION['mobile'] = $_POST['mobile'];
$sql = "SELECT * from admin_users where mobile = '".$_POST['mobile']."'";
if(empty($_POST['password'])){
if($result = mysqli_query($conn, $sql)){
	$num_row = mysqli_num_rows($result);
    $row = mysqli_fetch_assoc($result);
	//echo $row;
	if($num_row > 0){
		$otp = mt_rand (1000, 9999);
        // $hashed_otp = password_hash($otp, PASSWORD_DEFAULT);
        $query_otp = "SELECT * from otp where mobile = '".$_POST['mobile']."'";
        $result_otp = mysqli_query($conn, $query_otp);
        $row_otp = mysqli_num_rows($result_otp);
        if($row_otp == 0){
        $ins_otp = "INSERT INTO otp (mobile, otp, updated_at) VALUES ('".$_POST['mobile']."', '".$otp."', '".$date."')";
        $queryinsert = mysqli_query($conn, $ins_otp);
        if ($queryinsert == true) {
		$message = "Your OTP PIN is ".$otp.".\n\nجمعية الاحسان الخيرية";
		$nmob = substr($_POST['mobile'], 1);
		$add_prefix = '971';
        $mob = $add_prefix.$nmob;
		    $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://51.210.118.93:8080/websmpp/websms");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "user=ALIhsanTR&pass=AlIhsan@5&sid=AL%20IHSAN&mno=".$mob."&type=4&text=".$message."");
            $headers = array();
            $headers[]= 'Content-Type: application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            if (curl_errno($ch)){
               echo 'ERROR:'. curl_error($ch);
                echo " Please try again later";
            }
            curl_close($ch);
        }
        }else{
            $ins_otp = "UPDATE otp SET otp='".$otp."', updated_at='".$date."' WHERE mobile='".$row['mobile']."'";
            $queryinsert = mysqli_query($conn, $ins_otp);
            if ($queryinsert == true) {
            $message = "Your OTP PIN is ".$otp.".\n\nجمعية الاحسان الخيرية";
            $nmob = substr($_POST['mobile'], 1);
            $add_prefix = '971';
            $mob = $add_prefix.$nmob;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://51.210.118.93:8080/websmpp/websms");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "user=ALIhsanTR&pass=AlIhsan@5&sid=AL%20IHSAN&mno=".$mob."&type=4&text=".$message."");
                $headers = array();
                $headers[]= 'Content-Type: application/x-www-form-urlencoded';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                if (curl_errno($ch)){
                echo 'ERROR:'. curl_error($ch);
                    echo " Please try again later";
                }
                curl_close($ch);
            }
        }
	?>
        <form action="login.php" method="POST">            
			<div class="form-group">
				<input type="hidden" name="mobile" value="<?php echo $_POST['mobile']; ?>" class="form-control" />
				<p style="background:lightgrey;border-radius:5px;color:grey;line-height:2.5;padding:3px;"><?php echo $_POST['mobile']; ?></p>
			    <p style="color:green;margin-bottom:0px; margin-top:10px;">Enter OTP sent by SMS <?php //echo $otp; ?></p>
                <!--<input placeholder="XXXX" type="number" name="password" id="password" class="form-control" maxlength="4" required />-->
				<?php include "pin.php"; ?>
                <button name ="submit" value="submit" style="margin-top:10px;">Login</button>
            </div>
        </form>
	<?php
	}else{
		$_SESSION['error'] = "This number is not registered.";
		echo "<script>location.reload()</script>";}
}
}elseif(!empty($_POST['password'])){
echo "Check the OTP";
	

      //$sql = "INSERT INTO form (class, name, mobile, email, city, branch, message, category, status, request_no, language, rating) VALUES ('$class', '".$_SESSION['name']."', '".$_SESSION['mobile']."','".$_SESSION['email']."','".$_SESSION['city']."','".$_SESSION['branch']."','".$_SESSION['message']."','".$_SESSION['category']."','".$_SESSION['status']."','".$_SESSION['request_no']."', '".$_SESSION['language']."', '".$_SESSION['rating']."')";        


					
}

        ?>