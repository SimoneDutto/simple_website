<?php
  require_once 'header.php';
  if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
  {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    exit;
  }
  echo "<div class='mine'><h4>Please enter your details to log in</h4>";
  $error = $email = $pass = "";

  if (isset($_POST['email']))
  {
    $email = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['pass']);
    
    if ($email == "" || $pass == "")
        $error = "Not all fields were entered<br>";
    else if(strlen($email) > 100 || strlen($pass) > 100 ){
      $error = "Email or password more than 100 characters";
    }
    else
    {
      $result = queryMySQL("SELECT email,password FROM Users
        WHERE email='$email' AND password=MD5('$pass')");

      if ($result->num_rows == 0)
      {
        $error = "<span class='error'>Email/Password
                  invalid</span><br><br>";
      }
      else
      {
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        die("You are now logged in. Please <a href='index.php'>" .
            "click here</a> to continue.<br><br><br><br>");
      }
    }
  }

  echo <<<_END
    <form method='post' action='login.php'>$error
    <span class='fieldname'>Email</span><input type='text'
      maxlength='100' name='email' value='$email'><br>
    <span class='fieldname'>Password</span><input type='password'
      maxlength='100' name='pass' value='$pass'>
_END;
?>

    <br>
    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Login'>
    </form><br></div></div>
  </body>
</html>
