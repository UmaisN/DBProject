<!DOCTYPE html>
<html lang="en" >

<head style="font-family:Arial, Helvetica, sans-serif">
    <title>Fit Me Home</title>
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
      echo "<h2>Create a new Weekly Exercise Plan</h2>";
      echo "<hr>";  
    $UserIsRegistered = 0;
    $email = $_POST["email"];
    $sid =
          "(DESCRIPTION =
          (ADDRESS = (PROTOCOL = TCP)(HOST = DESKTOP-H9CIH7C.Home)(PORT = 1521))
          (CONNECT_DATA =
          (SERVER = DEDICATED)
          (SERVICE_NAME = shayan)
          )
      )";
    $con = oci_connect("scott", "12345", $sid);
      if ($con){  //if my connection with databae is successful
          //echo"<p>Connection Successful</p>";
          $q = "select * from exercise order by exid ASC";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
                echo "<h3>Exercises: </h3>";
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
                echo"<div class='container' style='background-color:powderblue; color:black;'>";
                echo '<table style="width: 100%" border="1">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td style="width: 100px;">Exercise ID</td>';
                echo '<td style="width: 100px;">Exercise name</td>';
                echo '<td style="width: 100px;">Body Part </td>';
                echo '<td style="width: 100px;">Calories/Rep</td>';
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
                echo '</tr>';
                echo '</tbody>';
                echo '</table>';
                echo"<hr>";
                echo "</div>";
            }
          }
       } 
      else {
          echo"<p>Could not connect to Oracle</p>";  
        }
      echo "<div class='col-md-12'>";
      echo '<form action="create_weekly_exercises.php" method="POST" class="form-group">';
      echo '<label for="goal">Goal</label><input type="text" name="goal" class="form-control"> <br>';
      echo '<label for="creator">Name: </label><input type="text" name="creator" class="form-control"> <br>';
      echo '<label for="meal1">Exercise 1 ID: <input name="meal1" type="number" class="form-control"><br>';
      echo '<label for="rep1">Reps for Exercise 1: <input name="rep1" type="number" class="form-control"><br>';
      echo '<label for="meal2">Exercise 2 ID: <input name="meal2" type="number" class="form-control"><br>';
      echo '<label for="rep2">Reps for Exercise 2: <input name="rep2" type="number" class="form-control"><br>'; 
      echo '<label for="meal3">Exercise 3 ID: <input name="meal3" type="number" class="form-control"><br>';
      echo '<label for="rep3">Reps for Exercise 3: <input name="rep3" type="number" class="form-control"><br>';
      echo '<label for="meal4">Exercise 4 ID: <input name="meal4" type="number" class="form-control"><br>';
      echo '<label for="rep4">Reps for Exercise 4: <input name="rep4" type="number" class="form-control"><br>';  
      echo '<input type="hidden" name="email" value='.$email.'>';   
      echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="create">';
      echo '</form>';
      echo "</div>";

    if (isset($_POST["goal"]) || isset($_POST["meal1"])){
      $id = -1;
      $q = "select max(weid) from weeklyexercise";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else{
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          $id = $row[0];
        }
      }
      $id++;
      $creator = $_POST["creator"];
      $creator = "'".$creator."'";
      $goal = $_POST["goal"];
      $goal = "'".$goal."'";
      $meal1 = $_POST["meal1"];
      $meal2 = $_POST["meal2"];
      $meal3 = $_POST["meal3"];
      $meal4 = $_POST["meal4"];
      $rep1 = $_POST["rep1"];
      $rep2 = $_POST["rep2"];
      $rep3 = $_POST["rep3"];
      $rep4 = $_POST["rep4"];
      $c = ",";
      $q = "insert into weeklyexercise values( ";
      $q = $q.$id.$c.$goal.$c.$creator.$c.$rep1.$c.$rep2.$c.$rep3.$c.$rep4.$c.$meal1.$c.$meal2.$c.$meal3.$c.$meal4;
      $q = $q.")";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Weekly Exercise was added successfully for ".$_POST['email']."</h4>";
          echo "</div>";
          $q = "commit";
          $query_id = oci_parse($con, $q); 		
          $r = oci_execute($query_id);
      }

      $q = "update fitness set weid = $id where fid = (select fid from fituser where email = '$email')";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Fitness plan was also for ".$_POST['email']."</h4>";
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