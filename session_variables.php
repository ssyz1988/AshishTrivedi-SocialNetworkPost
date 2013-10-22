<?php
session_start();

/*Code written by Ashish Trivedi*/

//Set User ID, User Name and User Profile Picture in SESSION variables as these will be frequently used
$_SESSION['user_id']=new MongoId("5146bb52d8524270060001f4");
$_SESSION['user_name']="George Hanks";
$_SESSION['user_profile_pic']="profile_pic.jpg";

?>