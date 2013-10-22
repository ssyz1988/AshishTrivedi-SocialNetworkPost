<?php
session_start();

/*Code written by Ashish Trivedi*/

//including session_variables file for initializing User ID, Name, etc.
include('session_variables.php');
//including common mongo connection file
include('mongo_connection.php');
?>

<!--Referencing common Javascript and CSS files-->
<link rel="stylesheet" href="style.css"/>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="script.js"></script>

<html>
<body>
<div id="div_main">
    <!--div_new_post for section to create new post -->
	<div id="div_new_post">
		<div id="div_post_content">
			<textarea id="post_textarea">
			</textarea>
		</div>
		<div class="div_post_submit">
			<input type="button" value="Create New Post" id="btn_new_post" onClick="new_post('<?php echo $_SESSION['user_id']; ?>')" class="button_style"/>
		</div>
	</div>
    <!--div_new_post ends-->
    
    <!--post_stream for displaying the post stream -->
    <div id="post_stream">
    <?php
    //Selecting the posts_collection
    $collection = $database->selectCollection('posts_collection');
    //Retreiving all the posts in the collection
    //If you want to retreive specific posts based on useer, relations, etc. put filter condition in find 
    $posts_cursor=$collection->find()->sort(array('_id'=>-1)); 

    //Iterating over all the retreived posts
    foreach($posts_cursor as $post)
    {
        //Post ID
        $post_id=$post['_id'];
        //Post text
        $post_text=$post['post_text'];
        //Number of likes
        $post_like_count=$post['total_likes'];
        //Number of comments
        $post_comment_count=$post['total_comments'];
        //Post timestamp
        $post_timestamp=$post['timestamp'];
        //User ID of the post author
        $post_author_id=$post['post_author_id'];
        
        
        //Retreiving name of the author from the users collection based on the $post_author_id
        $collection = $database->selectCollection('users');
        $post_author_details = $collection->findOne(array('_id' =>$post_author_id));
        //Name of post author
        $post_author = $post_author_details['name'];
        //Profile picture of post author 
        $post_author_profile_pic = $post_author_details['profile_pic'];

        
        //ID of span displaying Like/Unlike option
        $post_like_unlike_id=$post_id.'_like_unlike';
        //ID of span displaying number of likes
        $post_like_count_id=$post_id.'_like_count';
        //ID of span displaying number of comments
        $post_comment_count_id = $post_id.'_comment_count';
        //In the comments box list, the last comment box is empty so that the user can comment there
        //ID for that last self comment box
        $post_self_comment_id=$post_id.'_self_comment';
        //ID of textbox in the last comment box
        $post_comment_text_box_id=$post_id.'_comment_text_box';
        
        
        //If the user has previously liked the post the option of 'Unlike' should be shown.
        //For this we check if the user's user id is present in likes_user_ids array which stores user ids of all those who have liked 
        //Else the default Like option should be shown 
        if(in_array($_SESSION['user_id'],$post['likes_user_ids']))
        	{
        	    //User had already liked the post
        		$like_or_unlike='Unlike';
        	}
        else
        	{
        	    //User has not liked the post 
        		$like_or_unlike='Like';
        	}
    ?>

      <!-- div to display all the post content - to be repeated for each post -->
      <div class="post_wrap" id="<?php echo $post['_id'];?>">
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
                              
                           <?php
                           //iterating over all the comments
                           for($i=0;$i<$post_comment_count;$i++)
                            {
                                //comment id
                                $comment_id=$post['comments'][$i]['comment_id'];
                                //comment text
	                            $comment_text=$post['comments'][$i]['comment_text'];
                                //comment author user id  
                                $comment_author_id=$post['comments'][$i]['comment_user_id'];
                                //retreiving comment author's details fromm the users collection
                                $collection = $database->selectCollection('users');
	                            $comment_author_details = $collection->findOne(array('_id' => new MongoId($comment_author_id)));
                                //comment author name
                               	$comment_author = $comment_author_details['name'];
                                //comment author profile picture name
                                $comment_author_profile_pic = $comment_author_details['profile_pic'];
                           ?>                
                           <!-- div for displaying each comment - to be repeated for each comment -->
                           <div class="comment" id="<?php echo $comment_id; ?>">
                               <!-- div to display comment author profile picture -->
        						<div class="comment_author_profile_picture">
        							<img src="images/<?php echo $comment_author_profile_pic; ?>"/>
        						</div>
			            		<div class="comment_details">
                                    <!-- div to display comment author's name -->
                                	<div class="comment_author" >
			                    		<?php echo $comment_author; ?>
			                    	</div>
                                    <!-- div to display comment text -->
			                    	<div class="comment_text" >
			                    		<?php echo $comment_text; ?>
			                    	</div>
			           			</div>
      			 		   </div>
                            <?php
                              }
                            ?>   
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
    <?php
    }
    ?>
    
    </div>
</div>
</body>
</html>