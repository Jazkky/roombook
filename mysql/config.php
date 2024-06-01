<?php

$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "roombook";

$conn = new mysqli($db_host,$db_user,$db_pass,$db_name);
if($conn->connect_error){
    echo"Fail to connect MySQL : (".$conn->connect_error.")".$conn->connect_error;
}
