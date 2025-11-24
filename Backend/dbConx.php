<?php
$server="localhost";
$username="root";
$password="";
$db="medical_Hub";

try{
    //PDO = PHP Data Objects, a secure method for connecting and talking to the database.
    $pdo=new PDO("mysql:host=$server;dbname=$db", $username,$password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Setting the error mode to exception to handle errors more effectively
}//php default err mode is silent so we set it to exception to catch errors
catch(PDOException $exception){
echo" An Error Has Occured Connection failed :(  " . $exception->getMessage();
}
// Catch any connection errors and display a message
