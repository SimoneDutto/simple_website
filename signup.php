<?php 
  require_once 'header.php';
  if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
  {
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    exit;
  }
  echo <<<_END
  <script>
    function checkEmail(email)
    {
        var re = /[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}/igm;
       
        if (email.value == '' || !re.test(email.value))
        {
        O('info').innerHTML = 'Email not correctly formatted'
        return
        }

        params  = "email=" + email.value
        request = new ajaxRequest()
        request.open("POST", "checkemail.php", true)
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
        request.onreadystatechange = function()
        {
        if (this.readyState == 4)
            if (this.status == 200)
            if (this.responseText != null)
                O('info').innerHTML = this.responseText
        }
        request.send(params)
    } 
    function checkPassword(pass){

        var re2 = /(?=.*[a-z])((?=.*[A-Z])|(?=.*\d))/;
        if (pass.value == '' || !re2.test(pass.value))
        {
        O('info2').innerHTML = '&nbsp&#x2718 Password should contain one lower case letter and one number or a upper case letter'
        O('info2').style.color = "red";
        return
        }
        O('info2').innerHTML = '&nbsp&#x2714 Password correctly formatted';
        O('info2').style.color = "green";
    }
  </script>
  <div class='mine'><h4>Please enter your details to sign up</h4>
_END;

  $error = $email = $pass = "";
  if (isset($_SESSION['email'])) destroySession();
  if (isset($_POST['email']))
  {
    $email = sanitizeString($_POST['email']);
    $pass = sanitizeString($_POST['pass']);

    if ($email == "" || $pass == ""){
      $error = "Not all fields were entered<br><br>";
    }
    elseif (!preg_match("#^[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2,4}$#i", $email)){
      $error = "Email not correctly formatted";
    }
    elseif(!preg_match("^(?=.*[a-z])((?=.*[A-Z])|(?=.*\d))^", $pass)){
      $error = "Password not correctly formatted";
    }
    else
    {
      $result = queryMysql("SELECT * FROM Users WHERE email='$email'");

      if ($result->num_rows)
        $error = "That emailname already exists<br><br>";
      else
      {
        queryMysql("INSERT INTO Users VALUES('$email', MD5('$pass'))");
        die("<h4>Account created</h4>Please Log in.<br><br>");
      }
    }
  }

  echo <<<_END
    <form method='post' action='signup.php'>$error
    <span class='fieldname'>Email</span>
    <input type='text' maxlength='100' name='email' value='$email'
      onBlur='checkEmail(this)'><span id='info'></span><br>
    <span class='fieldname'>Password</span>
    <input type='password' maxlength='16' name='pass'
      value='$pass' onkeyup="checkPassword(this)"><span id='info2' style="font-size=4px, color="red"></span> <br>
_END;
?>

    <span class='fieldname'>&nbsp;</span>
    <input type='submit' value='Sign up'>
    </form></div><br>
  </body>
</html>
