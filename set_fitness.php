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
    echo "<div class = 'container'><h1>Fit Me</h1>";
    echo "<hr>";
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
    $fid = -1;
    if (isset($_POST["password"])) {
      $email = $_POST["email"];
      $password = $_POST["password"];
      if ($con){  //if my connection with databae is successful
          //echo"<p>Connection Successful</p>";
          echo "<h2>Weekly Exercises:</h2>";
          $q = "select * from weeklyexercise order by weid asc";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
                echo"<div class='container' style='background-color:powderblue; color:black;'>";
                echo '<table style="width: 100%" border="1">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td style="width: 80px;">ID:</td>';
                echo '<td style="width: 80px;">Goal:</td>';
                echo '<td style="width: 80px;">Creator:</td>';
                echo '<td style="width: 80px;">Exercise 1:</td>';
                echo '<td style="width: 80px;">Reps 1:</td>';
                echo '<td style="width: 80px;">Exercise 2:</td>';
                echo '<td style="width: 80px;">Reps 2:</td>';
                echo '<td style="width: 80px;">Exercise 3:</td>';
                echo '<td style="width: 80px;">Reps 3:</td>';
                echo '<td style="width: 80px;">Exercise 4:</td>';
                echo '<td style="width: 80px;">Reps 4:</td>';
                echo '</tr>';
                echo '<tr>';
                echo "<td> $row[0] <br>";
                echo '</td>';
                echo "<td> $row[1] <br>";
                echo '</td>';
                echo "<td> $row[2] <br>";
                echo '</td>';
                echo "<td> $row[7] <br>";
                echo '</td>';
                echo "<td> $row[3] <br>";
                echo '</td>';
                echo "<td> $row[8] <br>";
                echo '</td>';
                echo "<td> $row[4] <br>";
                echo '</td>';
                echo "<td> $row[9] <br>";
                echo '</td>';
                echo "<td> $row[5] <br>";
                echo '</td>';
                echo "<td> $row[10] <br>";
                echo '</td>';
                echo "<td> $row[6] <br>";
                echo '</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                echo"<hr>";
                echo "</div>";
            }
        }

          echo "<h2>Diet Plans:</h2>";
          $q = "select d.dietid, d.goal, m.mealcombo, m.caloriesint, m.carbs, m.fats, m.fiber  from diet d join meals m on d.day1 = m.mealid order by d.dietid asc";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
                echo"<div class='container' style='background-color:powderblue; color:black;'>";
                echo '<table style="width: 100%" border="1">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td style="width: 80px;">ID:</td>';
                echo '<td style="width: 80px;">Goal: </td>';
                echo '<td style="width: 80px;">Combo: </td>';
                echo '<td style="width: 80px;">Calories: </td>';
                echo '<td style="width: 80px;">Carbs: </td>';
                echo '<td style="width: 80px;">Fats: </td>';
                echo '<td style="width: 80px;">Fiber </td>';
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
                echo "<td> $row[6] <br>";
                echo '</td>';
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                echo"<hr>";
                echo "</div>";
            }
        }

      } 
      else{
          echo"<p>Could not connect to Oracle</p>";
      }    
  }

      echo "<hr>";
      echo "<h2>Enter Physical Data</h2>";
      echo "<hr>";
      echo "<div class='col-md-12'>";
      echo '<form action="set_fitness.php" method="POST" class="form-group">';
      echo '<label for="currweight">Current Weight<input name="currweight" type="number" class="form-control"><br>';
      echo '<label for="targetedweight">Targeted Weight<input name="targetedweight" type="number" class="form-control"><br>';
      echo '<label for="height">Height<input name="height" type="number" step="any" class="form-control"><br>';
      echo '<label for="targetbmi">Target BMI<input name="targetbmi" type="number" class="form-control"><br>';
      echo '<label for="weid">Weekly Exercise ID<input name="weid" type="number" class="form-control"><br>';
      echo '<label for="dietid">Diet ID<input name="dietid" type="number" class="form-control"><br>';
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="Enter">';
      echo '</form>';
      echo "</div>";

    if (isset($_POST["currweight"]) || isset($_POST["height"])){
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
      $q = "select max(fid) from fitness";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      $user = "null";
      if (!$r) echo"No response from database"; 
      else {
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
            $fid = $row[0];
        }
      }
      $fid++;
      //$id++;
      $currweight = $_POST["currweight"];
      $targetweight = $_POST["targetedweight"];
      $height = $_POST["height"];
      $targetbmi = $_POST['targetbmi'];
      $weid = $_POST['weid'];
      $dietid = $_POST['dietid'];
      $c = ",";
      $q = "insert into fitness(fid,current_weight,targeted_weight,height,targetedbmi,weid,dietid) values(";
      $q = $q.$fid.$c.$currweight.$c.$targetweight.$c.$height.$c.$targetbmi.$c.$weid.$c.$dietid;
      $q = $q.")";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Fitness data was updated successfully</h4>";
          echo "</div>";
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

      //////updating fituser with foreign key of this fitness entry
      $q = "update fituser set fid = $fid where fusrid = $id";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Fituser foreign key was also updated</h4>";
          echo "</div>";
          $q = "commit";
          $query_id = oci_parse($con, $q); 		
          $r = oci_execute($query_id);
      }
      
    }
    
?>  
</div>

</body>
</html>