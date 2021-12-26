<!DOCTYPE html>
<html lang="en" >

<head style="font-family:Arial, Helvetica, sans-serif">
    <title>Fit Me!</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap_blue.css" rel="stylesheet">
    
</head>

<body style="font-family:Arial, Helvetica, sans-serif">

    <div class = "container"><h1>Welcome to Fit Me!</h1>
    <hr/>
    
    <h2>Please enter your login details below</h2>
      <form name="Login" action="home.php" method="POST" autocomplete="on" >Email
        :&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp; <input name="email" placeholder="someone@example.com" type="text">
        <br><br>
        Password: &nbsp; <input name="password" type="password"><br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input name="login" id ='sign_in' value="Sign in" type="submit" class="btn-primary btn-success btn-block">
        </form>
        <br>
         <p>New here? &nbsp;<input name="sign up" id="sign_up" value="Sign up!" class="btn-success"type="button"></p>
</div>
  <script type="text/javascript"> //use this to go to the login page 
    document. getElementById("sign_up"). onclick = function () {
    location.href = "sign_up.php";
    };
  </script>

    <?php
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
        if ($con) {  //if my connection with databae is successful
            echo "Connection Successful";
        } else {
            echo "Could not connect to Oracle";
        }    
    }
    ?>

</body>

</html>