<?php
  require_once 'header.php';
  echo <<<_END
  <script>
  function handleClick(cell){
    params  = "seat="+cell.id
    request = new ajaxRequest()
    request.open("POST", "updateseat.php", true)
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    
    O('operation').innerHTML = "...";

    request.onreadystatechange = function()
    {
      if (this.readyState == 4){
        if (this.status == 200){
          if (this.responseText != null){
            var response = this.responseText;
            var array = response.split(";",2);
            if(array.length == 2){
              if(array[1]=='reserved'){
                O(array[0]).style.backgroundColor = 'yellow'
                O('operation').innerHTML = "Reservation performed";
              }
              else if(array[1] == 'delete'){
                O(array[0]).style.backgroundColor = 'green'
                O('operation').innerHTML = "Reservation removed";
              }
              else if(array[1] == 'nothing'){
                O(array[0]).style.backgroundColor = 'red'
                O('operation').innerHTML = "Reservation not performed because the seat is already sold";
              }
              else if(array[1] == 'expired'){
                alert("Your session expired, log in to perform operations");
                location.reload();
              }
            }
          }
        }
      }
    }
    request.send(params)
  }
  function buySeats(){
    request = new ajaxRequest()
    request.open("POST", "buyseats.php", true)
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    
    request.onreadystatechange = function(){
      if (this.readyState == 4){
        if (this.status == 200){
          if (this.responseText != null){
            var response = this.responseText
            if(response == 'nothing'){
              alert("Select one seat before buying")
            }
            else if(response == 'impossible'){
              alert("The seat you reserved were reserved/bought by other in the meantime")
            }
            else if(response == 'possible'){
              alert("The purchase is done")
            }
            else if(response == 'expired'){
              alert("Your session expired, log in to performs operations");
            }
            else{
              alert("Your request is not valid");
            }
          }
          location.reload()
        }  
      }  
    }
    request.send()
  }
  </script>
_END;
  $num_col = 6;
  $num_row = 10;
  $bought = 0;
  $reserved = 0;
  
  $i = 0;
  $j = 0;
  echo '<div class="mine">'.
       '<table border=4 class="stats" cellspacing=0><tr><td></td>';
  $letterAscii = ord('A');
  if(isset($_SESSION['email'])){
    $email=$_SESSION['email'];
    $_SESSION['seats']=array();
    $_SESSION['maxcol']=$letterAscii+$num_col;
    $_SESSION['maxrow']=$num_row; 
  }
  else{
    $email="";
  }

  for($j = 0; $j < $num_col; $j++){
      $letter=chr($letterAscii+$j);
      echo "<th>$letter</td>";
  }
  echo "</tr>";
  
  for($i = 1; $i <= $num_row; $i++){
    echo "<tr><th scope='row'>$i</th>";
    for($j = 0; $j < $num_col; $j++){
      $index=chr($letterAscii+$j);
      $index=$index.$i;
      $result = queryMySql("SELECT status, email FROM Seat WHERE seat='$index'");
      $color="";
      $function="";
      
      if ($result->num_rows){
        $row = $result->fetch_assoc();
        if($row['status'] == 'b'){
          $color='red';
          $bought++;
        }
        elseif($row['status']=='r' && $row['email'] != $email){
          $color='orange';
          $reserved++;
        }
        else{
          $color='yellow';
          array_push($_SESSION['seats'], $index);
        }
      }
      else{
        $color = 'green';
      }
      if($color=='red' || $email==""){
        $function = '';
        $cursor = "default";
      }
      else{
        $function = "onclick='handleClick(this)'";
        $cursor = "pointer";
      }
      echo <<<_END
      <td id='$index' style='background-color:$color; cursor: $cursor;' $function>$index</td>
_END;
    }
    echo "</tr>";
  }
  $size=0;
  if (!isset($_SESSION['email'])){
    $tot = ($num_row*$num_col);
    echo "Number of seat reserved by others: <span id='reserved' style='color:orange;'>$reserved </span><br> ";
    
    echo "Number of seat bought: <span id='bought' style='color:red;'> $bought </span><br>";
    echo "Number of seat free: <span id='free' style='color:green;'> ".($tot-$bought-$reserved-$size)."</span><br>";
    echo "Number of total seat: $tot<br><br>";
  }
  else{
    echo "<span id='operation'></span><br><br>";
  }
  echo '</div></div>';

  
?>