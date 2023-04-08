<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Password</title>
</head>
<body style="font-family: Arial; font-size: 18px; text-align: center">
<?php
if($_GET['id'] && $_GET['email'] && $_GET['pass']){

$db_host="localhost";//192.185.131.105
$db_user="cityciud_diego";
$db_pass="jnuMu4iD%Yh6";//R6!D[d$0RoDf
$db_name="cityciud_diegosierra";

//
$conn=@mysqli_connect($db_host, $db_user, $db_pass, $db_name);
mysqli_query($conn,"SET CHARACTER SET 'utf8'");

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysqli->set_charset("utf8");

 $id = addslashes($_GET['id']) ;
 $email = addslashes($_GET['email']);
 $pass = addslashes($_GET['pass']); 
//
 //or die($mysqli->error); 
if($mysqli->query("UPDATE users SET `password`='$pass' WHERE id='$id' AND email='$email'")){
echo '<h3>You can now use your new password.</h3> <br><a href="/admin">Go to the Login panel</a>';
}

}
?>  
</body>
</html>

