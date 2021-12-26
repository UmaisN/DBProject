<!DOCTYPE html>
<html lang="en" >

<head style="font-family:Arial, Helvetica, sans-serif">
    <title>Fit Me Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap_blue.css" rel="stylesheet">
    
</head>

<body style="font-family:Arial, Helvetica, sans-serif">
<div class="container">
    <h1>Register for Fit me</h1>
    <hr>
    <form action="sign_up.php" method="POST" class='form-group'>
            <label for="first_name">First Name</label><input type="text" name="first_name" class='form-control'> <br>
            <label for="last_name">Last Name</label><input type="text" name="last_name" class='form-control'> <br>
            <label for="email">Email</label><input type="text" name="email" class='form-control'> <br>
            <label for="username">Username</label><input type="text" name="username" class='form-control'> <br>
            <label for="password">Password<input name="password" type="password" class='form-control'><br>
            <label for="dob">Date of Birth</label><input type="date" name="birthday" max="2003-01-01" value="1995-01-01" class='form-control'> <br>
            <label for="gender">Gender</label>
            <select name='gender' class="form-select form-control" aria-label="Default select example">
                <option selected>Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select><br>
            <input type = "submit" class='form-group btn btn-primary' name ="submit" value="submit">
    </form>
</div>    
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if ($_POST) {
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
            //generating surrogate key for user using max if ids from user table
            $query = "select max(fusrid) from fituser";
            $query_id = oci_parse($con, $query);
            $r = oci_execute($query_id);
            $row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS);
            $row[0]++;
            $fusrid = $row[0];
            //setting the rest of the values to insert 
            $c = ",";
            $first_name = "'".$_POST['first_name']."'";
            $last_name = "'".$_POST['last_name']."'";
            $email = "'".$_POST['email']."'";
            $username = "'".$_POST['username']."'";
            $password = "'".$_POST['password']."'";
            $birthday = $_POST['birthday'];
            $gender = "'".$_POST['gender']."'";
            $dob = "to_date('".$birthday."','yyyy/mm/dd')";
            $q = "insert into fituser(fusrid,firstname,lastname,password,dob,gender,email,username) values (";
            $q = $q.$fusrid.$c.$first_name.$c.$last_name.$c.$password.$c.$dob.$c.$gender.$c.$email.$c.$username.")";
            //$q = "insert INTO fituser(fusrid,firstname,lastname,password,dob,gender,email,username) values (2,'Shayan','Zuberi','12345',to_date('11/09/1999','dd/mm/yyyy'),'male','shayanamir98@gmail.com','shayanzuberi')";
            $query_id = oci_parse($con, $q);		
            $r = oci_execute($query_id);
            if (!$r) echo"INSERTION UNSUCCESSFUL"; 
            else {
                echo "<div class='container'>"; 
                echo "<h4>Welcome on board, ".$_POST["first_name"]."</h4>";
                echo "</div>";
                $q = "commit";
                $query_id = oci_parse($con, $q); 		
                $r = oci_execute($query_id);
            }
            //while($row = oci_fetch_array($query_id, OCI_BOTH+OCI_RETURN_NULLS)) 
            //{ 
            //   echo "here".$row['id']; 
            //}

            //committing changes 

        } 
        else {
            echo"<p>Could not connect to Oracle</p>";
        }    
    }
    ?>

</body>

</html>