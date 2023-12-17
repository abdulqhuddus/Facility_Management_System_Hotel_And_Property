<?php
// Initialize the session
session_start();

if(isset($_POST['submit'])){

//echo $_POST['mobile'];
//echo $_POST['password'];
// Include config file
require_once "config.php";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    $mobile = trim($_POST["mobile"]);
    $password = trim($_POST["password"]);
    
        // Prepare a select statement
        $sql = "SELECT id, mobile, otp FROM otp WHERE mobile = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_mobile);
            
            // Set parameters
            $param_mobile = $mobile;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                // Check if mobile exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $mobile, $otp);
                    if(mysqli_stmt_fetch($stmt)){
                        if($password == $otp){
                            // Password is correct, so start a new session
                            session_start();                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["mobile"] = $mobile;  
                            
                            // Redirect user to welcome page
							if($_SESSION["id"] > 2){
								header("location: dashboard.php");
							}
							else{
                            header("location: dashboard.php");
							}
                        } else{
                            // Password is not valid, display a generic error message
                            $_SESSION['error'] = "Invalid OTP entered.";
							header("location: index.php");
                        }
                    }
                } else{
                    // mobile doesn't exist, display a generic error message
                    $_SESSION['error'] = "Invalid mobile number.";
					header("location: index.php");
                }
            } else{
                $_SESSION['error'] = "Oops! Something went wrong. Please try again later.";
				header("location: index.php");
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    
    // Close linkection
    mysqli_close($conn);
}

}else{header("location: index.php");}
?>