<?php
session_start();

if(isset($_SESSION["view_mode"])){

if($_SESSION["view_mode"]=="admin"){
$_SESSION["view_mode"]="student";
}else{
$_SESSION["view_mode"]="admin";
}

}

header("Location: main(NS).php");
exit;
?>