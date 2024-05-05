<?php
$con = new mysqli('localhost','root','','pos_system');
if($con->connect_error) {

    die(mysqli_error($con));
}
else {

}
?>