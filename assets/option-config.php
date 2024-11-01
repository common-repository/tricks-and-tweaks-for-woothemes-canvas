<?php

/**
	ReduxFramework Sample Config File
	For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
**/

if ( !class_exists( "ReduxFramework" ) ) {
	return;
}

if ( !class_exists( "Redux_Framework_sample_config" ) ) {
	class Redux_Framework_sample_config {

		public $args = array();
		public $sections = array();
		public $theme;
		public $ReduxFramework;

		public function __construct( ) {

			// Just for demo purposes. Not needed per say.
			$this->theme = wp_get_theme();

			// Set the default arguments
			$this->setArguments();

			// Set a few help tabs so you can see how it's done
			$this->setHelpTabs();

			// Create the sections and fields
			$this->setSections();

			if ( !isset( $this->args['opt_name'] ) ) { // No errors please
				return;
			}

			$this->ReduxFramework = new ReduxFramework($this->sections, $this->args);


			// If Redux is running as a plugin, this will remove the demo notice and links
			//add_action( 'redux/plugin/hooks', array( $this, 'remove_demo' ) );

			// Function to test the compiler hook and demo CSS output.
			//add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);
			// Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.

			// Change the arguments after they've been declared, but before the panel is created
			//add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

			// Change the default value of a field after it's been set, but before it's been used
			//add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

			// Dynamically add a section. Can be also used to modify sections/fields
			add_filter('redux/options/'.$this->args['opt_name'].'/sections', array( $this, 'dynamic_section' ) );

		}


		/**

			This is a test function that will let you see when the compiler hook occurs.
			It only runs if a field	set with compiler=>true is changed.

		**/

		function compiler_action($options, $css) {
			echo "<h1>The compiler hook has run!";
			//print_r($options); //Option values

			// print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )
			/*
			// Demo of how to use the dynamic CSS and write your own static CSS file
		    $filename = dirname(__FILE__) . '/style' . '.css';
		    global $wp_filesystem;
		    if( empty( $wp_filesystem ) ) {
		        require_once( ABSPATH .'/wp-admin/includes/file.php' );
		        WP_Filesystem();
		    }

		    if( $wp_filesystem ) {
		        $wp_filesystem->put_contents(
		            $filename,
		            $css,
		            FS_CHMOD_FILE // predefined mode settings for WP files
		        );
		    }
			*/
		}



		/**

		 	Custom function for filtering the sections array. Good for child themes to override or add to the sections.
		 	Simply include this function in the child themes functions.php file.

		 	NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
		 	so you must use get_template_directory_uri() if you want to use any of the built in icons

		 **/

		function dynamic_section($sections){
		    //$sections = array();
		    $sections[] = array(
		        'title' => __('Section via hook', 'tricks-framework'),
		        'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'tricks-framework'),
				'icon' => 'fa fa-paperclip',
				    // Leave this as a blank section, no options just some intro text set above.
		        'fields' => array()
		    );

		    return $sections;
		}


		/**

			Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

		**/

		function change_arguments($args){
		    //$args['dev_mode'] = true;

		    return $args;
		}


		/**

			Filter hook for filtering the default value of any given field. Very useful in development mode.

		**/

		function change_defaults($defaults){
		    $defaults['str_replace'] = "Testing filter hook!";

		    return $defaults;
		}


		// Remove the demo link and the notice of integrated demo from the redux-framework plugin
		function remove_demo() {

			// Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
			if ( class_exists('ReduxFrameworkPlugin') ) {
				remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_meta_demo_mode_link'), null, 2 );
			}

			// Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
			remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );

		}


		public function setSections() {

			/**
			 	Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
			 **/


			// Background Patterns Reader
			$sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
			$sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
			$sample_patterns      = array();

			if ( is_dir( $sample_patterns_path ) ) :

			  if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
			  	$sample_patterns = array();

			    while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

			      if( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
			      	$name = explode(".", $sample_patterns_file);
			      	$name = str_replace('.'.end($name), '', $sample_patterns_file);
			      	$sample_patterns[] = array( 'alt'=>$name,'img' => $sample_patterns_url . $sample_patterns_file );
			      }
			    }
			  endif;
			endif;

			ob_start();

			$ct = wp_get_theme();
			$this->theme = $ct;
			$item_name = $this->theme->get('Name');
			$tags = $this->theme->Tags;
			$screenshot = $this->theme->get_screenshot();
			$class = $screenshot ? 'has-screenshot' : '';

			$customize_title = sprintf( __( 'Customize &#8220;%s&#8221;','tricks-framework' ), $this->theme->display('Name') );

			?>
			<div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
				<?php if ( $screenshot ) : ?>
					<?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
					<a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr( $customize_title ); ?>">
						<img src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
					</a>
					<?php endif; ?>
					<img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>" alt="<?php esc_attr_e( 'Current theme preview' ); ?>" />
				<?php endif; ?>

				<h4>
					<?php echo $this->theme->display('Name'); ?>
				</h4>

				<div>
					<ul class="theme-info">
						<li><?php printf( __('By %s','tricks-framework'), $this->theme->display('Author') ); ?></li>
						<li><?php printf( __('Version %s','tricks-framework'), $this->theme->display('Version') ); ?></li>
						<li><?php echo '<strong>'.__('Tags', 'tricks-framework').':</strong> '; ?><?php printf( $this->theme->display('Tags') ); ?></li>
					</ul>
					<p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
					<?php if ( $this->theme->parent() ) {
						printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.' ) . '</p>',
							__( 'http://codex.wordpress.org/Child_Themes','tricks-framework' ),
							$this->theme->parent()->display( 'Name' ) );
					} ?>

				</div>

			</div>

			<?php
			$item_info = ob_get_contents();

			ob_end_clean();

			$sampleHTML = '';
			if( file_exists( dirname(__FILE__).'/info-html.html' )) {
				/** @global WP_Filesystem_Direct $wp_filesystem  */
				global $wp_filesystem;
				if (empty($wp_filesystem)) {
					require_once(ABSPATH .'/wp-admin/includes/file.php');
					WP_Filesystem();
				}
				$sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__).'/info-html.html');
			}




			// ACTUAL DECLARATION OF SECTIONS

			$this->sections[] = array(
				'icon' => 'fa fa-arrow-circle-up',
				'title' => __('Header', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas Header Section'),
				'fields' => array (

            array(
                'id'       => 'header_search',
                'type'     => 'switch',
                'title'    => __( 'Add Search Box', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds a search box to the header', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'header_centre',
                'type'     => 'switch',
                'title'    => __( 'Centre Logo', 'wordplay-theme-options' ),
                'desc'     => __( 'Places the logo in the centre of the header', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'header_remove',
                'type'     => 'switch',
                'title'    => __( 'Remove the Header', 'wordplay-theme-options' ),
                'desc'     => __( 'Completely removes the header', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'header_after',
                'type'     => 'switch',
                'title'    => __( 'Header Widgets AFTER Logo', 'wordplay-theme-options' ),
                'desc'     => __( 'Moves the header widgets to display AFTER the logo', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'header_before',
                'type'     => 'switch',
                'title'    => __( 'Header Widgets BEFORE Logo', 'wordplay-theme-options' ),
                'desc'     => __( 'Moves the header widgets to display beforeE the logo', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

				),
			);

			$this->sections[] = array(
				'icon' => 'fa fa-arrow-circle-down',
				'title' => __('Footer', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas Footer Section'),
				'fields' => array (

            array(
                'id'       => 'footer_bg',
                'type'     => 'color_rgba',
                'title'    => __( 'Add Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adds a background colour to the entire footer widgets', 'wordplay-theme-options' ),
                //'output'   => array( 'body' ),
                //'mode'     => 'background',
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),

            ),

            array(
                'id'       => 'footer_img',
                'type'     => 'media',
                'url'      => true,
                'title'    => __( 'Add Image', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds an image to the footer area', 'wordplay-theme-options' ),
                'compiler' => 'true',
                //'mode'      => false, // Can be set to false to allow any media type, or can also be set to any mime type.
                //'default'  => array( 'url' => 'http://s.wordpress.org/style/images/codeispoetry.png' ),
                //'hint'      => array(
                //    'title'     => 'Hint Title',
                //    'content'   => 'This is a <b>hint</b> for the media field with a Title.',
                //)
            ),

				),
			);


			$this->sections[] = array(
				'icon' => 'fa fa-navicon',
				'title' => __('Navigation', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas Navigation Section'),
				'fields' => array (

            array(
                'id'       => 'nav_login_primary',
                'type'     => 'switch',
                'title'    => __( 'Add Login to Primary', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds login & logout option to the primary menu', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_login_top',
                'type'     => 'switch',
                'title'    => __( 'Add Login to Top', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds login & logout option to the top menu', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_centre_primary',
                'type'     => 'switch',
                'title'    => __( 'Center Primary', 'wordplay-theme-options' ),
                'desc'     => __( 'Centers the primary menu', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_menu_right',
                'type'     => 'switch',
                'title'    => __( 'Menu to Right of Logo', 'wordplay-theme-options' ),
                'desc'     => __( 'Moves the menu to the right of the logo', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_fit_box',
                'type'     => 'switch',
                'title'    => __( 'Fit Box Layout', 'wordplay-theme-options' ),
                'desc'     => __( 'Makes the navigation fit the box layout width', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_drop',
                'type'     => 'color_rgba',
                'title'    => __( 'Drop Down Font Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Changes the drop down menu font colour', 'wordplay-theme-options' ),
                //'output'   => array( 'body' ),
                //'mode'     => 'background',
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),

            ),

            array(
                'id'            => 'nav_drop_size',
                'type'          => 'slider',
                'title'         => __( 'Drop Down Font Size', 'wordplay-theme-options' ),
                'desc'          => __( 'Changes the drop down menu font size (px)', 'wordplay-theme-options' ),
                'default'       => 15,
                'min'           => 0,
                'step'          => 5,
                'max'           => 100,
                'display_value' => 'text'
            ),

            array(
                'id'       => 'nav_search_primary',
                'type'     => 'switch',
                'title'    => __( 'Search to Primary', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds a search box to the primary menu', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'nav_right_top',
                'type'     => 'switch',
                'title'    => __( 'Top Menu to Right', 'wordplay-theme-options' ),
                'desc'     => __( 'Aligns the Top Menu to the Right', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

				),
			);


			$this->sections[] = array(
				'icon' => 'fa fa-file',
				'title' => __('Posts & Pages', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas Posts & Pages Section<br>(ONLY AVAILABLE IN PRO VERSION)'),
				'fields' => array (

            array(
                'id'       => 'post_remove_title',
                'type'     => 'switch',
                'title'    => __( 'Remove Titles', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the page titles', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_home_title',
                'type'     => 'switch',
                'title'    => __( 'Remove Home Title', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the home page title', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_bg',
                'type'     => 'color_rgba',
                'title'    => __( 'Background Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adds a background colour to your posts (and some padding)', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),

            ),

            array(
                'id'       => 'post_remove_page_title',
                'type'     => 'select',
                'data'     => 'pages',
                'multi'    => true,
                'title'    => __( 'Remove Page Title', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the page title from a specific page', 'wordplay-theme-options' ),
            ),

            array(
                'id'       => 'post_comment_closed',
                'type'     => 'switch',
                'title'    => __( 'Remove ‘comments are closed’', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the ‘comments are closed’ message', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_you_here',
                'type'     => 'switch',
                'title'    => __( 'Remove ‘You are here’', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the words ‘You are here’ in breadcrumbs', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_img_border',
                'type'     => 'switch',
                'title'    => __( 'Remove Image Border', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the border around images', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_home_breadcrumb',
                'type'     => 'switch',
                'title'    => __( 'Remove Homepage Breadcrumbs', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the breadcrumbs from your home page', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'post_sidebar_bg',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Sidebar Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Changes the background colour of the sidebar', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),
            ),

            array(
                'id'       => 'post_box_bg',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Box Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Changes the box layout colour', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),
            ),

					),
				);

			$this->sections[] = array(
				'icon' => 'fa fa-sliders',
				'title' => __('Sliders', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas Sliders Section<br>(ONLY AVAILABLE IN PRO VERSION)'),
				'fields' => array (

            array(
                'id'       => 'slider_remove_arrow',
                'type'     => 'switch',
                'title'    => __( 'Remove Slider Arrows', 'wordplay-theme-options' ),
                'desc'     => __( 'Removes the arrows on the sliders', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'            => 'slider_text_move_h',
                'type'          => 'slider',
                'title'         => __( 'Move Slider Text (Horizontal)', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the slider text position (Horizontally)', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'slider_text_move_v',
                'type'          => 'slider',
                'title'         => __( 'Move Slider Text (Vertical)', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the slider text position (Vertically)', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'slider_transparency',
                'type'          => 'slider',
                'title'         => __( 'Change Transparency on Sliders', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the transparency level of the sliders', 'wordplay-theme-options' ),
                'default'       => 1.0,
                'min'           => 0,
                'step'          => 0.1,
                'max'           => 1.0,
                'resolution'    => 0.1,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'slider_spacing',
                'type'          => 'slider',
                'title'         => __( 'Change Space Between Menu and Slider', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the space between the menu and the slider', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'slider_width',
                'type'          => 'slider',
                'title'         => __( 'Change the Width of the Text Area on Sliders', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the width of the text area on top of the slide', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'       => 'slider_text_colour',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Slider Text Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adjusts the colour of the slider text', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),

            ),

            array(
                'id'       => 'slider_arrow_colour',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Slider Arrow Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adjusts the size of the slider arrows', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),

            ),

            array(
                'id'            => 'slider_arrow_size',
                'type'          => 'slider',
                'title'         => __( 'Change Slider Arrow Size', 'wordplay-theme-options' ),
                'desc'          => __( 'Changes the drop down menu font size (px)', 'wordplay-theme-options' ),
                'default'       => 15,
                'min'           => 0,
                'step'          => 5,
                'max'           => 100,
                'display_value' => 'text'
            ),

					),
				);


			$this->sections[] = array(
				'icon' => 'fa fa-gears',
				'title' => __('WooFramework', 'tricks-framework'),
				'desc' => __('Tricks & Tweaks for your WooThemes Canvas WooFramework Section<br>(ONLY AVAILABLE IN PRO VERSION)'),
				'fields' => array (

            array(
                'id'       => 'wfw_col_box',
                'type'     => 'switch',
                'title'    => __( 'Add Column Boxes', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds boxes around your columns', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'wfw_col_box_colour',
                'type'     => 'color_rgba',
                'title'    => __( 'Add Column Box Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adds a background colour to your columns', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),
            ),

            array(
                'id'            => 'wfw_col_box_height',
                'type'          => 'slider',
                'title'         => __( 'Fix Column Box Height', 'wordplay-theme-options' ),
                'desc'          => __( 'Fixes the height of your column boxes (px)', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'wfw_col_box_corner',
                'type'          => 'slider',
                'title'         => __( 'Add Box Rounded Corners', 'wordplay-theme-options' ),
                'desc'          => __( 'Adds rounded corners to your column boxes (px)', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 5,
                'max'           => 100,
                'display_value' => 'text'
            ),

            array(
                'id'            => 'wfw_col_box_max',
                'type'          => 'slider',
                'title'         => __( 'Set Box Max Height', 'wordplay-theme-options' ),
                'desc'          => __( 'Sets a maximum height for your column boxes (px)', 'wordplay-theme-options' ),
                'default'       => 0,
                'min'           => 0,
                'step'          => 20,
                'max'           => 1000,
                'display_value' => 'text'
            ),

            array(
                'id'       => 'wfw_info_box_colour',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Info Box Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adjusts the background colour of the info boxes', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),
            ),

            array(
                'id'       => 'wfw_blockq_colour',
                'type'     => 'color_rgba',
                'title'    => __( 'Change Blockquote Colour', 'wordplay-theme-options' ),
                'subtitle' => __( 'Adjusts the colour of the blockquotes', 'wordplay-theme-options' ),
                'validate' => 'colorrgba',
                'default'  => array(
                     'color'     => null,
                     'alpha'     => null
                 ),
            ),

            array(
                'id'            => 'wfw_blockq_size',
                'type'          => 'slider',
                'title'         => __( 'Change Blockquote Size', 'wordplay-theme-options' ),
                'desc'          => __( 'Adjusts the size of the blockquotes (px)', 'wordplay-theme-options' ),
                'default'       => 15,
                'min'           => 0,
                'step'          => 5,
                'max'           => 100,
                'display_value' => 'text'
            ),

            array(
                'id'       => 'wfw_blockq_close',
                'type'     => 'switch',
                'title'    => __( 'Add Closing Blockquote', 'wordplay-theme-options' ),
                'desc'     => __( 'Adds a closing blockquote', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

					),
				);


			$this->sections[] = array(
				'icon' => 'fa fa-life-ring',
				'title' => __('Miscellaneous', 'tricks-framework'),
				'desc' => __('Miscellaneous Tricks & Tweaks for your WooThemes Canvas Theme<br>(ONLY AVAILABLE IN PRO VERSION)'),
				'fields' => array (

            array(
                'id'       => 'msc_stretch_img',
                'type'     => 'switch',
                'title'    => __( 'Stretch Background Image', 'wordplay-theme-options' ),
                'desc'     => __( 'Stretches the background image to fit the entire screen', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

            array(
                'id'       => 'msc_sq_avatar',
                'type'     => 'switch',
                'title'    => __( 'Square Avatar', 'wordplay-theme-options' ),
                'desc'     => __( 'Makes the avatar images square', 'wordplay-theme-options' ),
                'default'  => '0'// 1 = on | 0 = off
            ),

					),
				);


      $this->sections[] = array(
        'icon' => 'fa fa-rocket',
        'title' => __('UPGRADE', 'tricks-framework'),
        'desc' => __('Upgrade to Tricks & Tweaks for WooThemes Canvas PRO<br><a target="_blank" href="http://dwh-uk.com/product/wordpress-plugins/tricks-and-tweaks-for-woothemes-canvas-pro/">(Unlocks ALL Options)</a>'),
        'fields' => array (

            array(
                'id'       => 'upgrade_pro',
                'type'     => 'info',
                'style'    => 'custom',
                'color'    => '#fcfcfc',
                'desc'     => __( '<img src="http://dwh-uk.com/wp-content/uploads/2016/04/screenshot-tricks-pro.png"><br><br><a target="_blank" href="http://dwh-uk.com/product/wordpress-plugins/tricks-and-tweaks-for-woothemes-canvas-pro/">Upgrade NOW!</a><br><br>', 'wordplay-theme-options' )
            ),

          ),
        );



			$theme_info = '<div class="redux-framework-section-desc">';
			$theme_info .= '<p class="redux-framework-theme-data description theme-uri">'.__('<strong>Theme URL:</strong> ', 'tricks-framework').'<a href="'.$this->theme->get('ThemeURI').'" target="_blank">'.$this->theme->get('ThemeURI').'</a></p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-author">'.__('<strong>Author:</strong> ', 'tricks-framework').$this->theme->get('Author').'</p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-version">'.__('<strong>Version:</strong> ', 'tricks-framework').$this->theme->get('Version').'</p>';
			$theme_info .= '<p class="redux-framework-theme-data description theme-description">'.$this->theme->get('Description').'</p>';
			$tabs = $this->theme->get('Tags');
			if ( !empty( $tabs ) ) {
				$theme_info .= '<p class="redux-framework-theme-data description theme-tags">'.__('<strong>Tags:</strong> ', 'tricks-framework').implode(', ', $tabs ).'</p>';
			}
			$theme_info .= '</div>';

			if(file_exists(dirname(__FILE__).'/README.md')){
			$this->sections['theme_docs'] = array(
						'icon' => ReduxFramework::$_url.'assets/img/glyphicons/glyphicons_071_book.png',
						'title' => __('Documentation', 'tricks-framework'),
						'fields' => array(
							array(
								'id'=>'17',
								'type' => 'raw',
								'content' => file_get_contents(dirname(__FILE__).'/README.md')
								),
						),

						);
			}//if






/*
			$this->sections[] = array(
				'icon' => 'fa fa-info-circle',
				'title' => __('Theme Information', 'tricks-framework'),
				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'tricks-framework'),
				'fields' => array(
					array(
						'id'=>'raw_new_info',
						'type' => 'raw',
						'content' => $item_info,
						)
					),
				);
				*/


			if(file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
			    $tabs['docs'] = array(
					'icon' => 'fa fa-book',
					    'title' => __('Documentation', 'tricks-framework'),
			        'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
			    );
			}

		}

		public function setHelpTabs() {

			// Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
			$this->args['help_tabs'][] = array(
			    'id' => 'redux-opts-1',
			    'title' => __('Theme Information 1', 'tricks-framework'),
			    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'tricks-framework')
			);

			$this->args['help_tabs'][] = array(
			    'id' => 'redux-opts-2',
			    'title' => __('Theme Information 2', 'tricks-framework'),
			    'content' => __('<p>This is the tab content, HTML is allowed.</p>', 'tricks-framework')
			);

			// Set the help sidebar
			$this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'tricks-framework');

		}


		/**

			All the possible arguments for Redux.
			For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

		 **/
		public function setArguments() {

			$theme = wp_get_theme(); // For use with some settings. Not necessary.

			$this->args = array(

	            // TYPICAL -> Change these values as you need/desire
				'opt_name'          	=> 'tricks_options', // This is where your data is stored in the database and also becomes your global variable name.
				'display_name'			=> 'Tricks & Tweaks for WooThemes Canvas ', // $theme->get('Name'), // Name that appears at the top of your panel
				'display_version'		=> '', //$theme->get('Version'), // Version that appears at the top of your panel
				'menu_type'          	=> 'menu', //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
				'allow_sub_menu'     	=> true, // Show the sections below the admin menu item or not
				'menu_title'			=> __( 'Canvas Tricks & Tweaks ', 'tricks-framework' ),
	            'page'		 	 		=> __( 'Canvas Tricks & Tweaks ', 'tricks-framework' ),
	            'google_api_key'   	 	=> '', // Must be defined to add google fonts to the typography module
	            'global_variable'    	=> '', // Set a different name for your global variable other than the opt_name
	            'dev_mode'           	=> false, // Show the time the page took to load, etc
	            'customizer'         	=> true, // Enable basic customizer support

	            // OPTIONAL -> Give you extra features
	            'page_priority'      	=> 30, // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
	            'page_parent'        	=> 'themes.php', // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
	            'page_permissions'   	=> 'manage_options', // Permissions needed to access the options panel.
	            'menu_icon'          	=> '', // Specify a custom URL to an icon
	            'last_tab'           	=> '', // Force your panel to always open to a specific tab (by id)
	            'page_icon'          	=> 'fa fa-bell-o', // Icon displayed in the admin panel next to your menu_title
	            'page_slug'          	=> '_options', // Page slug used to denote the panel
	            'save_defaults'      	=> true, // On load save the defaults to DB before user clicks save or not
	            'default_show'       	=> false, // If true, shows the default value next to each field that is not the default value.
	            'default_mark'       	=> '', // What to print by the field's title if the value shown is default. Suggested: *


	            // CAREFUL -> These options are for advanced use only
	            'transient_time' 	 	=> 60 * MINUTE_IN_SECONDS,
	            'output'            	=> true, // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
	            'output_tab'            => true, // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
	            //'domain'             	=> 'redux-framework', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
	            'footer_credit'      	=> ' ', // Disable the footer credit of Redux. Please leave if you can help it.


	            // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
	            'database'           	=> '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!


	            'show_import_export' 	=> true, // REMOVE
	            'system_info'        	=> false, // REMOVE

	            'help_tabs'          	=> array(),
	            'help_sidebar'       	=> '', // __( '', $this->args['domain'] );
				);


			// SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
			$this->args['share_icons'][] = array(
			    'url' => 'https://github.com/dwhukcom',
			    'title' => 'My GitHub',
			    'icon' => 'fa fa-github-square'
			    // 'img' => '', // You can use icon OR img. IMG needs to be a full URL.
			);
			$this->args['share_icons'][] = array(
			    'url' => 'http://twitter.com/dwhukcom',
			    'title' => 'Follow me on Twitter',
			    'icon' => 'fa fa-twitter-square'
			);



			// Panel Intro text -> before the form

				$this->args['intro_text'] = __('<p>Welcome to the Tricks & Tweaks for WooThemes Canvas Plugin.</p>', 'tricks-framework');


			// Add content after the form.
			$this->args['footer_text'] = __('', 'tricks-framework');

		}
	}
	new Redux_Framework_sample_config();

}


/**

	Custom function for the callback referenced above

 */
if ( !function_exists( 'redux_my_custom_field' ) ):
	function redux_my_custom_field($field, $value) {
	    print_r($field);
	    print_r($value);
	}
endif;

/**

	Custom function for the callback validation referenced above

**/
if ( !function_exists( 'redux_validate_callback_function' ) ):
	function redux_validate_callback_function($field, $value, $existing_value) {
	    $error = false;
	    $value =  'just testing';
	    /*
	    do your validation

	    if(something) {
	        $value = $value;
	    } elseif(something else) {
	        $error = true;
	        $value = $existing_value;
	        $field['msg'] = 'your custom error message';
	    }
	    */

	    $return['value'] = $value;
	    if($error == true) {
	        $return['error'] = $field;
	    }
	    return $return;
	}
endif;

function newIconFont() {
    // Uncomment this to remove elusive icon from the panel completely
    //wp_deregister_style( 'redux-elusive-icon' );
    //wp_deregister_style( 'redux-elusive-icon-ie7' );

    wp_register_style(
        'redux-font-awesome',
        '//netdna.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.css',
        array(),
        time(),
        'all'
    );
    wp_enqueue_style( 'redux-font-awesome' );
}
// This example assumes the opt_name is set to redux_demo.  Please replace it with your opt_name value.
add_action( 'redux/page/tricks_options/enqueue', 'newIconFont' );
