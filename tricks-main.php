<?php
global $tricks_options;

/*
    //===============================================================================================//
    A) Header
    //===============================================================================================//
     header_search. Add Search Box to Header
     header_centre. Centre logo
     header_remove. Remove the Header (completely)
     header_after. Move Header Widgets AFTER logo
     header_before. Move Header Widgets BEFORE logo

    //===============================================================================================//
    B) Footer
    //===============================================================================================//
     footer_bg. Add a background color to entire footer widgets
     footer_img. Add an image to the footer area

    //===============================================================================================//
    C) Navigation
    //===============================================================================================//
     8. Add Login / Logout to Primary Navigation
     9. Add Login / Logout to Top Menu
     10. Center Primary menu
     11. Move menu to the right of logo
     12. Make navigation fit box layout width
     13. Change drop down menu font color
     14. Change drop down menu font size
     15. Add a search box to the primary menu
     16. Align top menu to the right

    //===============================================================================================//


    //===============================================================================================//
    	TO DO
    //===============================================================================================//
     //ToDo XX. Add social media icons to header
     //ToDo XX. Change the header image per page
     //ToDo XX. Centre Top menu
     //ToDo XX. Move menu to the left of logo
*/


//===============================================================================================//
if ( ! function_exists( 'dwh_tricks_main' ) ) {

  function dwh_tricks_main () {

    global $gc_dwh_tricks_css, $tricks_options;

    $gc_dwh_tricks_css = '';


    //===============================================================================================//
    // header_search - Add Search Box to Header
    //===============================================================================================//
    if ( $tricks_options['header_search'] ) {

        add_action( 'woo_header_inside', 'dwh_tricks_add_searchform', 10 );

        $gc_dwh_tricks_css .= '.header-search { position: relative; top: 20px; } ' .
			    '.header-search .icon-search { ' .
			    'position: absolute; ' .
			    'top: 8px; ' .
			    'right: 9px; }';
    }


    //===============================================================================================//
    // header_centre - Centre logo
    //===============================================================================================//
    if ( $tricks_options['header_centre'] ) {

        $gc_dwh_tricks_css .= '#logo { float: none; margin: 0 auto; width: 300px; }';
    }


    //===============================================================================================//
    // header_remove - Remove the Header (completely)
    //===============================================================================================//
    if ( $tricks_options['header_remove'] ) {

        $gc_dwh_tricks_css .= '#header { display: none; }';
    }


    //===============================================================================================//
    // header_after - Move Header Widgets AFTER logo
    //===============================================================================================//
    if ( $tricks_options['header_after'] ) {
          $gc_dwh_tricks_css .= '.header-widget { margin: 0 0 0 50%; }';
    }

    //===============================================================================================//
    // header_after - Move Header Widgets AFTER logo
    //===============================================================================================//
    if ( $tricks_options['header_before'] ) {
          $gc_dwh_tricks_css .= '.header-widget { margin: 0 0 0 50%; }';
    }

    //===============================================================================================//
    // footer_bg - Add a background color to entire footer widgets
    //===============================================================================================//
    if ( $tricks_options['footer_bg']['color'] !== null ) {

	$gc_dwh_tricks_css .= '#footer-widgets { ' .
			  'background-color:' . $tricks_options['footer_bg']['rgba'] . ';' .
			  'padding:20px; ' .
			  'margin-left:-30px; ' .
			  'margin-right:-30px; ' .

			  '} ' .

			  '#footer { ' .
			  'background-color:' . $tricks_options['footer_bg']['rgba'] . ';' .
			  'color:white; ' .
			  'padding:20px; ' .
			  'margin-left:-30px; ' .
			  'margin-right:-30px; ' .
			  '} ';

    }


    //===============================================================================================//
    // footer_img - Add an image to the footer area
    //===============================================================================================//
    if ( $tricks_options['footer_img']['url'] ) {

	$gc_dwh_tricks_css .= '#footer { background:url("' . $tricks_options['footer_img']['url'] . '")}';
    }


    //===============================================================================================//
    // nav_login_primary - Add Login / Logout to Primary Navigation
    //===============================================================================================//
    if ( $tricks_options['nav_login_primary'] ) {

	add_filter( 'wp_nav_menu_items', 'dwh_tricks_primary_login', 10, 2 );

    }


    //===============================================================================================//
    // nav_login_top - Add Login / Logout to Top Menu
    //===============================================================================================//
    if ( $tricks_options['nav_login_top'] ) {

      add_filter( 'wp_nav_menu_items', 'dwh_tricks_top_login', 10, 2 );

    }

    //===============================================================================================//
    // nav_centre_primary - Center Primary menu
    //===============================================================================================//
    if ( $tricks_options['nav_centre_primary'] ) {

	  $gc_dwh_tricks_css .= '#navigation { position: relative; } ' .
			    '#main-nav { ' .
			      'clear: left; ' .
			      'float: left; ' .
			      'list-style: none; ' .
			      'margin: 0; ' .
			      'padding: 0; ' .
			      'position: relative; ' .
			      'left: 50%; ' .
			      'text-align: center;} ' .
			    '.nav li { ' .
			      'display: block; ' .
			      'float: left; ' .
			      'list-style: none; ' .
			      'margin: 0; ' .
			      'padding: 0; ' .
			      'position: relative; ' .
			      'right: 50%; } ' .
			    '.nav li:hover, .nav li.hover { ' .
			      'position: relative; } ' .
			    '.nav li ul li { ' .
			      'left: 0; } ' ;

    }


    //===============================================================================================//
    // nav_menu_right - Move menu to the right of logo
    //===============================================================================================//
    if ( $tricks_options['nav_menu_right'] ) {
        $gc_dwh_tricks_css .= '@media only screen and (min-width: 768px) {' .
    				'#navigation { ' .
        				'float: right; ' .
        				'width: auto; ' .
        				'clear:none; ' .
        				'max-width: 600px; // This can be changed' .
    				'}';
    }


    //===============================================================================================//
    // nav_fit_box - Make navigation fit box layout width
    //===============================================================================================//
    if ( $tricks_options['nav_fit_box'] ) {

	  $gc_dwh_tricks_css .= '#navigation { ' .
				  'margin-left:-30px; ' .
				  'width:978px; ' .
				  '} ';
    }


    //===============================================================================================//
    // nav_drop - Change drop down menu font color
    //===============================================================================================//
    if ( $tricks_options['nav_drop']['color'] !== null ) {

      $gc_dwh_tricks_css .= '#navigation ul.nav>li a:hover { ' .
                            'color: ' . $tricks_options['nav_drop']['rgba'] .
                            '} ' .
                            'ul.nav li ul li a:hover { ' .
                            'color: ' . $tricks_options['nav_drop']['rgba'] . '!important; ' .
                            '}';
    }


    //===============================================================================================//
    // nav_drop_size - Change drop down menu font size (px)
    //===============================================================================================//
    if ( $tricks_options['nav_drop_size'] ) {

        $gc_dwh_tricks_css .= 'ul.nav li ul li a { font-size:' . $tricks_options['nav_drop_size'] . 'px;}';
    }


    //===============================================================================================//
    // nav_search_primary - Add a search box to the primary menu
    //===============================================================================================//
    if ( $tricks_options['nav_search_primary'] ) {

        add_action( 'woo_nav_inside', 'dwh_tricks_add_searchform', 10 );

        $gc_dwh_tricks_css .= '#nav-search .icon-search { ' .
				'position: absolute; ' .
				'right: 9px; ' .
			  '} ' .
			  '#nav-search { ' .
				'margin-right: 9px; ' .
				'top: 10px; ' .
				'padding-top:5px; ' .
			  '} ';
    }


    //===============================================================================================//
    // nav_right_top - Align top menu to the right
    //===============================================================================================//
    if ( $tricks_options['nav_right_top'] ) {

        $gc_dwh_tricks_css .= '#top-nav { float: right;} ';
    }
  }
}


