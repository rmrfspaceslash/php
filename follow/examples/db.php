<?php

//connect to database
$db_user = "noah";
$db_password = "southhills#";
$db_host = "localhost";
$db_name = "noah";

//establish connection
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

if ($conn -> connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

?>
