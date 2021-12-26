<?php
// session_start();
?>
<!DOCTYPE html>
<html lang="en">

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
  $email = $_POST["email"];
    $UserIsRegistered = 0;
    if ($_POST) {
      $email = $_POST["email"];
      $password = $_POST["password"];
      //$_SESSION["email"] = $email;
      //$_SESSION["password"] = $password;
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
          $q = "select email, password, firstname from fituser where email = '$email' AND password = '$password' ";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
              $user = $row[2];
              //$_SESSION["user"] = $user;
              $UserIsRegistered++;
            }
          }
      } 
      else {
          echo"<p>Could not connect to Oracle</p>";
      }    
  }
    if ($UserIsRegistered > 0){
      /*echo '<nav class="navbar navbar-default container">';
      echo '<div class="container-fluid">';
      echo '<div>';
      echo '<div';
      echo 'class="collapse navbar-collapse"';
      echo 'id="main_navBar">';
      echo '<ul class="nav navbar-nav">';
      echo '<li class="active"> <a href="home.php">Home</a> </li>';
      echo '<li> <a href="progress.php">Enter log</a></li>';
      echo '<li> <a href="plan.php">Set Plan</a></li>';
      echo "<form action = 'sign_in.php'>";
      echo '<li> <input class="btn btn-warning" type="submit" value="Sign out"></input></li>';
      echo '</form>';
      echo '</ul>';
      echo '</div><!--end un-collapsed navBar-->';
      echo '</div>';
      echo '</nav>';*/
      echo "<div class = 'container'><h1>Fit Me Home</h1>";
      echo "<hr>";
      echo "<h2>Welcome Back, $user</h2>";
      echo "<hr>";

      echo "<h3>Your current fitness attributes: </h3>";
      $q = "select * from fitness where fid = (select fid from fituser where email = '$email')";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else {
            echo "<h4>Fitness: </h4>";
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
            echo"<div class='container' style='background-color:powderblue; color:black;'>";
            echo '<table style="width: 100%" border="1">';
            echo '<tbody>';
            echo '<tr>';
            echo '<td style="width: 100px;">Fitness ID</td>';
            echo '<td style="width: 100px;">Current Weight</td>';
            echo '<td style="width: 100px;">Targeted Weight</td>';
            echo '<td style="width: 100px;">Height</td>';
            echo '<td style="width: 100px;">BMI</td>';
            echo '<td style="width: 100px;">Targeted BMI</td>';
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
            echo"<hr>";
            echo "</div>";
            echo "<hr>";
        }
      }      
      $count = 1;
      echo "<h3>Your current weekly diet plans: </h3>";
      $q = "select dietid, mealid, mealcombo, caloriesint, fats from diet join meals on mealid = day1 or mealid=day2 or mealid = day3 or mealid = day4 or mealid = day5 or mealid = day6 or mealid = day7 where dietid = (select dietid from fitness where fid = (select fid from fituser where email = '$email')) order by mealid desc";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else {
            echo "<h4>Diet Plan: </h4>";
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          echo"<div class='container' style='background-color:powderblue; color:black;'>";
          echo '<table style="width: 100%" border="1">';
          echo '<tbody>';
          echo '<tr>';
          echo '<td style="width: 100px;">Day</td>';
          echo '<td style="width: 100px;">Fats</td>';
          echo '<td style="width: 100px;">Calories</td>';
          echo '<td style="width: 100px;">Meal ID</td>';
          echo '<td style="width: 100px;">Meal combo</td>';
          echo '</tr>';
          echo '<tr>';
          echo "<td> $count <br>";
          echo '</td>';
          echo "<td> $row[4] <br>";
          echo '</td>';
          echo "<td> $row[3] <br>";
          echo '</td>';
          echo "<td> $row[1] <br>";
          echo '</td>';
          echo "<td> $row[2] <br>";
          echo '</td>';
          echo '</tr>';
          echo '</tbody>';
          echo '</table>';
          echo "<hr>";
          echo "</div>";
            $count++;
        }
        echo "<hr>";
      }       

      echo "<h3>Your current exercise Plan : </h3>";
      $q = "select exname, bodypart, caloriesperrep, goal from weeklyexercise join exercise on exercise1 = exid or exercise2 = exid or exercise3 = exid or exercise4 = exid where weid = (select weid from fitness where fid = (select fid from fituser where email = '$email'))";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else {
            echo "<h4>Exercise: </h4>";
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
            echo"<div class='container' style='background-color:powderblue; color:black;'>";
            echo '<table style="width: 100%" border="1">';
            echo '<tbody>';
            echo '<tr>';
            echo '<td style="width: 100px;">Exercise Name</td>';
            echo '<td style="width: 100px;">Body Part</td>';
            echo '<td style="width: 100px;">Calories/Rep</td>';
            echo '<td style="width: 100px;">Goal</td>';
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
      echo "<br><hr><br>";
      echo "<form action='set_fitness.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Update physical attributes' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";

      echo "<form action='create_meal.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Create a new meal' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";

      echo "<form action='create_diet_plan.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Create a new Diet Plan' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";

      echo "<form action='create_exercise.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Create a new exercise' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";

      echo "<form action='create_weekly_exercises.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Set weekly exercises' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";

      echo "<form action='enter_log.php' method='POST'>";
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="hidden" name="password" value='.$password.'>';
      echo "<input type='submit' value='Enter Log' class='btn btn-primary btn-lg btn-block'>";
      echo "</form>";
      echo "<hr>";

    }
    else{
      echo "<div class='container'>";
      echo "<h2>User not registered</h2>";
      echo "<hr>";
      echo "<form action='sign_up.php'>";
      echo "<input type='submit' value='Register me' class='btn btn-bg btn-block btn-success'>";
      echo "</form>";
      echo "<form action='sign_in.php'>";
      echo "<input type='submit' value='Try Again' class='btn btn-bg btn-block btn-success'>";
      echo "</form>";
      echo "</div>";
    }
?>  
</div>

</body>
</html>