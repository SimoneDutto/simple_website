<?php
  require_once 'functions.php';
  session_start();
  if(!isset($_SESSION['email']) || !isset($_SESSION['seats']) || 
    !isset($_SESSION['maxcol']) || !isset($_SESSION['maxrow']) || !isset($_SESSION['lasttime'])){
    die("You are not logged in");
  }
  if (isset($_POST['seat']))
  {
    $email   = $_SESSION['email'];
    $seat    = sanitizeString($_POST['seat']);
    
    if($seat[0] < 'A' || $seat[0] > chr($_SESSION['maxcol']) ||
      $seat[1]  < '1' || $seat[1] > $_SESSION['maxrow']){
        die("ERROR");
    }
    if($_SESSION['lasttime'] < time() - 120){
      $result = "expired";
    }
    else{
      $_SESSION['lasttime']=time();
      $result = updateSeat($email, $seat);
      if($result == 'reserved'){
        array_push($_SESSION['seats'], $seat);
      }
      else if($result == 'delete'){
        if (($key = array_search($seat, $_SESSION['seats'])) !== false) {
          unset($_SESSION['seats'][$key]);
        }
      }
    }
    
    echo $seat.";".$result;
  }
?>