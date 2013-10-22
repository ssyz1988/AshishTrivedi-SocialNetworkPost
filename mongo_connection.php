<?php
/*Code written by Ashish Trivedi*/

//This file contains the common database connection code used accross files
//If you have modified username and password for connection, define it here     
$connection = new Mongo();
$database = $connection->selectDB('project');

?>