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
?>
<div class="t-head">Al Ihsan Tower Dashboard</div>
<div class="dash-home">
    <table>
        <tr style="display:flex;flex-wrap:wrap;justify-content: center;">
            <?php
        $sql = "SELECT * from parking";
        $query = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($query)){
        ?>
            <td>
                <?php
            ?><form action="apartment.php" method="POST">
                    <input type="hidden" name="id" id="id" value="<?php echo $row['id']; ?>">
                    <?php
                    if($row['status'] != '0'){
                    ?>
                    <button type="submit" name="submit" style="background:#0c0c7e;">
                      <i class="fa-solid fa-car" style="font-size: 25px;"></i>
                        <p><?php echo $row['number']; ?>/<?php echo $row['status']; ?></p>
                    </button>
                    <?php
                    }else{
                    ?>
                    <button type="submit" name="submit" style="background:#0f9347;">
                      <i class="fa-solid fa-car" style="font-size: 25px;"></i>
                        <p><?php echo $row['number']; ?></p>
                    </button>
                    <?php
                    }
                    ?>
                </form>
                <?php
            ?>
            </td>
            <?php
        }
        ?>
        </tr>
    </table>
</div>
<?php include "css/footer-en.php";?>