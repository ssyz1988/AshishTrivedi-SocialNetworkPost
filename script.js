/*Code written by Ashish Trivedi*/

//Function to insert new post
function new_post(user_session_id) 
{    
    //Getting the post text
	var new_post_text = $('#post_textarea').val();
    
    //if condition to ckeck if the user has not entered blank comment
	if(!$.trim(new_post_text))
	{
	    alert("Please enter some text in the post");
		return;	
	} 
    
    //Insert_new_post.php contains php script to insert new post
    var insert_new_post_filename='php_scripts/insert_new_post.php'; 
    //Ajax POST request to call insert_new_post.php
    //Sends User Id and Post Text as parameters    
   	$.post('php_scripts/insert_new_post.php',{user_id: user_session_id,post_text: new_post_text},function(output){
   	    //The output contains html content of the new post as returned from insert_new_post.php
        //Prepending this output to the existing post stream    
   	    $('#post_stream').prepend(output);
        //adding sliding animation
    	var new_post_id=$("#post_stream div:first-child").attr("id");
    	$("#"+new_post_id).hide().slideDown();
        //clearing the post text area after inserting the post 
	    $('#post_textarea').val(null);
	});   
}
    
//Function to like/unlike post        
function post_like_unlike(post_id_like_unlike,user_session_id)
{
    //Getting type whether user clicked on Like or Unlike based on the text
	var type = ($('#'+(post_id_like_unlike.id)).html());
    //Splitting post_id_like_unlike to get the post id for which the user clicked Like/Unlike
	var post_id_of_like_unlike= ((post_id_like_unlike.id).split("_")) [0];
    //Getting the span id which shows post's like count
    var post_id_like_count = post_id_of_like_unlike+'_like_count';
    
    //if user clicked Like
	if (type == 'Like')
    {
        //Ajax POST request to call post_like.php
        //Sends Post ID and User ID as parameter
    	$.post('php_scripts/post_like.php',{post_id:post_id_of_like_unlike,user_id:user_session_id},function(output){
                $('#'+(post_id_like_unlike.id)).html('Unlike');
                //Increasing the previous like count by 1
                $('#'+(post_id_like_count)).html(
            	parseInt($('#'+(post_id_like_count)).html())+1
            	);
	       });
     }
    //if user clicked Unlike 
    else 
    {
        //Ajax POST request to call post_unlike.php
        //Sends Post ID and User ID as parameter
    	$.post('php_scripts/post_unlike.php',{post_id:post_id_of_like_unlike,user_id:user_session_id},function(output){
            	$('#'+(post_id_like_unlike.id)).html('Like');
                //Decreasing the previous like count by 1
            	$('#'+(post_id_like_count)).html(
            	parseInt($('#'+(post_id_like_count)).html())-1
            	);
    	   });
    }
};    

//Function to insert new comment
function new_comment(comment_box_id,return_key_event,user_session_id)
{
	//if condition to check if the user clicked Enter
	if(return_key_event && return_key_event.keyCode == 13)
       {
            //if condition to ckeck if the user has not entered blank comment
        	if(!$.trim($('#'+(comment_box_id.id)).val()))
        	{
        	    alert("Please enter some text in the comment");
        		return;	
        	} 
    	    
            //Getting comment text
    	    var new_comment_text= $('#'+(comment_box_id.id)).val();
            //Getting post id of the post on which the user has commented
    	    var post_id_of_comment= ((comment_box_id.id).split("_")) [0];
            //Getting span id which shows the comment count
            var post_comment_count = post_id_of_comment+'_comment_count';
            
            //Ajax POST request to call new_comment.php
            //Sends Post ID, Comment text and User ID as parameter	
            $.post('php_scripts/new_comment.php',{post_id:post_id_of_comment,comment_text:new_comment_text,user_id:user_session_id},function(output){
                    //placing the new comment before the last self-comment box
                	$('#'+post_id_of_comment+'_self_comment').before(output);
                    //increasing number of comments by 1
                	$('#'+(post_comment_count)).html(
                	parseInt($('#'+(post_comment_count)).html())+1
                	);
                    //clearing comment text in the textarea
                	$('#'+(comment_box_id.id)).val(null);
        	}); 	
      }	
};