<?php
/**
 * Sugartown Pink functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Sugartown Pink 1.0
 */

// Adds theme support for post formats.
if ( ! function_exists( 'sugartown_pink_post_format_setup' ) ) :
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_post_format_setup() {
		add_theme_support( 'post-formats', array( 'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video' ) );
	}
endif;
add_action( 'after_setup_theme', 'sugartown_pink_post_format_setup' );

// Enqueues editor-style.css in the editors.
if ( ! function_exists( 'sugartown_pink_editor_style' ) ) :
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_editor_style() {
		add_editor_style( 'assets/css/editor-style.css' );
	}
endif;
add_action( 'after_setup_theme', 'sugartown_pink_editor_style' );

// Enqueues style.css on the front.
if ( ! function_exists( 'sugartown_pink_enqueue_styles' ) ) :
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_enqueue_styles() {
		wp_enqueue_style(
			'sugartown-pink-style',
			get_parent_theme_file_uri( 'style.css' ),
			array(),
			wp_get_theme()->get( 'Version' )
		);
	}
endif;
add_action( 'wp_enqueue_scripts', 'sugartown_pink_enqueue_styles' );
// ✨ FIX: Force the Editor to read the same file
add_action( 'enqueue_block_editor_assets', 'sugartown_pink_enqueue_styles' );

// Registers custom block styles.
if ( ! function_exists( 'sugartown_pink_block_styles' ) ) :
	/**
	 * Registers custom block styles.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_block_styles() {
		register_block_style(
			'core/list',
			array(
				'name'         => 'checkmark-list',
				'label'        => __( 'Checkmark', 'sugartown-pink' ),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action( 'init', 'sugartown_pink_block_styles' );

// Registers pattern categories.
if ( ! function_exists( 'sugartown_pink_pattern_categories' ) ) :
	/**
	 * Registers pattern categories.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_pattern_categories() {

		register_block_pattern_category(
			'sugartown_pink_page',
			array(
				'label'       => __( 'Pages', 'sugartown-pink' ),
				'description' => __( 'A collection of full page layouts.', 'sugartown-pink' ),
			)
		);

		register_block_pattern_category(
			'sugartown_pink_post-format',
			array(
				'label'       => __( 'Post formats', 'sugartown-pink' ),
				'description' => __( 'A collection of post format patterns.', 'sugartown-pink' ),
			)
		);
	}
endif;
add_action( 'init', 'sugartown_pink_pattern_categories' );

// Registers block binding sources.
if ( ! function_exists( 'sugartown_pink_register_block_bindings' ) ) :
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_register_block_bindings() {
		register_block_bindings_source(
			'sugartown-pink/format',
			array(
				'label'              => _x( 'Post format name', 'Label for the block binding placeholder in the editor', 'sugartown-pink' ),
				'get_value_callback' => 'sugartown_pink_format_binding',
			)
		);
	}
endif;
add_action( 'init', 'sugartown_pink_register_block_bindings' );

// Registers block binding callback function for the post format name.
if ( ! function_exists( 'sugartown_pink_format_binding' ) ) :
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function sugartown_pink_format_binding() {
		$post_format_slug = get_post_format();

		if ( $post_format_slug && 'standard' !== $post_format_slug ) {
			return get_post_format_string( $post_format_slug );
		}
	}
endif;
/**
 * Register the 'case_study' Custom Post Type
 * Resurrected from the Sugartown Database.
 */
function register_sugartown_case_studies() {
    $labels = array(
        'name'                  => 'Case Studies',
        'singular_name'         => 'Case Study',
        'menu_name'             => 'Case Studies',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Case Study',
        'edit_item'             => 'Edit Case Study',
        'new_item'              => 'New Case Study',
        'view_item'             => 'View Case Study',
        'all_items'             => 'All Case Studies',
        'search_items'          => 'Search Case Studies',
        'not_found'             => 'No case studies found.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'case-studies' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-portfolio',
        
        // ✨ NEW: This line connects your XML tags to this post type
        'taxonomies'         => array( 'category', 'post_tag' ), 
        
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'case_study', $args );
}
add_action( 'init', 'register_sugartown_case_studies' );

/**
 * Register the 'gem' Custom Post Type
 * For the Headless Content Store.
 */
