<?php
/*
Plugin Name: Post View by User
Description: This plugin will help you to check if a user has view the post or not. Author email: <a href="mailto:sshuvro58@gmail.com">sshuvro58@gmail.com</a>
Author: Mithun Sarker Shuvro
Version: 2.0
License: GPLv2
*/

// function to display number of posts.
function getPostViews($postID){
    $count_key = 'post_views_by_user';
    $count = get_post_meta($postID, $count_key, true);   
    $total_item = count($count);
    if($count!=''){
        $show_data = $count;
        $list_user = '';
        $i= 0;
        foreach ($show_data as $key => $value) {
            $user_info = get_userdata($key);
            $userName=  $user_info->user_login;
            if(++$i != $total_item){
                $list_user .= '<b>'.$userName.'</b>'.'-'.$value.', '.'<br/>';
            }else{
                $list_user .= '<b>'.$userName.'</b>'.'-'.$value;
            }
        }
    }
    if($list_user == ''){
        return null;
    }  else {
        return $list_user;       
    }

}
 
// function to count views.
function setPostViews($postID) {
    $count_key = 'post_views_by_user';
    $count = get_post_meta($postID, $count_key, true);
    //echo '<script type="text/javascript">console.log('.$count.')</script>';
    $user_array= array();
    if($count==''){
        if(is_user_logged_in()){
            $user_ID = get_current_user_id();
            $user_array[$user_ID] = date("Y-m-d H:i:s"); 
            add_post_meta($postID,'post_views_by_user' ,$user_array);
        }
        
        
   }
   else{
        if(is_user_logged_in()){
            $user_ID = get_current_user_id();
            var_dump($count[$user_ID]);
            if($count[$user_ID] ==null){
                $count[$user_ID] =date("Y-m-d H:i:s"); 
                delete_post_meta($postID, $count_key);
                add_post_meta($postID,'post_views_by_user' ,$count);
            }else{
                return;
            }

        }
    }
}
 
 
// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'posts_column_views');
add_action('manage_posts_custom_column', 'posts_custom_column_views',5,2);
function posts_column_views($defaults){
    $defaults['post_views'] = __('Viewed by');
    return $defaults;
}
function posts_custom_column_views($column_name){
    if($column_name === 'post_views'){
        echo getPostViews(get_the_ID());
    }
}



function work(){
	if(is_single(get_the_ID())){
			setPostViews(get_the_ID());
	}
}

add_action('wp_head','work');
