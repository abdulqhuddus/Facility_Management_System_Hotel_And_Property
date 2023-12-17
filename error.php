<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="images/favicon.ico">
    <meta property="og:image" content="images/preview.jpg">
    <title>Ihsan X</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="fonts/material-icon/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://kit.fontawesome.com/fdf5fc6483.js" crossorigin="anonymous"></script>
    <!-- Main css -->
    <link rel="stylesheet" href="css/style-en.css">
</head>
<body>
<div class="error-section">
   <i class="fa-solid fa-circle-exclamation"></i>
   <p><?php echo $_SESSION['error']; ?></p>
   <a href="index.php">Home</a>
</div>
<?php 
include "css/footer-en.php";
session_destroy(); ?>
</body>
</html>