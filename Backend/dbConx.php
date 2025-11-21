<?php
$server="localhost";
$username="root";
$password="";
$db="medical_Hub";

try{
    $pdo=new PDO("mysql:host=$server;dbname=$db", $username,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $exception){
echo" An Error Has Occured Connection failed :(  " . $exception->getMessage();
}