//=======================================//
if ( ! function_exists( 'dwh_tricks_init' ) ) {
//=======================================//

  function dwh_tricks_init () {
    global $tricks_options;

    if ( $tricks_options['header_after'] ) {

        remove_action( 'woo_header_inside', 'woo_header_widgetized' );
        add_action( 'woo_header_after', 'woo_header_widgetized', 5 );
    }


    if ( $tricks_options['header_before'] ) {

        remove_action( 'woo_header_inside', 'woo_header_widgetized' );
        add_action( 'woo_header_before', 'woo_header_widgetized' );
    }


    if ( $tricks_options['nav_menu_right'] ) {

	remove_action( 'woo_header_after','woo_nav', 10 );
 	add_action( 'woo_header_inside','woo_nav', 10 );
    }
  }
}


//=======================================//
if ( ! function_exists( 'dwh_tricks_primary_login' ) ) {
//=======================================//

	function dwh_tricks_primary_login( $items, $args ) {

    	    if (is_user_logged_in() && $args->theme_location == 'primary-menu') {
        	$items .= '<li><a href="'. wp_logout_url() .'">Log Out</a></li>';
    	    }
    	    elseif (!is_user_logged_in() && $args->theme_location == 'primary-menu') {
        	$items .= '<li><a href="'. site_url('wp-login.php') .'">Log In</a></li>';
    	    }
    	    return $items;
	}
}


//=======================================//
if ( ! function_exists( 'dwh_tricks_top_login' ) ) {
//=======================================//

	function dwh_tricks_top_login( $items, $args ) {
    		if (is_user_logged_in() && $args->theme_location == 'top-menu') {
        		$items .= '<li><a href="'. wp_logout_url() .'">Log Out</a></li>';
    		}
    		elseif (!is_user_logged_in() && $args->theme_location == 'top-menu') {
        		$items .= '<li><a href="'. site_url('wp-login.php') .'">Log In</a></li>';
    		}
    		return $items;
	}
}


//=======================================//
if ( ! function_exists( 'dwh_tricks_enqueue_css' ) ) {
//=======================================//

  function dwh_tricks_enqueue_css () {
	global $gc_dwh_tricks_css;

	echo "\n" . '<!-- Woo Canvas Mods Custom CSS Styling -->' . "\n" .
	     '<style type="text/css">' . "\n" .

	     $gc_dwh_tricks_css .

	     '</style>' . "\n";

  } // End dwh_tricks_enqueue_css()

}


//=======================================//
if ( ! function_exists( 'dwh_tricks_add_searchform' ) ) {
//=======================================//

  function dwh_tricks_add_searchform () {

      echo '<div id="custom-search" class="custom-search fr">' . " ";

      get_template_part( 'searchform' );

      echo '</div><!--/#custom-search .custom-search fr-->' . " ";

  }
}


add_action( 'init', 'dwh_tricks_init' );
dwh_tricks_main();

add_action( 'woothemes_wp_head_before', 'dwh_tricks_enqueue_css', 10 );

?>
