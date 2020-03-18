<?php 
//include 'classes/User.php';
include 'classes/Post.php';
include 'header.php';

    
if(isset($_POST['post'])){
    $uploadOk = 1;
    $imageName = $_FILES['fileToUpload']['name'];
    $errorMessage = "";
    
    if($imageName != ""){
        $targetDir = "assets/images/posts/";
        $imageName = $targetDir . uniqid() . basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);
        
        if($_FILES['fileToUpload']['size'] > 10000000){
            $errorMessage = "Sorry your file is too large";
            $uploadOk = 0;
        }
        
        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg"){
            $errorMessage = "Sorry, only jpeg, jpg and png files are allowed";
            $uploadOk = 0;
        }   
        
        if($uploadOk){
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],
            $imageName)){
                //image Upload Ok
            }
            else{
                $uploadOk = 0;
            }
        }
    }
    
    if($uploadOk){
        $post = new Post($con, $userLoggedIn);
        $post->submitPost($_POST['post_text'],'none', $imageName);
    }
    else{
        echo "<div style='text-align: center;' class='alert alert-danger'> $errorMessage </div>";
    }
}

?>

<style type="text/css">
    .wrapper{
        width:75%;
    }
</style>


<div class="index-wrapper">
    <div class="info-box">
        <div class="info-inner">
            <div class="info-in-head">
                <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['cover_pic']; ?>"></a>
            </div>
            <div class="info-in-body">
                <div class="in-b-box">
                    <div class="in-b-img">
                        <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
                    </div>
                </div>
                <div class="info-body-name">
                    <div class="in-b-name">
                        <div><a href="<?php echo $userLoggedIn; ?>"><?php echo $user['first_name'] . " " . $user['last_name']; ?></a>
                        </div>
                        <span><small><a href="<?php echo $userLoggedIn; ?>"><?php echo "@" . $user['username'] ?></a></small></span>
                    </div>
                </div>
            </div>
            <div class="info-in-footer">
                <div class="number-wrapper">
                    <div class="num-box">
                        <div class="num-head">
                            POSTS
                        </div>
                        <div class="num-body">
                            <?php echo $user['num_posts']; ?>
                        </div>
                    </div>
                    <div class="num-box">
                        <div class="num-head">
                            LIKES
                        </div>
                        <div class="num-body">
                            <span class="count-likes">
                                <?php echo $user['num_likes']; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="post-wrap">
        <div class="post-inner">
            <div class="post-h-left">
                <div class="post-h-img">
                    <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic'] ?>"></a>
                 </div>
            </div>
            
            <div class="post-body">
                <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
                    <textarea class="status" name="post_text" id="post_text" placeholder="Type Something here!" rows="4" cols="50"></textarea>
                    <div class="hash-box">
                        <ul>
                        </ul>
                    </div>
            </div>
                <div class="post-footer">
                    <div class="p-fo-left">
                        <ul>
                            <input type="file" name="fileToUpload" id="fileToUpload"/>
                            <label for="fileToUpload"><i style="color:#3875C5" class="fas fa-camera"></i></label>
                            <span class="tweet-error"></span>
                        </ul>
                    </div>
                    <div class="p-fo-right">
                        <input type="submit" name="post" value="SHARE">
                    </form>
                    </div>
                </div>
            </div>
         <div class="main_column_home">
                <div class="posts_area"></div>
                <img id="loading" src="assets/images/icons/loading.gif">
             <?php
                $limit = 10;
             $posts = new Post($con, $userLoggedIn);
              $posts->IndexPosts($_REQUEST, $limit); ?>
        </div>
        
        </div>
    </div>

<script>

    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    
    $(document).ready(function(){
        $('#loading').show();
        
        $.ajax({
            url: "includes/handlers/ajax_load_posts.php",
            type: "POST",
            data: "page=1$userLoggedIn=" + userLoggedIn,
            cache: false,
            
            success: function(data){
                $('#loading').hide();
                $('.posts_area').html(data);
            }
        });
    });

</script>
       

<script>
    var userLoggedIn = '<?php echo $userLoggedIn; ?>';
    
    $(document). ready(function() {
        $('#loading').show();
                       
    $.ajax({
        url: "includes/handlers/ajax_load_posts.php",
        type: "POST",
        data: "page=1&userLoggedIn=" + userLoggedIn,
        cache: false,
        
        sucessl: function(data) {
            $('#loading').hide();
            $('.posts_area').html(data);
        }
    });
        
    $(window).scroll(function() {
        
        var height = %('.posts_area').height();
        var scroll_top = $(this).scrollTop();
        
        var page = $('.posts_area').find('.nextPage').val();
        var noMorePosts = $('.posts_area').find('.noMorePosts').val();
                                 
        if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false'){
            
            $('#loading').show();
            
            var ajaxReq = $.ajax ({
                url: "includes/handlers/ajax_load_posts.php",
                type: "POST",
                data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                cache: false,    
                
                success: function (response) {
                    $('.posts_area').find('.nextPage').remove();
                    $('.posts_area').find('.noMorePosts').remove();
                    $('.posts_area').find('.noMorePostsText').remove();
                    
                    $('#loading').hide();
                    $('.posts_area').append(response);
                }
            });
        } //End if
        return false;
    });

</script>
