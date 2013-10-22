<?php session_start();

/*Code written by Ashish Trivedi*/

//including common mongo connection file
include('../mongo_connection.php'); 


$collection = $database->selectCollection('posts_collection');

$comment_text=$_POST['comment_text'];
$post_id = new MongoId($_POST['post_id']);
$user_id =new MongoId($_SESSION['user_id']);
//$comment_id= str_replace('.','',sprintf("%.8f",(microtime(true))));
$comment_id=new MongoId();


																								

$collection->update(array('_id' => $post_id),array('$push' => array('comments'=>
array (
      'comment_id'=>$comment_id,
	  'comment_user_id' =>$user_id,
      'comment_text' => $comment_text
    )
),'$inc' => array('total_comments' => 1 )));													
?>



<div class="comment" id="<?php echo $comment_id; ?>">
        						<div class="comment_author_profile_picture">
        							<img src="images/<?php echo $_SESSION['user_profile_pic']; ?>"/>
        						</div>
			            		<div class="comment_details">
                                	<div class="comment_author" >
			                    		<?php echo $_SESSION['user_name']; ?>
			                    	</div>
			                    	<div class="comment_text" >
			                    		<?php echo $comment_text; ?>
			                    	</div>
			           			</div>
</div>

