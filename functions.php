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
	 * Registers block pattern categories.
	 *
	 * @since Sugartown Pink 1.0
	 *
	 * @return void
	 */
	function sugartown_pink_pattern_categories() {

		// Full-page layouts
		register_block_pattern_category(
			'sugartown_pink_page',
			array(
				'label'       => __( 'Pages', 'sugartown-pink' ),
				'description' => __( 'A collection of full page layouts.', 'sugartown-pink' ),
			)
		);

		// Post format layouts
		register_block_pattern_category(
			'sugartown_pink_post-format',
			array(
				'label'       => __( 'Post formats', 'sugartown-pink' ),
				'description' => __( 'A collection of post format patterns.', 'sugartown-pink' ),
			)
		);

		// Sugartown system & utility patterns (callouts, cards, components)
		register_block_pattern_category(
			'sugartown',
			array(
				'label'       => __( 'Sugartown', 'sugartown-pink' ),
				'description' => __( 'Sugartown system components, callouts, and utilities.', 'sugartown-pink' ),
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
        'capability_type'    => 'post',
        'rewrite'            => array(
            'slug'       => 'gem',       // single: /gem/{slug}
            'with_front' => false,
         ), 
        'has_archive'        => 'knowledge-graph',                     // archive becomes /knowledge-graph/
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
 * 
 * Taxonomy v4: gem_category removed (using WordPress categories instead)
 */
function register_gem_meta_fields() {
    $meta_fields = array(
        'gem_status',
        'gem_action_item',
        'gem_related_project',
        // 'gem_category' - REMOVED in v4: Using WordPress categories as single source of truth
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
add_action('template_redirect', function () {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  if ($path === 'knowledge_graph') {
    wp_redirect(home_url('/knowledge-graph/'), 301);
    exit;
  }
});

function sugartown_mermaid_shortcode( $atts, $content = '' ) {
    // Don't escape content; Mermaid needs the raw syntax.
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
    <strong>"We will need 4–8 post-launch sprints dedicated solely to addressing tech debt, or the site will not scale."</strong>
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
    <strong>"We will need 4–8 post-launch sprints dedicated solely to addressing tech debt, or the site will not scale."</strong>
  </p>
</div>
<!-- /wp:html -->'
        )
    );
} );

// ==========================================
// GEM ARCHIVE ENHANCEMENTS
// ==========================================

/**
 * Enqueue gem archive styles (if using separate CSS file)
 * Only loads on the gem archive page for performance
 */
function sugartown_enqueue_gem_archive_styles() {
    if ( is_post_type_archive('gem') ) {
        wp_enqueue_style(
            'gem-archive-styles',
            get_stylesheet_directory_uri() . '/assets/css/gem-archive-styles.css',
            array('sugartown-pink-style'),
            '1.0.0'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'sugartown_enqueue_gem_archive_styles' );

/**
 * Helper function to get project name from project ID
 * This matches the project definitions in content_store.py
 * 
 * Update this array when you add new projects to content_store.py
 */
function sugartown_get_project_name( $project_id ) {
    $projects = array(
        'PROJ-001' => 'Sugartown CMS',
        'PROJ-002' => 'The Resume Factory',
        'PROJ-003' => 'Sugartown Pink Design System',
        'PROJ-004' => 'Knowledge Graph Viz Engine',
    );
    
    return isset($projects[$project_id]) ? $projects[$project_id] : $project_id;
}

function sugartown_enqueue_kg_filters() {
    if (is_post_type_archive('gem')) {
        wp_enqueue_script(
            'kg-filters',
            get_template_directory_uri() . '/assets/js/kg-filters.js',
            array(),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'sugartown_enqueue_kg_filters');

/* =========================================================
   GEM: Metadata under title (clickable where appropriate)
   ========================================================= */

if ( ! function_exists('sugartown_gem_archive_url') ) {
  function sugartown_gem_archive_url($param_name, $param_value) {
    $base = get_post_type_archive_link('gem'); // e.g. /knowledge-graph/
    return add_query_arg(array($param_name => $param_value), $base);
  }
}

if ( ! function_exists('sugartown_render_gem_metadata_dl') ) {
  function sugartown_render_gem_metadata_dl($post_id) {
    if ( ! $post_id ) return '';

    $project_id  = get_post_meta($post_id, 'gem_related_project', true);
    $gem_status  = get_post_meta($post_id, 'gem_status', true);
    $action_item = get_post_meta($post_id, 'gem_action_item', true);

    $project_name = '';
    if ( $project_id && function_exists('sugartown_get_project_name') ) {
      $project_name = sugartown_get_project_name($project_id);
    }

    $cats = get_the_category($post_id);
    $primary_cat = ( $cats && ! empty($cats) ) ? $cats[0] : null;

    $tags = get_the_tags($post_id);

    $date_iso   = get_the_date('c', $post_id);
    $date_human = get_the_date('F j, Y', $post_id);

    $rows = array();

    /* Project (filterable) */
    if ( $project_id ) {
      $label = $project_id;
      if ( $project_name && $project_name !== $project_id ) {
        $label .= ' • ' . $project_name;
      }
      $rows[] = array(
        'dt' => 'Project',
        'dd' => '<a href="' . esc_url( sugartown_gem_archive_url('project', $project_id) ) . '">' . esc_html($label) . '</a>'
      );
    }

    /* Status (filterable enum) */
    if ( $gem_status ) {
      $rows[] = array(
        'dt' => 'Status',
        'dd' => '<a href="' . esc_url( sugartown_gem_archive_url('status', $gem_status) ) . '">' . esc_html($gem_status) . '</a>'
      );
    }

    /* Next step (open text — NOT filterable) */
    if ( $action_item ) {
      $rows[] = array(
        'dt' => 'Next step',
        'dd' => esc_html($action_item)
      );
    }

    /* Category (filterable) */
    if ( $primary_cat ) {
      $rows[] = array(
        'dt' => 'Category',
        'dd' => '<a href="' . esc_url( sugartown_gem_archive_url('wp_category', $primary_cat->term_id) ) . '">' . esc_html($primary_cat->name) . '</a>'
      );
    }

   /* Tags (filterable, rendered as st-chips) */
if ( $tags && ! is_wp_error($tags) ) {
  $chips = array();

  foreach ( $tags as $t ) {
    $chips[] =
      '<a class="st-chip" href="' .
      esc_url( sugartown_gem_archive_url('wp_tag', $t->term_id) ) .
      '">' . esc_html($t->name) . '</a>';
  }

  $rows[] = array(
    'dt' => 'Tags',
    'dd' => '<div class="st-chips" role="list">' . implode('', $chips) . '</div>'
  );
}

    /* Date (informational) */
    $rows[] = array(
      'dt' => 'Date',
      'dd' => '<time datetime="' . esc_attr($date_iso) . '">' . esc_html($date_human) . '</time>'
    );

    if ( empty($rows) ) return '';

    $out  = '<dl class="st-metadata st-metadata--gem" aria-label="Gem metadata">';
    foreach ( $rows as $r ) {
      $out .= '<dt>' . esc_html($r['dt']) . '</dt>';
      $out .= '<dd>' . $r['dd'] . '</dd>';
    }
    $out .= '</dl>';

    return $out;
  }
}

/* Inject directly after the post title block */
add_filter('render_block', function($block_content, $block) {
  if ( ! is_singular('gem') ) return $block_content;
  if ( empty($block['blockName']) || $block['blockName'] !== 'core/post-title' ) return $block_content;

  return $block_content . sugartown_render_gem_metadata_dl( get_the_ID() );
}, 10, 2);

/* =========================================================
   GEM: Remove bottom Post Terms block (categories/tags) on single gems
   ========================================================= */

add_filter('render_block', function($block_content, $block) {
  if ( ! is_singular('gem') ) return $block_content;
  if ( empty($block['blockName']) ) return $block_content;

  // Removes the bottom "Categories / Tags" block output
  if ( $block['blockName'] === 'core/post-terms' ) {
    return '';
  }

  return $block_content;
}, 12, 2);
