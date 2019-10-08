<?php // Example 26-6: checkuser.php
  require_once 'functions.php';

  if (isset($_POST['email']))
  {
    $email   = sanitizeString($_POST['email']);
    $result = queryMysql("SELECT * FROM Users WHERE email='$email'");

    if ($result->num_rows)
      echo  "<span class='taken'>&nbsp;&#x2718; " .
            "This email is taken</span>";
    else
      echo "<span class='available'>&nbsp;&#x2714; " .
           "This email is available</span>";
  }
?>
