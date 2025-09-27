<?php
$server    = "localhost";
$username = "root";
$password = "";
$dbname = "healthsphere";


$con = mysqli_connect($server, $username, $password, $dbname);

if(!$con)
{
    echo "not connected";
}



if(isset($_POST['login']))
{
   $username=  $_POST['username'];
   $pwd=  $_POST['password'];

   $query = "SELECT * FROM users WHERE email = '$username' && password = '$pwd'";
   $data = mysqli_query($con , $query);
   $total = mysqli_num_rows($data);
echo $total;


   if($total == 1)
   {
    header('location:newpatient.html');
   }
   else
   {
    echo "login failed";
   }
}
?>