<?php
/**
 * Template Name: Gem Archive
 * Template for displaying all Gems (Knowledge Graph)
 * 
 * File location: /wp-content/themes/sugartown-pink/archive-gem.php
 * Version: 4.0 - Revised card layout
 */

get_header(); 

// Get filter parameters from URL
$filter_project = isset($_GET['project']) ? sanitize_text_field($_GET['project']) : '';
$filter_category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
$filter_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$filter_wp_category = isset($_GET['wp_category']) ? intval($_GET['wp_category']) : 0;
$filter_wp_tag = isset($_GET['wp_tag']) ? intval($_GET['wp_tag']) : 0;

// Determine what we're filtering by
$active_filter = '';
$filter_label = '';
$filter_value = '';

if ($filter_project) {
    $active_filter = 'project';
    $filter_label = 'PROJECT';
    $filter_value = $filter_project . ' • ' . sugartown_get_project_name($filter_project);
} elseif ($filter_category) {
    $active_filter = 'category';
    $filter_label = 'CATEGORY';
    $filter_value = $filter_category;
} elseif ($filter_status) {
    $active_filter = 'status';
    $filter_label = 'STATUS';
    $filter_value = $filter_status;
} elseif ($filter_wp_category) {
    $active_filter = 'wp_category';
    $filter_label = 'CATEGORY';
    $cat = get_category($filter_wp_category);
    $filter_value = $cat ? $cat->name : '';
} elseif ($filter_wp_tag) {
    $active_filter = 'wp_tag';
    $filter_label = 'TAG';
    $tag = get_tag($filter_wp_tag);
    $filter_value = $tag ? $tag->name : '';
}

// Modify query if filtering
if ($filter_wp_category || $filter_wp_tag) {
    add_action('pre_get_posts', function($query) use ($filter_wp_category, $filter_wp_tag) {
        if (is_admin() || !$query->is_main_query()) return;
        
        if ($filter_wp_category) {
            $query->set('cat', $filter_wp_category);
        }
        if ($filter_wp_tag) {
            $query->set('tag_id', $filter_wp_tag);
        }
    });
}

// Helper function to build archive filter URL
// Always includes post_type=gem to ensure we stay on archive page
function gem_archive_url($param_name, $param_value) {
    return add_query_arg(
        array(
            'post_type' => 'gem',
            $param_name => $param_value
        ),
        home_url('/')
    );
}
?>

<div class="gem-archive">
    <header class="archive-header">
        <?php if ($active_filter) : ?>
            <!-- Filter Header -->
            <div class="filter-active-notice st-callout st-callout--filter">
                <span class="filter-label"><?php echo esc_html($filter_label); ?>:</span>
                <span class="filter-value"><?php echo esc_html($filter_value); ?></span>
                <a href="<?php echo esc_url( add_query_arg('post_type', 'gem', home_url('/')) ); ?>" class="clear-filter">✕ Clear Filter</a>
            </div>
        <?php else : ?>
            <!-- Default Header -->
            <h1>Knowledge Graph</h1>
            <p>Topological content nodes • Not chronological blog posts</p>
        <?php endif; ?>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="gem-grid">
            <?php while ( have_posts() ) : the_post(); 
                // Get custom meta fields
                $project_id = get_post_meta( get_the_ID(), 'gem_related_project', true );
                $gem_status = get_post_meta( get_the_ID(), 'gem_status', true );
                $gem_category = get_post_meta( get_the_ID(), 'gem_category', true );
                $action_item = get_post_meta( get_the_ID(), 'gem_action_item', true );
                
                // Get project name
                $project_name = '';
                if ( $project_id ) {
                    $project_name = sugartown_get_project_name( $project_id );
                }
                
                // Get WordPress categories and tags
                $categories = get_the_category();
                $tags = get_the_tags();
                
                // Status badge color mapping
                $status_colors = array(
                    'Active'   => 'green',
                    'Shipped'  => 'blue',
                    'Draft'    => 'yellow',
                    'Backlog'  => 'gray',
                    'Done'     => 'purple',
                    'In Progress' => 'orange'
                );
                $status_color = isset($status_colors[$gem_status]) ? $status_colors[$gem_status] : 'gray';
            ?>
            
            <article class="gem-card st-card st-card--light" 
                     data-project="<?php echo esc_attr($project_id); ?>"
                     data-status="<?php echo esc_attr($gem_status); ?>"
                     data-category="<?php echo esc_attr($gem_category); ?>">
                
                <!-- Card Top: Title + Status Badge -->
                <div class="gem-card-header">
                    <h2 class="gem-title">
                        <a href="<?php the_permalink(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    
                    <?php if ( $gem_status ) : ?>
                        <span class="gem-status status-<?php echo esc_attr($status_color); ?>">
                            <?php echo esc_html($gem_status); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Card Body: Metadata (aligned to top) -->
                <div class="gem-card-body">
                    
                    <!-- Project -->
                    <?php if ( $project_id ) : ?>
                        <div class="gem-meta-row">
                            <span class="meta-label">Project:</span>
                            <a href="<?php echo esc_url( gem_archive_url('project', $project_id) ); ?>" class="meta-link">
                                <?php echo esc_html($project_id); ?>
                                <?php if ( $project_name && $project_name !== $project_id ) : ?>
                                    • <?php echo esc_html($project_name); ?>
                                <?php endif; ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Category: Gem Category + WP Categories on same line -->
                    <?php if ( $gem_category || $categories ) : ?>
                        <div class="gem-meta-row">
                            <span class="meta-label">Category:</span>
                            <div class="meta-badges">
                                <!-- Gem Category -->
                                <?php if ( $gem_category ) : ?>
                                    <a href="<?php echo esc_url( gem_archive_url('category', $gem_category) ); ?>" class="meta-badge">
                                        <?php echo esc_html($gem_category); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <!-- WP Categories -->
                                <?php if ( $categories ) : ?>
                                    <?php foreach ( $categories as $cat ) : ?>
                                        <a href="<?php echo esc_url( gem_archive_url('wp_category', $cat->term_id) ); ?>" class="meta-badge">
                                            <?php echo esc_html( $cat->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Tags -->
                    <?php if ( $tags ) : ?>
                        <div class="gem-meta-row">
                            <span class="meta-label">Tags:</span>
                            <div class="meta-badges">
                                <?php foreach ( $tags as $tag ) : ?>
                                    <a href="<?php echo esc_url( gem_archive_url('wp_tag', $tag->term_id) ); ?>" class="meta-badge tag">
                                        #<?php echo esc_html( $tag->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                </div>
                
                <!-- Card Footer: Next Step + Date (aligned to bottom) -->
                <div class="gem-card-footer">
                    <?php if ( $action_item ) : ?>
                        <div class="gem-next-step">
                            <span class="meta-label">Next Step:</span>
                            <span class="next-step-text"><?php echo esc_html($action_item); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <time class="gem-date" datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>
                
            </article>
            
            <?php endwhile; ?>
        </div>
        
        <?php the_posts_pagination(); ?>
        
    <?php else : ?>
        <p>No gems found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
