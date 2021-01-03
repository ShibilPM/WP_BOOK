<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://shibil.com
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Shibil <shibilpm3232@gmail.com>
 */
class Wp_Book_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		register_activation_hook(__FILE__, array( $this, 'custom_flush_rules' ) );

		add_shortcode( 'book', array( $this, 'bk_shortcode' ) );

		add_action( 'wp_dashboard_setup', array( $this, 'bk_dashboard_widget' ) );

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-book-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	*
	* 1. Creating A Cusom post type ( book )
	*
	* @since     1.0.0
	*
	*/

	public function bk_create_cpt() {

    $labels = array(
      'name' => __( 'Books', 'bk' ),
      'singular_name' => __( 'Book', 'bk' ),
      'add_new' => __( 'New Book', 'bk' ),
      'add_new_item' => __( 'Add New Book', 'bk' ),
      'edit_item' => __( 'Edit Book', 'bk' ),
      'new_item' => __( 'New Book', 'bk' ),
      'view_item' => __( 'View Books', 'bk' ),
      'search_items' => __( 'Search Books', 'bk' ),
      'not_found' =>  __( 'No Books Found', 'bk' ),
      'not_found_in_trash' => __( 'No Books found in Trash', 'bk' ),
    );

    $args = array(
      'labels' => $labels,
      'has_archive' => true,
      'public' => true,
      'hierarchical' => false,
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'custom-fields',
        'thumbnail',
        'page-attributes'
      ),
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'show_in_admin_bar' => true,
      'has_archive' => true,
      'can_export' => true,
      'exclude_from_search' => false,
      'rewrite' => array( 'slug' => 'book' ),
      'publicly_queryable' => true,
      'capability_type' => 'post',
      'show_in_rest' => true,
    );

    //Registering Custom Post Type
    register_post_type( 'book', $args );

  }

	function custom_flush_rules(){
	//defines the post type so the rules can be flushed.
	bk_create_cpt();

	//and flush the rules.
	flush_rewrite_rules();
}


	/**
	*
	* 2. Creating a custom hierarchical taxonomy Book Category
	*
	* @since version 1.0.0
	*
	*/

  public function bk_create_ht() {

  // Adding new taxonomy, hierarchical like categories
    $labels = array(
      'name' => _x( 'Categories', 'taxonomy general name' ),
      'singular_name' => _x( 'Category', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Category' ),
      'all_items' => __( 'All Categories' ),
      'parent_item' => __( 'Parent Category' ),
      'parent_item_colon' => __( 'Parent Category:' ),
      'edit_item' => __( 'Edit Category' ),
      'update_item' => __( 'Update Category' ),
      'add_new_item' => __( 'Add New Category' ),
      'new_item_name' => __( 'New Category Name' ),
      'menu_name' => __( 'Book Category' ),
    );

    $args = array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'categories' )
    );

		//Registering The Taxanomy
    register_taxonomy('subjects',array('book'), $args);

  }


	/**
	*
	* 3. Creating a custom non-hierarchical taxonomy Book Tag
	*
	* @since version 1.0.0
	*
	*/


  public function bk_create_nht() {

    // Adding new taxonomy, Non-hierarchical like Tags
    $labels = array(
      'name' => _x( 'Tags', 'taxonomy general name' ),
      'singular_name' => _x( 'Tag', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Tags' ),
      'all_items' => __( 'All Tags' ),
      'parent_item' => null,
      'parent_item_colon' => null,
      'edit_item' => __( 'Edit Tags' ),
      'update_item' => __( 'Update Tag' ),
      'add_new_item' => __( 'Add New Tag' ),
      'new_item_name' => __( 'New Tag Name' ),
      'menu_name' => __( 'Book Tag' ),
    );

    $args = array(
      'hierarchical' => false,
      'labels' => $labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'tags' )
    );

		//Register The Taxanomy
    register_taxonomy('custom',array('book'), $args);

  }

	/**
	* 4. Creating Meta box
	*
	* 5. Creating A Custom Meta table and extending Metadata API
	*
	* @since      1.0.0
	*
	*/

	//Table integrating with wpdb
	public function bkmeta_integrate_wpdb() {

		global $wpdb;

		$wpdb->bookmeta = $wpdb->prefix . 'bookmeta';
		$wpdb->tables[] = 'bookmeta';

	}

	// Creating A Meta Box
	public function bk_create_meta_box() {

  	//adding a meta box
  	add_meta_box( 'bk-cpt-mtbox',//id
                	'Details Metabox',//title
                	array( $this, 'bk_mtbox_function' ),//callback function
                	'book',//cpt name
                	'side',
                	'high'
              	);

	}

	//Meta Box Callback functin
	public function bk_mtbox_function( $post ) {

  	wp_nonce_field( basename( __FILE__ ), 'bk_create_meta_box_nonce' );
		?>
		<label for="author_name">Author Name: </label><br/>
		<input name="author_name" id="author_name" type="text" /> <br/>
		<label for="price">Price: </label><br>
		<input name="price" id="price" type="text" /> <br/>
		<label for="publisher">Publisher: </label><br>
		<input name="publisher" id="publisher" type="text" /> <br/>
		<label for="year">Year: </label><br>
		<input name="year" id="year" maxlength="4" type="text" /> <br/>
		<label for="edition">Edition: </label><br>
		<input name="edition" id="edition" type="text" /> <br/>
		<?php

	}



	public function bk_save_table( $post_id, $post ) {

		// checking nonce is set
		if( !isset( $_POST[ 'bk_create_meta_box_nonce' ] ) ) {
			return $post_id;
		}

		//declaring variable for saving data
		$author_name = '';
		$price = '';
		$publisher = '';
		$year = '';
		$edition = '';

		//checking and updating wp_bookmeta table
		if( !empty($_POST[ 'author_name' ]) ) {
			$author_name = sanitize_text_field( $_POST[ 'author_name' ] );
			update_book_meta( $post_id, "author", $author_name );
		}

		if( !empty($_POST[ 'price' ]) ) {
			$price = sanitize_text_field( $_POST[ 'price' ] );
			update_book_meta( $post_id, "price", $price );
		}

		if( !empty($_POST[ 'publisher' ]) ) {
			$publisher = sanitize_text_field( $_POST[ 'publisher' ] );
			update_book_meta( $post_id, "publisher", $publisher );
		}

		if( !empty($_POST[ 'year' ]) ) {
			$year = sanitize_text_field( $_POST[ 'year' ] );
			update_book_meta( $post_id, "year", $year );
		}

		if( !empty($_POST[ 'edition' ]) ) {
			$edition = sanitize_text_field( $_POST[ 'edition' ] );
			update_book_meta( $post_id, "edition", $edition );
		}

	}


	/**
	* 6. Adding menu page & submenu page
	*
	* @since      1.0.0
	*
	*/

	function bk_submenu_settings() {

		add_menu_page(
			'Booksmenu',
			'Booksmenu',
			'manage_options',
			'booksmenu-settings',
			array( $this, 'bk_settings_callback' )
		);

		add_submenu_page(
			'booksmenu-settings',
			'Book Settings', //title
			'Book Settings', //name
			'manage_options', // access
			'book_settings', //slug
			array( $this, 'bk_submenu_settings_callback' ) // call back function
		);

	}

	function bk_settings_callback() {
		?>
		<div class="wrap">
			<h3>Book Settings</h3>
		</div>
		<?php
	}


	function bk_submenu_settings_callback() {

		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<form action= "options.php" method="post">
				<?php
				//security field
				settings_fields( 'book_settings' );

				//output settings section
				do_settings_sections( 'book_settings' );

				// save settings
				submit_button( 'Save Settings' );
				?>
			</form>
		</div>
		<?php

	}

	//settings template
	function bk_submenu_settings_init() {

		// setup the setting section
		add_settings_section(
			'bk_settings_section',
			'Book Settings Page',
			'',
			'book_settings'
		);

		//Register input fields
		register_setting(
			'book_settings',
			'bk_settings_input_field',
			array(
				'type' => '',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		//Add settings field
		add_settings_field(
			'bk_settings_input_field',
			__( 'Post Per Page' ),
			array( $this, 'bk_settings_input_field_callback' ),
			'book_settings',
			'bk_settings_section'
		);

		//Register select fields
		register_setting(
			'book_settings',
			'bk_settings_select_field',
			array(
				'type' => '',
				'sanitize_callback' => 'sanitize_text_field',
				'default' => ''
			)
		);

		//Add settings field
		add_settings_field(
			'bk_settings_select_field',
			__( 'Select Currency' ),
			array( $this, 'bk_settings_select_field_callback' ),
			'book_settings',
			'bk_settings_section'
		);

	}

	//settings input field template
	function bk_settings_input_field_callback() {

		$bk_input_field = get_option( 'bk_settings_input_field' );

		?>
		<input type="number" name="bk_settings_input_field" value="<?php echo isset( $bk_input_field ) ? esc_attr( $bk_input_field ) : ''; ?>" />
		<?php
	}

	//settings option field template
	function bk_settings_select_field_callback() {

		$bk_select_field = get_option( 'bk_settings_select_field' );

		?>
		<select class="text-field" name="bk_settings_select_field">
			<option value="">Select Currency</option>
			<option value="option1" <?php selected( 'option1', $bk_select_field ) ?> >Rs.</option>
			<option value="option2" <?php selected( 'option2', $bk_select_field ) ?> >U.S. dollar</option>
			<option value="option3" <?php selected( 'option3', $bk_select_field ) ?> >Euro</option>
		</select>
		<?php

	}

	/**
	*
	* 7. Adding short code
	*
	* @since       1.0.0
	*
	*/

	function bk_shortcode( $atts ){
		$atts = shortcode_atts(
	 		array(
	    	'id' => '',
	      'author_name' => '',
	      'year' => '',
	      'category' => '',
	     	'tag' => '',
	      'publisher' => ''
	    ), $atts
	  );

	  $args = array(
	  	'post_type' => 'book',
	    'post_status' => 'publish',
     	'author' => $atts['author_name'],
  	);

    if($atts['book_id'] != ''){
	  	$args['book_id'] = $atts['id'];
	  }

		if($atts['category'] != ''){
	  	$args['tax_query'] = array(
	    	array(
	      	'taxonomy' => 'subjects',
	        'terms' => array( $atts['category'] ),
	        'field' => 'name',
	    		'operator' => 'IN'
	      ),
	    );
	 	}

	  if($atts['tag'] != ''){
	  	$args[ 'tax_query' ] = array(
	    	array(
 					'taxonomy' => 'custom',
   				'terms' => array($atts['tag']),
   				'field' => 'name',
     			'operator' => 'IN'
   			),
  		);
 		}

		$wpb_query = new WP_Query( $args );
			if ( $wpb_query->have_posts() ) {
				while($wpb_query->have_posts()){
    			$wpb_query->the_post();

					$content = '';
					$content .= '<article id="book-'.get_the_ID().'">';
  				$content .= '<center><h3 style="color: maroon;">'.get_the_title().'</h3></center>';
  				$content .= '<p>'.get_the_content().'</p>';
  				$content .= '<p>Author :- '.get_metadata('book', get_the_ID(), 'author', true);
    			$content .= '<br> publisher :- '.get_metadata('book', get_the_ID(), 'publisher', true);
    			$content .= '<br> year :- '.get_metadata('book', get_the_ID(), 'year', true);
  				$content .= '</article>';
        }//end while
    	} else {
        $content .= "No Book Found....";
    	}//end if

    	return $content;

	}

	/**
	* adding dashboard widget
	*/

	function bk_dashboard_widget(){

		wp_add_dashboard_widget(
			'bk_dashboard_widget',
			'Top-5',
			array( $this, 'bk_dashboard_widget_callback' )
		);

	}


	/*
  * dashboard widget callback function
	*/

	function bk_dashboard_widget_callback(){

		$categories = get_terms(
			array(
				'taxonomy' => 'subjects',
				'hide_empty' => false,
				'order' => 'DESC',
				'number' => 5
			)
		);

		if( !empty( $categories )):
			?>
			<p class="book-table-head">
				<span><b><?php esc_html_e( 'Category Name', 'wp-book' ); ?></b></span>
			</p>
			<ul class="dashboard-book-display">
			<?php
				foreach($categories as $cat){
			?>
				<li><a href="<?php echo get_category_link( $cat->term_id );?>">
					<?php echo $cat->name; ?> </a>
				</li>
			<?php
			}
			?>
			</ul>
			<?php else : ?>
				<p><?php esc_html_e( 'Add new book categories', 'wp-book' ); ?></p>
			<?php
				endif;

	}
}

/****
*
*
* wrapper function
*
*/

function add_book_meta($book_id, $meta_key, $meta_value, $unique = false) {

	return add_metadata('book', $book_id, $meta_key, $meta_value, $unique);

}


function delete_book_meta($book_id, $meta_key, $meta_value = '') {

	return delete_metadata('book', $book_id, $meta_key, $meta_value);

}


function get_book_meta($book_id, $key = '', $single = false) {

	return get_metadata('book', $book_id, $key, $single);

}


function update_book_meta($book_id, $meta_key, $meta_value, $prev_value = '') {

	return update_metadata('book', $book_id, $meta_key, $meta_value, $prev_value);

}


class create_sidebar
{

  function __construct() {
    add_action( 'widgets_init', array( $this, 'bk_register_sidebar' ) );
  }

	//creating side bar
  function bk_register_sidebar() {
    $args = array(
      'name' => 'Sidebar',
      'id' => 'bk-sidebar',
      'description' => 'widget to display books of selected category',
      'before_widget' => '<li id="%1$s" class="widget %2$s">',
      'after_widget' => '</li>',
      'before_title' => '<h2 class="widgettitle">',
      'after_title' => '</h2>'
    );

    register_sidebar( $args );//registering sidebar

  }

}

$createSideBar = new create_sidebar();

/**
*
* Create custom widget
*
* @since    1.0.0
*
*/
class Custom_Post_Type_Widgets {

  /**
	  * Sets up a new widget instance
	  */
		public function __construct() {
	    add_action( 'widgets_init', array( $this, 'init' ) );
	  }

	  /**
	  * Register widget
	  */
	  public function init() {
	  	if ( ! is_blog_installed() ) {
	    	return;
	    }

	    register_widget( 'create_widget' );

			}

	  }

	 if ( ! defined( 'ABSPATH' ) ) {
	 exit;
}
$custom_post_type_widgets = new Custom_Post_Type_Widgets();


class create_widget extends WP_Widget {

	/**
 	* Sets up a new widget instance.
 	*
 	* @since 1.0.0
	*
	* @access public
 	*/
	public function __construct() {
  	$widget_ops = array(
	 		'classname'                   => 'widget_categories',
	    'description'                 => __( 'A list or dropdown of categories.', 'custom-post-type-widgets' ),
	    'customize_selective_refresh' => true,
	  );
	  parent::__construct( 'custom-post-type-categories', __( 'Categories (Custom Post Type)', 'custom-post-type-widgets' ), $widget_ops );
	}

	/**
	* The widget create form (for the backend ).
	*
	* @param array $instance Current settings.
	*
	* @return void.
	*/
 	public function form($instance) {
		// Set widget defaults.
		$title = esc_attr($instance['title']);
		$number	= esc_attr($instance['number']);
		$exclude	= esc_attr($instance['exclude']);
		$taxonomy	= esc_attr($instance['taxonomy']);
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of categories to display'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('taxonomy'); ?>"><?php _e('Choose the Taxonomy to display'); ?></label>
			<select name="<?php echo $this->get_field_name('taxonomy'); ?>" id="<?php echo $this->get_field_id('taxonomy'); ?>" class="widefat"/>
				<?php
				$taxonomies = get_taxonomies(array('public'=>true), 'names');
				foreach ($taxonomies as $option) {
					echo '<option id="' . $option . '"', $taxonomy == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				?>
			</select>
		</p>
		<?php

	}//end form()


	 /**
		* The widget update form (for the backend ).
		*
		* @param array $newInstance New settings for this instance as input by the user via WP_Widget::form().
		* @param array $oldInstance Old settings for this instance.
		*
		* @return void.
		*/
	 public function update($new_instance, $old_instance)
	 {

		 $instance = $old_instance;
		 $instance['title'] = strip_tags($new_instance['title']);
		 $instance['number'] = strip_tags($new_instance['number']);
		 $instance['taxonomy'] = $new_instance['taxonomy'];
		 return $instance;

	 }//end update()


	 /**
		* The widget display form (for the backend ).
		*
		* @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		* @param array $instance The settings for the particular instance of the widget.
		*
		* @return void.
		*/
	 public function widget($args, $instance)
	 {
		 extract( $args );
		 $title = apply_filters('widget_title', $instance['title']); // the widget title
		 $number 	= $instance['number']; // the number of categories to show
		 $taxonomy 	= $instance['taxonomy']; // the taxonomy to displa
		 $args = array(
		 	'number' 	=> $number,
		 	'taxonomy'	=> $taxonomy
		);

	 /**
		* retrieves an array of categories or taxonomy terms
	  */
	 $cats = get_categories($args);
	 ?>
 		<?php echo $before_widget; ?>
	 	<?php if ( $title ) { echo $before_title . $title . $after_title; } ?>
 		<ul>
 		<?php foreach($cats as $cat) { ?>
 			<li><a href="<?php echo get_term_link($cat->slug, $taxonomy); ?>" title="<?php sprintf( __( "View all posts in %s" ), $cat->name ); ?>"><?php echo $cat->name; ?></a></li>
 		<?php } ?>
 		</ul>
	 	<?php echo $after_widget; ?>
		<?php
	}

}
