<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ERROR | E_WARNING | E_PARSE);
require "../config.php";
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
</head>

<body>
    <?php

    // Downloads files
if (isset($_GET['file_id'])) {
    $id = $_GET['file_id'];

    if($_GET['type'] === "Rent"){$category = "rents";}
    elseif($_GET['type'] === "Cancel"){$category = "cancel_contracts";}
    elseif($_GET['type'] === "Parking"){$category = "parkings";}
    elseif($_GET['type'] === "Repair"){$category = "repairs";}

    // fetch file to download from database
    $sql = "SELECT * FROM $category WHERE id=$id";
    
    date_default_timezone_set('Asia/Dubai');
    $date = date("Y-m-d H:i:sa");
    $get_user = "SELECT * FROM admin_users WHERE mobile='".$_SESSION["mobile"]."'";
    $user_query = mysqli_query($conn, $get_user);
    $user_row = mysqli_fetch_assoc($user_query);
    $updated_by = "".$user_row['first_name']." ".$user_row['last_name']."";

    $result = mysqli_query($conn, $sql);

    $file = mysqli_fetch_assoc($result);
    $filepath = '../attachments/' . $file['file_name'];

    if (file_exists($filepath)) {
        header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Description: File Transfer');
        header('Content-type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Disposition: attachment; filename="'.$file['file_name'].'"');
        readfile('../attachments/' . $file['file_name']);
        
        // header('Content-type: application/pdf');

        // Now update downloads count
        $newCount = $file['download_count'] + 1;
        echo $updateQuery = "UPDATE $category SET download_count='".$newCount."', date='".$date."', updated_by='".$updated_by."' WHERE id='".$file['id']."'";
        mysqli_query($conn, $updateQuery);
        exit;
    }

}
    ?>
</body>

</html>