<?php

/**
 * Handles the AJAX calls
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

<?php


    add_action( 'wp_ajax_find_incoming_links', 'find_incoming_outgoing_links' );

    function find_incoming_outgoing_links(){
        //get the post id from the AJAX request
        $selected_post_id = $_POST['selected_post_id'];
        
        //get the absolute URL of the post
        $selected_post_permalink = get_permalink($selected_post_id );
        //get the relative URL of the post
        $selected_post_slug = str_replace( home_url(), '', $selected_post_permalink );

        //get all posts and pages
        $args = array(
            'post_type'    => array( 'page', 'post' ),
            'post_status'    => 'publish',
            'orderby' => 'title',
            'order'     => 'ASC',
            'posts_per_page' => '-1'
        );
        $all_posts = new WP_Query ( $args );

        // The Loop
        if ( $all_posts->have_posts() ) {

            //this array will be populated with all incoming links and returned to the front end
            $incoming_links = array();

            while ( $all_posts->have_posts() ) {
                $all_posts->the_post();
                //get the content of the post
                $post_content = apply_filters( 'the_content', get_the_content() );
                
                //create the regex string to search for the links to the selected post
                $regex_str = '/<a[^<>]+?href="[^<>]*?' . str_replace('/','\/',$selected_post_slug) . '".*?>[^<>]*?<\/a>/';

                //specific REGEX for the homepage
                if($selected_post_slug == '/'){
                    $site_homepage_url = str_replace('/','\/',home_url('/'));
                    $regex_str = '/<a[^<>]+?href="'.$site_homepage_url.'".*?>[^<>]*?<\/a>|<a[^<>]+?href="\/".*?>[^<>]*?<\/a>/';
                }
                //if the content of the post contains the link
                if(preg_match_all($regex_str , $post_content , $matches)){
                    //create an array to hold the anchor texts
                    $anchors_array = array();
                    foreach ($matches[0] as $match){
                        //extract the anchor text from the <a> tag and push them into the array
                        $anchor_text = preg_replace('/<.*?a.*?>/' , '' ,$match );
                        array_push($anchors_array , $anchor_text);
                    }
                    $post_data = array('title' => get_the_title() , 'id' => get_the_ID(), 'permalink'=> get_permalink() , 'number' => count($matches[0]) , 'anchors' => $anchors_array , 'post_type' => get_post_type() , 'search_slug' =>$selected_post_slug );
                    array_push($incoming_links , $post_data);
                }
                
            }   //end while loop
        } 
        /* Restore original Post Data */
        wp_reset_postdata();

        //find outgoinglinks in the post

        //get the content of the post
        $the_selected_post = get_post($selected_post_id); 
        //$the_selected_post_content = apply_filters( 'the_content', $the_selected_post->post_content );
        $the_selected_post_content = get_post_field('post_content', $selected_post_id); 

        //create the regex string to search for all links
        $outgoing_regex_str = '/<a[^<>]+?href="([^<>]+?)".*?>([^<>]*?)<\/a>/';
        $outgoing_links =array();
        //if the content of the post contains the link
        if(preg_match_all($outgoing_regex_str , $the_selected_post_content , $outgoing_matches)){

            //$site_homepage_url = str_replace('/','\/',home_url());
            
            for ($i=0 ; $i < count($outgoing_matches[1]) ; $i++){
                $stripped_home_url = preg_replace('/(https?:\/\/)?(www\.)?/' , '' , home_url());
                $stripped_href = preg_replace('/(https?:\/\/)?(www\.)?(' .$stripped_home_url .')?/' , '' , $outgoing_matches[1][$i]);
                $link_type = strpos($stripped_href , '/') == 0 ? 'internal' : 'external';
                array_push($outgoing_links , array('href' => $outgoing_matches[1][$i] , 'anchor' => $outgoing_matches[2][$i] , 'link_type' => $link_type , 'stripped_href' => $stripped_href ));
            }
        }

        //return the data to the AJAX call
        echo json_encode(array('incoming_links' => $incoming_links , 'outgoing_links' => $outgoing_links , 'test' => array($_POST['selected_post_id'] ,$selected_post_id, $the_selected_post_content)));
        wp_die();
    }
?>