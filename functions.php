<?php 
  $dbhost  = 'localhost';    
  $dbname  = 's257348';   
  $dbuser  = 's257348';   
  $dbpass  = 'nsesssad'; 
  $appname = "AirplaneApp";

  // To generically query the db
  function queryMysql($query)
  {
    global $dbhost, $dbname , $dbuser, $dbpass,$appname;
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    $connection->close();
    return $result;
  }
  // To update atomically the status of a seat
  function updateSeat($email, $seat){
    global $dbhost, $dbname , $dbuser, $dbpass,$appname;
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);
    $return ="";
    try{
      $connection->autocommit(FALSE);
      $connection->begin_transaction();
      $result=$connection->query("SELECT status, email FROM Seat WHERE seat='$seat' FOR UPDATE");
      if (!$result) {
        trigger_error('Invalid query: ' . $connection->error);
      }
      if ($result->num_rows){
        $row = $result->fetch_assoc();
        if($row['status']=='b'){
          $return = 'nothing';
        }
        elseif($row['status']=='r' && $row['email']==$email){
          $connection->query("DELETE FROM Seat WHERE seat='$seat'");
          $return = 'delete';
        }
        else{
          $connection->query("UPDATE Seat SET email='$email' WHERE seat='$seat'");
          $return = 'reserved';
        }
      }
      else{
        $connection->query("INSERT INTO Seat VALUES('$email','$seat','r')");
        $return='reserved';
      }
      
      $connection->commit();
    }
    catch(PDOException $e){
      $connection->rollback();
      $return = '0';
    }
    mysqli_free_result($result);
    $connection->close();

    return $return;
  }
  // To check and buy atomically a seat
  function buySeats($email){
    global $dbhost, $dbname , $dbuser, $dbpass,$appname;
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);
    $return ="";
    try{
      $connection->autocommit(FALSE);
      $connection->begin_transaction();
      $result=$connection->query("SELECT seat FROM Seat WHERE email='$email' AND status='r' FOR UPDATE");
      if (!$result) {
        trigger_error('Invalid query: ' . $connection->error);
      }
      if ($result->num_rows){
        $rows = [];
        while($row = mysqli_fetch_array($result))
        {
          array_push($rows, $row[0]);
        }

        if(array_equal($rows,$_SESSION['seats'])){
          for($i=0; $i < sizeof($rows); $i++){
            $row = $rows[$i];
            $connection->query("UPDATE Seat SET status='b' WHERE seat='$row'");
          }
          $return = 'possible';
        }
        else{
          $connection->query("DELETE FROM Seat WHERE status='r' AND email='$email'");
          $return = "impossible";
        }
      }
      else{
        $connection->query("DELETE FROM Seat WHERE status='r' AND email='$email'");
        $return = 'impossible';
      }
      $connection->commit();
      
    }
    catch(PDOException $e){
      $connection->rollback();
      $return = 'impossible';
    }
    mysqli_free_result($result);
    $connection->close();
    return $return;
  }
  // To compare two arrays
  function array_equal($a, $b) {
    return (is_array($a) && is_array($b) && array_diff($a, $b) === array_diff($b, $a));
  }
  // Destroy a session and all cookies
  function destroySession()
  {
    $_SESSION=array();

    if (session_id() != "" || isset($_COOKIE[session_name()]))
      setcookie(session_name(), '', time()-2592000, '/');

    session_destroy();
  }
  // to clear input string to avoid injections
  function sanitizeString($var)
  {
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    global $dbhost, $dbname , $dbuser, $dbpass,$appname;
    $connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($connection->connect_error) die($connection->connect_error);
    $var= $connection->real_escape_string($var);
    $connection->close();
    return $var;
  }

  function checkInactivity(){
    if(!isset($_SESSION['lasttime'])){
      $_SESSION['lasttime']=time();
    }
    $lastactivity = $_SESSION['lasttime'];
    if($lastactivity < time() - 120){
      destroySession();
      $loggedin = FALSE;
      $emailstr = ' (Guest)';
      header("Location: ./login.php");
      exit("Your session expired");
    }
    else{
      $_SESSION['lasttime'] = time();
    }
  }

?>
