<?php session_start();

//including common mongo connection file
include('../mongo_connection.php'); 

//selecting posts_collection
$collection = $database->selectCollection('posts_collection');
//Getting post id from the ajax POST parameters
$post_id = new MongoId($_POST['post_id']);
//getting user id from the ajax POST parameters
$user_id = new MongoId($_POST['user_id']);
//Session User ID
$user_session_id = new MongoId($_SESSION['user_id']);

//Check if the user ID coming from AJAX parameter is same as Session User ID
if($user_id == $user_session_id)
{
//Removing the User ID from the list of likes_user_ids and decreasing the total_likes count by 1     
$collection->update(array('_id' => $post_id),array('$pull' => array('likes_user_ids'=>$user_id),'$inc' => array('total_likes' => -1 )) );

}

?>
