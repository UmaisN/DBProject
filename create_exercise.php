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
    $id = -1;
      if ($con){  //if my connection with databae is successful
          //echo"<p>Connection Successful</p>";
          $q = "select max(exid) from exercise ";
          $query_id = oci_parse($con, $q);		
          $r = oci_execute($query_id);
          $user = "null";
          if (!$r) echo"No response from database"; 
          else {
            while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)){
                $id = $row[0];
            }
          } 
            echo "<div class = 'container'><h1>Fit Me</h1>";
            echo "<hr>";
            echo "<h2>Create a new Exercise</h2>";
            echo "<hr>";
            echo "<div class='col-md-12'>";
            echo '<form action="create_exercise.php" method="POST" class="form-group">';
            echo '<label for="name">Exercise Name</label><input type="text" name="name" class="form-control"> <br>';
            echo '<label for="part">Body Part<input name="part" type="text" class="form-control"><br>';
            echo '<label for="calories">Calories per rep<input name="calories" type="number" class="form-control"><br>';
            echo '<input type="submit" class="form-group btn btn-primary" name ="submit" value="create">';
            echo '</form>';
            echo "</div>";

            if (isset($_POST["calories"]) || isset($_POST["name"])){
            $id++;
            $name = $_POST["name"];
            $name = "'".$name."'";
            $part = $_POST["part"];
            $part = "'".$part."'";            
            $calories = $_POST["calories"];
            $c = ",";
            $q = "insert into exercise values( ";
            $q = $q.$id.$c.$name.$c.$part.$c.$calories;
            $q = $q.")";
            //echo $q;
            $query_id = oci_parse($con, $q);		
            $r = oci_execute($query_id);
            if (!$r) echo"INSERTION UNSUCCESSFUL"; 
            else {
                echo "<div class='container'>"; 
                echo "<h4>Exercise was added successfully</h4>";
                echo "</div>";
                $q = "commit";
                $query_id = oci_parse($con, $q); 		
                $r = oci_execute($query_id);
            }
        }
    } 
    else {
        echo"<p>Could not connect to Oracle</p>";  
    }
    
?>  
</div>

</body>
</html>