<?php
/*
Plugin Name: ncc_ratings
Description: Using this plugin you can let users ad their own rating to your posts.
Version: 1.0.0
Author: NCC Wordpress
*/

/*  Copyright 2012 NCC Wordpress

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Hook for adding admin menus
add_action('admin_menu', 'ncc_ratings_add_pages');

if (get_option("mt_ratings_plugin_support")=="Yes" || get_option("mt_ratings_plugin_support")=="") {
add_action('wp_footer', 'ratings_plugin_support');
}

add_filter('the_content', 'show_ratings');

// action function for above hook
function ncc_ratings_add_pages() {
    add_options_page(' NCC Ratings', 'NCC Ratings', 'administrator', 'ncc_ratings', 'ncc_ratings_options_page');
}

// ncc_ratings_options_page() displays the page content for the Test Options submenu
function ncc_ratings_options_page() {

    // variables for the field and option names 
    $opt_name_6 = 'mt_ratings_plugin_support';
    $hidden_field_name = 'mt_nofollow_submit_hidden';
    $data_field_name_6 = 'mt_ratings_plugin_support';

    // Read in existing option value from database
    $opt_val_6 = get_option( $opt_name_6 );
	
if (!$_POST['feedback']=='') {
$my_email1="feedback@nccwordpress.com";
$plugin_name="NCC Ratings";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$subject=$_POST['subject'];
$feedback_feedback=$_POST['feedback'];
$headers1 = "From: feedbackd@nccwordpress.com";
$emailsubject1=$plugin_name." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);

?>

<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>

<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val_6 = $_POST[$data_field_name_6];

        // Save the posted value in the database
        update_option( $opt_name_6, $opt_val_6 );  

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Settings saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'NCC Ratings Plugin Options', 'mt_trans_domain' ) . "</h2>";

    // options form
    
    $change5 = get_option("mt_nofollow_plugin_support");

if ($change5=="Yes" || $change5=="") {
$change5="checked";
$change51="";
} else {
$change5="";
$change51="checked";
}

    ?>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Keep link to support this plugin?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="Yes" <?php echo $change5; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_6; ?>" value="No" <?php echo $change51; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p><hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p>

</form>

<h3>Give Me Feedback!</h3>
<form name="form2" method="post" action="">
<p><?php _e("E-Mail (Optional):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Subject:", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<p><?php _e("Comment:", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ) ?>" />
</p><hr />
</form>
</div>
<?php
}

function ratings_action( $post_id, $action = 'get', $rating = '' ) {
  
  //Let's make a switch to handle the three cases of 'Action'
  switch ($action) {
    case 'update' :
      
      if( $rating != '' ) {
	  $value1=get_post_meta($post_id, 'postratings-no', false);
	  $value2=get_post_meta($post_id, 'postratings-total', false);
	  $value1=$value1[0];
	  $value2=$value2[0];
	  $value3=$value2+$rating;
	  $value4=$value1+1;
	  
	  if ($value1!="" && $value2!="") {
        update_post_meta( $post_id, 'postratings-total', $value3 );
		update_post_meta( $post_id, 'postratings-no', $value4 );
        return true;
        } else {
		add_post_meta($post_id, 'postratings-no', '1', true);
		add_post_meta($post_id, 'postratings-total', $rating, true);
		return true;
		}
		} else { return false; }
      
    case 'get' :

      $total = get_post_meta( $post_id, 'postratings-total', false );
	  $total2 = get_post_meta( $post_id, 'postratings-no', false );
	  $total = $total[0];
	  $total2 = $total2[0];
	  
	  $return = "1: $total 2: $total2";

if ($total!="" && $total2!="") {      
$return=round($total/$total2);
} else {
$return=0;
}
      
      return $return;
	  break;
    default :
      return false;
    break;
  } 
}

function show_ratings($comment2) {
global $single, $feed, $post;

if ($single) {
if ($_POST['rating'] != "") {
$value=$_POST['rating'];
global $wp_query;
$id = $wp_query->post->ID;

$var=ratings_action($id, 'update', $value);

$val=ratings_action($id, 'get', '');

if ($val==1) {
$val="<img src='http://i41.tinypic.com/15fncds.png' alt='1 star' title='1 star' />";
} else if ($val==2) {
$val="<img src='http://i41.tinypic.com/kan2ix.png' alt='2 stars' title='2 stars' />";
} else if ($val==3) {
$val="<img src='http://i42.tinypic.com/20zql3m.png' alt='3 stars' title='3 stars' />";
} else if ($val==4) {
$val="<img src='http://i44.tinypic.com/11ipcht.png' alt='4 stars' title='4 stars' />";
} else if ($val==5) {
$val="<img src='http://i42.tinypic.com/282nk7d.png' alt='5 stars' title='5 stars' />";
} else if ($val==0) {
$val="<img src='http://i42.tinypic.com/282nk7d.png' alt='5 stars' title='5 stars' />";
}

$comment2 .= "<p><center>Rating: ".$val."</center></p>";

} else {

global $wp_query;
$id = $wp_query->post->ID;

$val=ratings_action($id, 'get', '');

if ($val==1) {
$val="<img src='http://i41.tinypic.com/15fncds.png' alt='1 star' title='1 star' />";
} else if ($val==2) {
$val="<img src='http://i41.tinypic.com/kan2ix.png' alt='2 stars' title='2 stars' />";
} else if ($val==3) {
$val="<img src='http://i42.tinypic.com/20zql3m.png' alt='3 stars' title='3 stars' />";
} else if ($val==4) {
$val="<img src='http://i44.tinypic.com/11ipcht.png' alt='4 stars' title='4 stars' />";
} else if ($val==5) {
$val="<img src='http://i42.tinypic.com/282nk7d.png' alt='5 stars' title='5 stars' />";
} else if ($val==0) {
$val="<img src='http://i42.tinypic.com/282nk7d.png' alt='5 stars' title='5 stars' />";
}

$comment2 .= "<p><center>Rating: ".$val."</center></p>";

$comment2 .= "<p><center><form action='' method='post'><select name='rating'><option value='5'>*****</option><option value='4'>****</option><option value='3'>***</option><option value='2'>**</option><option value='1'>*</option></select><input type='submit' value='Vote!' /></form></center></p>";
}
}
return $comment2;
}


function ratings_plugin_support() {
global $single, $feed, $post;

echo "<p style='font-size:x-small'>Plugin supported by <a href='http://www.r4i-ds.net'>R4i</a>.</p>";
}

?>