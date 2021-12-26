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
    if (isset($_POST["email"])) {
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
      echo "<div class = 'container'><h1>Fit Me</h1>";
      echo "<hr>";
      echo "<h2>Add a new meal</h2>";
      echo "<hr>";
      echo "<div class='col-md-12'>";
      echo '<form action="create_meal.php" method="POST" class="form-group">';
      echo '<label for="meal_name">Meal Name</label><input type="text" name="meal_name" class="form-control"> <br>';
      echo '<label for="calories">Calories<input name="calories" type="number" class="form-control"><br>';
      echo '<label for="carbs">Carbs<input name="carbs" type="number" class="form-control"><br>';
      echo '<label for="fats">Fats<input name="fats" type="number" class="form-control"><br>';
      echo '<label for="fiber">Fiber<input name="fiber" type="number" class="form-control"><br>';
      echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="create">';
      echo '</form>';
      echo "</div>";

    if (isset($_POST["calories"]) || isset($_POST["meal_name"])){
      $id = -1;
      $q = "select max(mealid) from meals";
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"No response from database"; 
      else{
        while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
          $id = $row[0];
        }
      }
      $id++;
      $name = $_POST["meal_name"];
      $name = "'".$name."'";
      $calories = $_POST["calories"];
      $carbs = $_POST["carbs"];
      $fats = $_POST["fats"];
      $fiber = $_POST["fiber"];
      $c = ",";
      $q = "insert into meals values( ";
      $q = $q.$id.$c.$name.$c.$calories.$c.$carbs.$c.$fats.$c.$fiber;
      $q = $q.")";
      //echo $q;
      $query_id = oci_parse($con, $q);		
      $r = oci_execute($query_id);
      if (!$r) echo"INSERTION UNSUCCESSFUL"; 
      else {
          echo "<div class='container'>"; 
          echo "<h4>Meal was added successfully</h4>";
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