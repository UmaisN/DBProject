<!DOCTYPE html>
<html lang="en" >

<head style="font-family:Arial, Helvetica, sans-serif">
    <title>Fit Me</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap_blue.css" rel="stylesheet">
    
</head>

<body style="font-family:Arial, Helvetica, sans-serif" >
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

    <!-- NAV BAR BEGINS HERE -->
  <?php
    $email ='';
    $UserIsRegistered = 0;
    $sid =
          "(DESCRIPTION =
          (ADDRESS = (PROTOCOL = TCP)(HOST = DESKTOP-H9CIH7C.Home)(PORT = 1521))
          (CONNECT_DATA =
          (SERVER = DEDICATED)
          (SERVICE_NAME = shayan)
          )
      )";
    $con = oci_connect("scott", "12345", $sid);
    if (isset($_POST["password"])) {
      $email = $_POST["email"];
      $password = $_POST["password"];
      if ($con){  //if my connection with databae is successful
          //echo"<p>Connection Successful</p>";
          $q = "select email, password, firstname from fituser where email = '$email' AND password = '$password' ";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
              $user = $row[2];
              $UserIsRegistered++;
            }
          }
      } 
      else {
          echo"<p>Could not connect to Oracle</p>";
      }    
  }
      echo "<div class = 'container'><h1>Fit Me</h1>";
      echo "<hr>";
      echo "<h2>Enter Log</h2>";
      echo "<hr>";
      echo "<div class='col-md-12'>";
      echo '<form action="enter_log.php" method="POST" class="form-group">';
      echo '<label for="weightloss">Weight Loss<input name="weightloss" type="number" class="form-control"><br>';
      echo '<label for="caloriesburnt">Calories Burnt<input name="caloriesburnt" type="number" class="form-control"><br>';
      echo '<label for="currweight">Current Weight<input name="currweight" type="number" class="form-control"><br>';
      echo '<label for="date">Log Date</label><input type="date" name="date" value="2021-06-01" class="form-control"> <br>';
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="Enter">';
      echo '</form>';
      echo "</div>";

    if (isset($_POST["weightloss"]) || isset($_POST["caloriesburnt"])){
      $id = -1;
      $email = $_POST["email"];
      $email = "'".$email."'";
      $q = 'select fusrid from fituser where email ='.$email;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else{
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          $id = $row[0];
        }
      }
      $logid;
      $q = 'select max(logid) from log';
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else{
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          $logid = $row[0];
        }
      }
      //$id++;
      //echo $id;
      $logid++;
      $weightloss = $_POST["weightloss"];
      $caloriesburnt = $_POST["caloriesburnt"];
      $currweight = $_POST["currweight"];
      $birthday = $_POST['date'];
      $date = "to_date('".$birthday."','yyyy/mm/dd')";
      $c = ",";
      $q = "insert into log values( ";
      $q = $q.$logid.$c.$weightloss.$c.$currweight.$c.$caloriesburnt.$c.$date.$c.$id;
      $q = $q.")";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Log was entered successfully</h4>";
          echo "</div>";
          $q = "commit";
          $query_id = oci_parse($con, $q); 		
          $r = oci_execute($query_id);
      }

     ///getting height to update user's BMI
      $fid = -1;
      $height = 0;   
      $q = "select height,fid from fitness where fid = (select fid from fituser where email = $email)";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else {
          while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
              $height = $row[0];
              $fid = $row[1];
          }   
      }      

      ///////////updating User's BMI/////////////
      $height  = $height * 0.3048;  //convnerting in meters
      $bmi = $currweight / ($height * $height);
      $q = "update fitness set bmi = $bmi where fid = $fid";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>BMI was updated automatically, set as: $bmi </h4>";
          echo "</div>";
      }   

        //updating weight
        $q = "update fitness set current_weight = $currweight where fid = $fid";//echo $q;
        $query_id = oci_parse($con, $q);		
        $r = oci_execute($query_id);
        if (!$r) echo"No response from database"; 
    }
 


    $email = $_POST["email"];
    echo "<h3>$email's current Logs: </h3>";
    $q = "select * from log where fusrid = (Select fusrid from fituser where email = '$email') order by logdate ASC";
    $query_id = oci_parse($con, $q);		
    $r = oci_execute($query_id);
    $user = "null";
    if (!$r) echo"No response from database"; 
    else {
          echo "<h3>Logs: </h3>";
      while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          echo"<div class='container' style='background-color:powderblue; color:black;'>";
          echo '<table style="width: 100%" border="1">';
          echo '<tbody>';
          echo '<tr>';
          echo '<td style="width: 100px;">LOG ID</td>';
          echo '<td style="width: 100px;">Weight Loss</td>';
          echo '<td style="width: 100px;">Current Weight</td>';
          echo '<td style="width: 100px;">Calories Burnt</td>';
          echo '<td style="width: 100px;">Log date</td>';
          echo '<td style="width: 100px;">User ID</td>';
          echo '</tr>';
          echo '<tr>';
          echo "<td> $row[0] <br>";
          echo '</td>';
          echo "<td> $row[1] <br>";
          echo '</td>';
          echo "<td> $row[2] <br>";
          echo '</td>';
          echo "<td> $row[3] <br>";
          echo '</td>';
          echo "<td> $row[4] <br>";
          echo '</td>';
          echo "<td> $row[5] <br>";
          echo '</td>';
          echo '</tr>';
          echo '</tbody>';
          echo '</table>';
          echo "<hr>";
          echo "</div>";
      }
    }    
    echo "<hr>";

    
?>  
</div>

</body>
</html>