<?php
  require_once 'functions.php';
  session_start();
  if(!isset($_SESSION['email']) || !isset($_SESSION['seats']) || !isset($_SESSION['lasttime'])){
    die("");
  }

  $email = $_SESSION['email'];
  if(sizeof($_SESSION['seats']) < 1){
    $result = "nothing";
  }
  else if($_SESSION['lasttime'] < time() - 120){
    $result = "expired";
  }
  else{
    $_SESSION['lasttime'] = time();
    $result = buySeats($email);
    $_SESSION['seats']=array();
  }
  echo $result;
?>