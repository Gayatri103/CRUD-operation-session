<?php
$servername='localhost';
$username='root';
$password='';
$dbname='G1';
$conn = new mysqli ($servername,$username,$password,$dbname);
if(!$conn){
    echo"db is not connected";
}else{
    echo"connected";
}
?>























































