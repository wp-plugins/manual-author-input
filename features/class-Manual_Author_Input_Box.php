<?php/**
 * Feature Name:	Post Boxes
 * Version:			0.1
 * Author:			Inpsyde GmbH
 * Author URI:		http://inpsyde.com
 * Licence:			GPLv3
 */

if ( ! class_exists( 'Manual_Author_Input_Box' ) ) {

	class Manual_Author_Input_Box extends Manual_Author_Input {

		/**
		 * Tab holder
		 *
		 * @since	0.1
		 * @access	public
		 * @var		array
		 */
		public $tabs = array();
		
		/**
		 * Instance holder
		 *
		 * @since	0.1
		 * @access	private
		 * @static
		 * @var		NULL | Manual_Author_Input_Box
		 */
		private static $instance = NULL;
		
		/**
		 * Method for ensuring that only one instance of this object is used
		 *
		 * @since	0.1
		 * @access	public
		 * @static
		 * @return	Manual_Author_Input_Box
		 */
		public static function get_instance() {
				
			if ( ! self::$instance )
				self::$instance = new self;
				
			return self::$instance;
		}
		
		/**
		 * Setting up some data, initialize translations and start the hooks
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	is_admin, add_filter
		 * @return	void
		 */
		public function __construct() {
						// Change the authors name			add_filter( 'the_author', array( $this, 'the_author' ) );						// Fire admin only filters
			if ( is_admin() ) {
							// Adds the meta boxes				add_filter( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );							// Adds the save post hook				add_filter( 'save_post', array( $this, 'save_meta_data' ) ); 			}
		}				/**
		 * Changing the authors name and url		 *
		 * @since	0.1
		 * @access	public
		 * @uses	is_admin, get_post_meta
		 * @return	string author name
		 */		public function the_author( $display_name ) {						// Return on admin pages			if ( is_admin() )				return;			// Set default			$return_name = $display_name;					// Get current post			global $post;						// Check Name			if ( '' != get_post_meta( $post->ID, 'mai_author', TRUE ) )				$return_name = get_post_meta( $post->ID, 'mai_author', TRUE );					// Check URL
			if ( '' != get_post_meta( $post->ID, 'mai_author_url', TRUE ) )
				$return_name = '<a href="' . get_post_meta( $post->ID, 'mai_author_url', TRUE ) . '">' . $return_name . '</a>';						return $return_name;		}				/**
		 * add the meta box
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	add_meta_box, __
		 * @return	void
		 */		public function add_meta_boxes() {						add_meta_box(				'manual-author-input',				__( 'Manual Author Input', parent::$textdomain ),				array( $this, 'mai_box' ),				'post'			);		}				/**
		 * display the meta box
		 *
		 * @since	0.1
		 * @access	public
		 * @uses	_e, get_post_meta
		 * @return	void
		 */		public function mai_box() {			global $post;						?>
			<table class="form-table">
				<tr>
					<th><label for="mai_author"><?php _e( 'Author Name', parent::$textdomain ); ?></label></th>
					<td>
						<input type="text" name="mai_author" id="mai_author" value="<?php echo get_post_meta( $post->ID, 'mai_author', TRUE ); ?>" />
					</td>
				</tr>				<tr>					<th><label for="mai_author_url"><?php _e( 'Author URL', parent::$textdomain ); ?></label></th>					<td>						<input type="text" name="mai_author_url" id="mai_author_url" value="<?php echo get_post_meta( $post->ID, 'mai_author_url', TRUE ); ?>" />					</td>				</tr>
			</table>
			<?php		}				/**
		 * Saves the post meta
		 *
		 * @access	public
		 * @since	0.1
		 * @uses	DOING_AUTOSAVE, current_user_can, update_post_meta
		 * @return	void
		 */
		public function save_meta_data() {
		
			// Preventing Autosave, we don't want that
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
				return;
					// Do we have a post
			if ( 'post' != get_post_type( $_POST[ 'ID' ] ) )
				return;
		
			// Check permissions
			if ( ! current_user_can( 'edit_posts', $_POST[ 'ID' ] ) )
				return;					// Add Post Meta if there is one			if ( ! isset( $_POST[ 'mai_author' ] ) )				$_POST[ 'mai_author' ] = '';
			if ( ! isset( $_POST[ 'mai_author_url' ] ) )				$_POST[ 'mai_author_url' ] = '';			
			update_post_meta( $_POST[ 'ID' ], 'mai_author', $_POST[ 'mai_author' ] );			update_post_meta( $_POST[ 'ID' ], 'mai_author_url', $_POST[ 'mai_author_url' ] );		}	}
}

// Kickoff
if ( function_exists( 'add_filter' ) )
	Manual_Author_Input_Box::get_instance();?>