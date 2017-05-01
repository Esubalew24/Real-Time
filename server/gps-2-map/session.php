<?php
   session_start();
   include('api/database.php');
      
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($db_connection,"SELECT username FROM users WHERE username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:index.php");
   }
?>