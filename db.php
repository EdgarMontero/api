<?php
$servername = "localhost";
$dbusername = "cfgs";
$dbpassword = "ira491";
$dbname = "proyecto";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
