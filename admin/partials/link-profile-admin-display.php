<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://parsifar.com/
 * @since      1.0.0
 *
 * @package    Link_Profile
 * @subpackage Link_Profile/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<section class="container p-4 m-2">
  <div class="jumbotron ">
    <h1 class="display-4">Links Profile</h1>
  </div>

  <form class="form-inline my-4" id="link-profile-form">
    <label class="my-3" for="selected-post-input">Select a post or page to see its incoming and outgoing links.</label>
    <div class=" form-group row">
      <div class="col-md-6">
        <select class="form-control p-2 mw-100" name="selected-post" id="selected-post-input">
          <?php
                //get all posts and pages
                $args = array(
                    'post_type'    => array( 'page', 'post' ),
                    'post_status'    => 'publish',
                    'orderby' => 'title',
                    'order'     => 'ASC',
                    'posts_per_page' => '-1'
                );
                $all_posts = new WP_Query ( $args );
                $post_id_array = array();

                // The Loop
                if ( $all_posts->have_posts() ) {
                    
                    while ( $all_posts->have_posts() ) {
                        $all_posts->the_post();
                        $this_post_title = get_the_title();
                        echo '<option>' . $this_post_title . '</option>';
                        $post_id_array[  html_entity_decode($this_post_title, ENT_NOQUOTES, 'UTF-8')  ] = get_the_ID();
                    }   
                } else {
                    echo '<option>No Posts Found!</option>';
                }
                /* Restore original Post Data */
                wp_reset_postdata();
                ?>
        </select>
      </div>
      <div class="col-md-6">
        <button type="submit" class="btn btn-primary h-100">Show the links</button>
      </div>
    </div>
  </form>

  <!-- incoming links table -->
  <h2 class="message incoming-message mt-5"></h2>
  <table class="table incoming-table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Post Type</th>
        <th scope="col">Post Title</th>
        <th scope="col"># of Links</th>
        <th scope="col">Anchor Text</th>
      </tr>
    </thead>
    <tbody id="incoming-results">
    </tbody>
  </table>

  <!-- outgoing links table -->
  <h2 class="message outgoing-message mt-5"></h2>
  <table class="table outgoing-table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Link Type</th>
        <th scope="col">href</th>
        <th scope="col">Anchor Text</th>
      </tr>
    </thead>
    <tbody id="outgoing-results">
    </tbody>
  </table>

  <!-- Loader-->
  <div class="text-center my-4">
    <img src="<?php echo plugin_dir_url( __DIR__ ).'loader.gif' ;?>" id="loader-image">
  </div>
</section>


<script type="text/javascript" >
    //create an array with all posts 
    let allPostsArray=JSON.parse(   '<?php echo json_encode($post_id_array); ?>'   );
</script>