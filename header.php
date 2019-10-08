<?php
  session_start();

  echo "<!DOCTYPE html>\n<html><head><script src='javascript.js' type='text/javascript'></script>".
        "<script>function setVisible(){
          if (navigator.cookieEnabled === true){
            S('container2').visibility = 'visible';
            S('container1').visibility = 'visible';
          }
          else{
            O('bod').innerHTML = '<h1> Enable cookies to see the website</h1>'; 
          }
        }
        </script>".
        "<noscript><h1>Please enable JavaScript if you want to access the page</h1></noscript>";

  require_once 'functions.php';

  $emailstr = 'Guest (To reserve seat please log in) ';

  if (isset($_SESSION['email']))
  {
    if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on"){
      header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
      exit;
    }
    $email  = $_SESSION['email'];
    $loggedin = TRUE;
    $emailstr  = " ($email)";
    checkInactivity();
  }
  else $loggedin = FALSE;



  echo <<<_HEAD
    <title>$appname$emailstr</title><link rel='stylesheet'
    href='styles.css' type='text/css'></head>
    <body id='bod' onload="setVisible()">
    <div id='container1' class='appname'>$appname
    <h3>User: $emailstr</h3>
    </div><div id='container2'> <div class = 'left'>
_HEAD;

  if ($loggedin)
  {
    echo( "<ul class='menu'>" .
        "<li><a href='index.php'>Home</a></li>".
        '<li><a style="cursor: pointer;" onclick="buySeats()">Buy</a></li>'.
        "<li><a href='index.php'>Refresh</a></li>".
        "<li><a href='logout.php'>Logout</a></li></ul></div>" );
  }
  else
  {
    echo ("<ul class='menu'>" .
      "<li><a href='index.php'>Home</a></li>".
        "<li><a href='signup.php'>Sign up</a></li>"            .
        "<li><a href='login.php'>Log in</a></li></ul></div>"    );
  }
?>
