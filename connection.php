<?php
 //   $host = "localhost"; $dbname = "gis-comms"; $username = "root"; $password = ""; 
    $host = "10.15.31.19"; $dbname = "gis-comms"; $username = "remote_user"; $password = "password1234";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
