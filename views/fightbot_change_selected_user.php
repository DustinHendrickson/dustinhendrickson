<?php 
session_start();
if($_POST['SearchList'] != '') { 
    $_SESSION['SearchList'] = $_POST['SearchList'];
}