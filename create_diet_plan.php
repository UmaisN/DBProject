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
      echo "<h2>Create a new Diet Plan</h2>";
      echo "<hr>";  
    $email = $_POST["email"];
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
      if ($con){  //if my connection with databae is successful
          //echo"<p>Connection Successful</p>";
          $q = "select * from meals order by mealid ASC";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
                echo "<h3>Meals: </h3>";
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
                echo"<div class='container' style='background-color:powderblue; color:black;'>";
                echo '<table style="width: 100%" border="1">';
                echo '<tbody>';
                echo '<tr>';
                echo '<td style="width: 81.35px;">Meal ID</td>';
                echo '<td style="width: 65.333px;">Combo name</td>';
                echo '<td style="width: 45.3333px;">Calories</td>';
                echo '<td style="width: 54.3333px;">Carbs</td>';
                echo '<td style="width: 47.3333px;">Fats</td>';
                echo '<td style="width: 64.3167px;">Fibers</td>';
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
            }
          }
       } 
      else {
          echo"<p>Could not connect to Oracle</p>";  
        }
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
      echo "<div class='col-md-12'>";
      echo '<form action="create_diet_plan.php" method="POST" class="form-group">';
      echo '<label for="goal">Goal</label><input type="text" name="goal" class="form-control"> <br>';
      echo '<label for="meal1">Meal 1 ID: <input name="meal1" type="number" class="form-control"><br>';
      echo '<label for="meal2">Meal 2 ID: <input name="meal2" type="number" class="form-control"><br>';
      echo '<label for="meal3">Meal 3 ID: <input name="meal3" type="number" class="form-control"><br>';
      echo '<label for="meal4">Meal 4 ID: <input name="meal4" type="number" class="form-control"><br>';
      echo '<label for="meal5">Meal 5 ID: <input name="meal5" type="number" class="form-control"><br>';
      echo '<label for="meal6">Meal 6 ID: <input name="meal6" type="number" class="form-control"><br>';
      echo '<label for="meal7">Meal 7 ID: <input name="meal7" type="number" class="form-control"><br>';
      echo '<input type="hidden" name="email" value='.$email.'>';
      echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="create">';
      echo '</form>';
      echo "</div>";

    if (isset($_POST["goal"]) || isset($_POST["meal1"])){
      $id = -1;
      $q = "select max(dietid) from diet";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else{
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          $id = $row[0];
        }
      }
      $id++;
      $goal = $_POST["goal"];
      $goal = "'".$goal."'";
      $meal1 = $_POST["meal1"];
      $meal2 = $_POST["meal2"];
      $meal3 = $_POST["meal3"];
      $meal4 = $_POST["meal4"];
      $meal5 = $_POST["meal5"];
      $meal6 = $_POST["meal6"];
      $meal7 = $_POST["meal7"];
      $c = ",";
      $q = "insert into diet values( ";
      $q = $q.$id.$c.$goal.$c.$meal1.$c.$meal2.$c.$meal3.$c.$meal4.$c.$meal5.$c.$meal6.$c.$meal7;
      $q = $q.")";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Diet was added successfully</h4>";
          echo "</div>";
          $q = "commit";
          $query_id = oci_parse($con, $q); 		
          $r = oci_execute($query_id);
      }
      $q = "update fitness set dietid = $id where fid = (select fid from fituser where email = '$email')";
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