function register_sugartown_gems() {
    $labels = array(
        'name'                  => 'Gems',
        'singular_name'         => 'Gem',
        'menu_name'             => 'Gems',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Gem',
        'edit_item'             => 'Edit Gem',
        'new_item'              => 'New Gem',
        'view_item'             => 'View Gem',
        'all_items'             => 'All Gems',
        'search_items'          => 'Search Gems',
        'not_found'             => 'No gems found.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'gem' ), // The URL will be sugartown.io/gem/title
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-diamond', // 💎 Icon!
        'taxonomies'         => array( 'category', 'post_tag' ), // Enables XML tags
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
        'show_in_rest'       => true, // CRITICAL: Enables the REST API
    );

    register_post_type( 'gem', $args );
}
add_action( 'init', 'register_sugartown_gems' );

/**
 * Register Custom Meta Fields for Gems
 * Allows Python to read/write these fields via REST API.
 */
function register_gem_meta_fields() {
    $meta_fields = array(
        'gem_status',
        'gem_action_item',
        'gem_related_project'
    );

    foreach ( $meta_fields as $field ) {
        register_post_meta( 'gem', $field, array(
            'show_in_rest' => true, // CRITICAL: Exposes to API
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() { return current_user_can( 'edit_posts' ); }
        ) );
    }
}
add_action( 'init', 'register_gem_meta_fields' );

function sugartown_mermaid_shortcode( $atts, $content = '' ) {
    // Don’t escape content; Mermaid needs the raw syntax.
    $content = trim( $content );
    if ( empty( $content ) ) {
        return '';
    }

    return '<pre class="mermaid">' . $content . '</pre>';
}
add_shortcode( 'mermaid', 'sugartown_mermaid_shortcode' );


/**
 * Enqueue Mermaid.js (v11)
 * Upgraded to support "Neo" looks and Frontmatter config.
 */
function sugartown_enqueue_mermaid() {
    // ✨ FIX: Bump to v11.3.0 to support 'look: neo'
    wp_enqueue_script( 
        'mermaid-js', 
        'https://cdn.jsdelivr.net/npm/mermaid@11.3.0/dist/mermaid.min.js', 
        array(), 
        '11.3.0', 
        true 
    );

    // Initialize (Keep it simple so Frontmatter can override it)
    wp_add_inline_script( 
        'mermaid-js', 
        'mermaid.initialize({ startOnLoad: true });' 
    );
}
add_action( 'wp_enqueue_scripts', 'sugartown_enqueue_mermaid' );

/** QUOTE BLOCKS */
add_action( 'init', function() {
    if ( ! function_exists( 'register_block_pattern' ) ) {
        return;
    }

    // Light mode pattern
    register_block_pattern(
        'sugartown/pull-quote-premium-light',
        array(
            'title'       => __( 'Sugartown Premium Pull Quote (Light)', 'sugartown' ),
            'description' => __( 'Large editorial pull quote with soft light background and Sugartown green accent.', 'sugartown' ),
            'categories'  => array( 'text', 'quotes' ),
            'content'     =>
'<!-- wp:html -->
<div style="
  width: 100%;
  margin: 72px auto;
  padding: 40px 48px;
  background: #F8F8FA;
  border-left: 8px solid #2BD4AA;
  border-radius: 4px;
">
  <p style="
    font-size: 1.65rem;
    line-height: 1.55;
    font-style: italic;
    color: #0D1226;
    margin: 0;
  ">
    <strong>“We will need 4–8 post-launch sprints dedicated solely to addressing tech debt, or the site will not scale.”</strong>
  </p>
</div>
<!-- /wp:html -->'
        )
    );

    // Dark mode pattern
    register_block_pattern(
        'sugartown/pull-quote-premium-dark',
        array(
            'title'       => __( 'Sugartown Premium Pull Quote (Dark)', 'sugartown' ),
            'description' => __( 'Large editorial pull quote for dark sections/pages.', 'sugartown' ),
            'categories'  => array( 'text', 'quotes' ),
            'content'     =>
'<!-- wp:html -->
<div style="
  width: 100%;
  margin: 72px auto;
  padding: 40px 48px;
  background: #0D1226;
  border-left: 8px solid #2BD4AA;
  border-radius: 4px;
">
  <p style="
    font-size: 1.65rem;
    line-height: 1.55;
    font-style: italic;
    color: #F8F8FA;
    margin: 0;
  ">
    <strong>“We will need 4–8 post-launch sprints dedicated solely to addressing tech debt, or the site will not scale.”</strong>
  </p>
</div>
<!-- /wp:html -->'
        )
    );
} );
