<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
if($_SESSION['tower'] === '1'){
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'alihsan');
define('DB_PASSWORD', 'poIQ8hJPMaCJ)4ol');
define('DB_NAME', 'saleel_db');
}elseif($_SESSION['tower'] === '2'){
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'alihsan');
    define('DB_PASSWORD', 'poIQ8hJPMaCJ)4ol');
    define('DB_NAME', 'saleel_tower_2');
    }elseif($_SESSION['tower'] === '3'){
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'alihsan');
        define('DB_PASSWORD', 'poIQ8hJPMaCJ)4ol');
        define('DB_NAME', 'saleel_tower_3');
        }else{
            define('DB_SERVER', 'localhost');
            define('DB_USERNAME', 'alihsan');
            define('DB_PASSWORD', 'poIQ8hJPMaCJ)4ol');
            define('DB_NAME', 'saleel_db');
            }
 
/* Attempt to connect to MySQL database */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>