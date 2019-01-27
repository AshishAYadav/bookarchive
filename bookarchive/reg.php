<?php

session_start();

function logout(){
    session_unset(); 
    // destroy the session 
    session_destroy(); 
    header('Location: ./login.php');
    exit();
}
if(isset($_SESSION['ukey'])){
    header('Location: ./profile.php');
    exit();
}
else{
$servername = "db4free.net";
$username = "YXNoMjRhbml5";
$password = "YXNoMjRhbml5";
$dbname = "ashish_db";
$con = new mysqli($servername, base64_decode($username), base64_decode($password), $dbname);
if ($con->connect_errno) {
    echo "Failed to connect to MySQL: " . $con->connect_error;
}
if(!empty($_POST['email'])||!empty($_POST['userid'])||!empty($_POST['pass'])||!empty($_POST['cnfpass'])){
    $email = $_POST['email'];
    $usr = $_POST['userid'];
    $pass =$hashpass= hash('sha256',$_POST['pass']);
    $cnfpass =$hashpass= hash('sha256',$_POST['cnfpass']);
    if ($pass == $cnfpass){
        $check =$con->query("select id from usr where (usr_id ='".$usr."' || email = '".$email."')");
    if ($check->num_rows > 0) {
        echo "<script>alert('User already exists');</script>";
        header('Location: ./login.php');
        echo "  Registration Failed!";
        exit();
        }
        else{
            $db_usr = "insert into usr(email, usr_id,pass, ukey)  values('".$email."','".$usr."','".$pass."','".$_POST['pass']."')";

        if ($con->query($db_usr) === TRUE) {
                session_start();
                $_SESSION['ukey']= $_POST['pass'];
                header('Location: ./profile.php');
                echo  $_SESSION['ukey']." successfully Registered!";
                exit();
                
        }else{
            header('Location: ./login.php?signup=true');
            echo "  Registration Failed!";
            exit();
        }
        }
    }  
    else{
         echo "<script>alert('password do not match');</script>";
         header('Location: ./login.php?signup=true');
         echo "  Registration Failed!";
         exit();
    }
}  
else{
    echo "<script>alert('All fields are mandatory !');</script>";
    header('Location: ./login.php?signup=true');
    echo "  Registration Failed!";
    exit();
}

}

?>

