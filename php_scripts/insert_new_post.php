<?php
session_start();
/*Code written by Ashish Trivedi*/

//including common mongo connection file
include('../mongo_connection.php');

//generating a new post id
$post_id = new MongoId();
//getting user id from the ajax POST parameters
$user_id = new MongoId($_POST['user_id']);
//Session User ID
$user_session_id = new MongoId($_SESSION['user_id']);
//getting post text from the ajax POST parameters
$post_text = $_POST['post_text'];
//generating post timestamp from the post id 
//the statement should differ based on the date format needed and time zone 
$timestamp=date('D, d-M-Y', $post_id->getTimestamp()+19800);

//Check if the user ID coming from AJAX parameter is same as Session User ID
if($user_id == $user_session_id)
{
//selecting posts_collection
$collection = $database->selectCollection('posts_collection');
//creates document for inserting new post
$new_post_mongo = array ( '_id'=> $post_id,
                          'post_author_id' => $_SESSION['user_id'],
                          'post_text' => $post_text,
                          'total_likes' => 0,
  						  'likes_user_ids' => array (),
                          'comments' => array (),
						  'total_comments' => 0,
                          'timestamp'=>$timestamp
                          );
$collection->insert($new_post_mongo);                          
}
         
?>         

<!-- Returns html data for the newly inserted post. 
This data will be sent as output to the AJAX POST call. 
Then from the javascript, this data containing the new post will be prepended to the page.
-->      
<?php
//So here we will be initializing variables which that we will be using to display the new post content
//Note that this data is in the same format as seen on index.php page

//Profile picture of post author 
$post_author_profile_pic = $_SESSION['user_profile_pic'];
//Name of post author
$post_author = $_SESSION['user_name'];
//ID of span displaying Like/Unlike option
$post_like_unlike_id=$post_id.'_like_unlike';
//ID of span displaying number of likes
$post_like_count_id=$post_id.'_like_count';
//Number of likes
$post_like_count=0;
//ID of span displaying number of comments
$post_comment_count_id = $post_id.'_comment_count';
//Number of comments
$post_comment_count=0;
//Post timestamp
$post_timestamp=$timestamp;
//In the comments box list, the last comment box is empty so that the user can comment there
//ID for that last self comment box
$post_self_comment_id=$post_id.'_self_comment';
//ID of textbox in the last comment box
$post_comment_text_box_id=$post_id.'_comment_text_box';

$like_or_unlike='Like';
?>
 
<div class="post_wrap" id="<?php echo $post_id;?>">
                <!-- div to display post author's profile picture -->
				<div class="post_wrap_author_profile_picture">
					   <img src="images/<?php echo $post_author_profile_pic; ?>" />
				</div>  
				<div class="post_details">  
                    <!-- div to display post author's name -->
                    <div class="post_author">
					 <?php echo $post_author ?> 
				    </div>
                    <!-- div to display post's text-->
					<div class="post_text">
						<?php echo $post_text; ?>
				    </div>
			    </div>   
                <!-- div to display all the comments related to post -->
                <div class="comments_wrap">
        					<span>
                                <span><img src="images/like.png" /></span>
                                <!-- span to display Like/Unlike option -->
        						<span class="post_feedback_like_unlike" id="<?php echo $post_like_unlike_id;?>"  
								      onclick="post_like_unlike(this,'<?php echo $_SESSION['user_id']; ?>')"><?php echo $like_or_unlike; ?></span>
								<!-- span to display number of likes -->
                                <span class="post_feedback_count" id="<?php echo $post_like_count_id; ?>"><?php echo $post_like_count;?></span>
        					</span>
        					<span>
                                <span class="post_feedback_comment"> <img src="images/comment.png" /> Comment</span>
                                <!-- span to display number of comments -->
                                <span class="post_feedback_count" id="<?php echo $post_comment_count_id; ?>"><?php echo $post_comment_count;?></span>
                            </span>
                            <!-- span to display post timestamp -->
      						<span class="post_timestamp">
      								<?php echo $post_timestamp; ?>
      						</span>                   
                              
                          
                          <!-- div to display a default empty comment box at the end for the current user to comment-->
                           <div class="comment" id="<?php echo $post_self_comment_id; ?>">
                				<div class="comment_author_profile_picture">
        							<img src="images/<?php echo $_SESSION['user_profile_pic']; ?>" />
        						</div>
        						<div class="comment_text">
    	            				<textarea placeholder="Write a comment..." id="<?php echo $post_comment_text_box_id; ?>" onKeyPress="return new_comment(this,event,'<?php echo $_SESSION['user_id']; ?>')" ></textarea>
       		   					</div>
            				</div>
                 </div> 
       </div> 
       <hr class="soften special">
