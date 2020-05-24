<?php
require("db/users.php");
date_default_timezone_set('Europe/Istanbul');
if($_POST["submit"] == "login" && isset($_POST['pass']) && isset($_POST['email'])) 
{
    session_start();
	$objUser = new users;
	$objUser->setEmail($_POST['email']);
	$objUser->setPassword($_POST['pass']);
	$objUser->setLoginStatus(1);
 	$objUser->setLastLogin(date('Y-m-d H:i:s'));
	$userData = $objUser->login();
 	if(is_array($userData)) {
        $objUser->setId($userData['id']);
        $objUser->setName($userData['name']);
 		if($objUser->updateLoginStatus()) {
 			echo "User login..";
 			$_SESSION['user'][$userData['id']] = $userData;
 			header("location: chatroom.php");
 		} else {
             echo "Failed to login.";
             header("refresh:1;url=index.php");
 		}
     }
     else{
        echo "Failed to login.";
        header("refresh:1;url=index.php");
     }
}
else if($_POST["submit"]== "signup" && isset($_POST['upass']) && isset($_POST['uemail']) && isset($_POST['uname']) )
{
    session_start();
	$objUser = new users;
	$objUser->setEmail($_POST['uemail']);
	$objUser->setName($_POST['uname']);
	$objUser->setPassword($_POST['upass']);
	$objUser->setLoginStatus(1);
    $objUser->setLastLogin(date('Y-m-d H:i:s'));
    $boolemail = $objUser->getUserByEmail();
    if(!is_array($boolemail)){
        if($objUser->save()){
            $lastId = $objUser->dbConn->lastInsertId();
            $objUser->setId($lastId);
			$_SESSION['user'][$lastId] = [ 
				'id' => $objUser->getId(), 
				'name' => $objUser->getName(), 
				'email'=> $objUser->getEmail(), 
				'login_status'=>$objUser->getLoginStatus(), 
				'last_login'=> $objUser->getLastLogin() 
            ];
            header("location: chatroom.php");
        }
        else{
            echo "Failed..";
            header("refresh:1;url=index.php");
        }
    }
}