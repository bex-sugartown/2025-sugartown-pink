<?php
/**
 * Template Name: Gem Archive
 * Template Post Type: page
 * Template for displaying all Gems (Knowledge Graph)
 * Version: 6.0 - Taxonomy v4 (WordPress categories only, no gem_category meta)
 */

get_header(); 

// Get filter parameters from URL
$filter_project = isset($_GET['project']) ? sanitize_text_field($_GET['project']) : '';
$filter_status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';
$filter_wp_category = isset($_GET['wp_category']) ? intval($_GET['wp_category']) : 0;
$filter_wp_tag = isset($_GET['wp_tag']) ? intval($_GET['wp_tag']) : 0;

// Determine active filter
$active_filter = '';
$filter_label = '';
$filter_value = '';

if ($filter_project) {
    $active_filter = 'project';
    $filter_label = 'PROJECT';
    $filter_value = $filter_project . ' • ' . sugartown_get_project_name($filter_project);
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

// Build custom query args
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
    'post_type' => 'gem',
    'posts_per_page' => 9,
    'paged' => $paged,
    'post_status' => 'publish',
    'orderby' => 'date',
    'order' => 'DESC'
);

// Apply taxonomy filters (WP Categories & Tags)
if ($filter_wp_category) {
    $args['cat'] = $filter_wp_category;
}

if ($filter_wp_tag) {
    $args['tag_id'] = $filter_wp_tag;
}

// Apply meta filters (Project, Status)
if ($filter_project || $filter_status) {
    $args['meta_query'] = array('relation' => 'AND');
    
    if ($filter_project) {
        $args['meta_query'][] = array(
            'key' => 'gem_related_project',
            'value' => $filter_project,
            'compare' => '='
        );
    }
    
    if ($filter_status) {
        $args['meta_query'][] = array(
            'key' => 'gem_status',
            'value' => $filter_status,
            'compare' => '='
        );
    }
}

// Create custom query
$gem_query = new WP_Query($args);

// Helper function for archive URLs
function gem_archive_url($param_name, $param_value) {
  $base = get_post_type_archive_link('gem'); // will be /knowledge-graph/
  return add_query_arg(array($param_name => $param_value), $base);
}
?>

<div class="gem-archive">
    <header class="archive-header">
        <?php if ($active_filter) : ?>
            <div class="st-callout st-callout--filter">
                <span class="filter-label"><?php echo esc_html($filter_label); ?>:</span>
                <span class="filter-value"><?php echo esc_html($filter_value); ?></span>
                <a href="<?php echo esc_url( get_post_type_archive_link('gem') ); ?>" class="clear-filter">✕ Clear Filter</a>
            </div>
        <?php else : ?>
            <h1>Knowledge Graph</h1>
            <p>Topological content nodes • Not chronological blog posts</p>
        <?php endif; ?>
    </header>
    
    <?php
    // Treat any of these as "results mode"
    $filter_keys = array('project', 'category', 'wp_category', 'tag', 'wp_tag', 'status', 's'); // add more if you use them
    $has_filters = false;

    foreach ($filter_keys as $k) {
    if (isset($_GET[$k]) && $_GET[$k] !== '') {
        $has_filters = true;
        break;
    }
    }
    ?>

    <?php
    if (!$has_filters) {
    $intro_page = get_page_by_path('knowledge-graph-intro');
    if ($intro_page) {
        echo '<section class="kg-intro st-container">';
        echo apply_filters('the_content', $intro_page->post_content);
        echo '</section>';
    }
    }
    ?>

    <?php if ( $gem_query->have_posts() ) : ?>
        
        <!-- Result Count Display -->
        <div class="gem-results-info">
            <?php
            $showing_start = (($paged - 1) * 9) + 1;
            $showing_end = min($showing_start + $gem_query->post_count - 1, $gem_query->found_posts);
            ?>
            <p class="results-count">
                Showing <strong><?php echo $showing_start; ?>-<?php echo $showing_end; ?></strong> 
                of <strong><?php echo $gem_query->found_posts; ?></strong> gems
            </p>
        </div>
        
        <div class="st-grid">
            <?php while ( $gem_query->have_posts() ) : $gem_query->the_post(); 
                // Get custom meta fields (Taxonomy v4: no gem_category)
                $project_id = get_post_meta( get_the_ID(), 'gem_related_project', true );
                $gem_status = get_post_meta( get_the_ID(), 'gem_status', true );
                $action_item = get_post_meta( get_the_ID(), 'gem_action_item', true );
                
                // Get project name
                $project_name = '';
                if ( $project_id ) {
                    $project_name = sugartown_get_project_name( $project_id );
                }
                
                // Build eyebrow: "PROJ-001 • Sugartown Headless CMS"
                $eyebrow = '';
                if ( $project_id ) {
                    $eyebrow = $project_id;
                    if ( $project_name && $project_name !== $project_id ) {
                        $eyebrow .= ' • ' . $project_name;
                    }
                }
                
                // Get WordPress categories and tags
                $categories = get_the_category();
                $tags = get_the_tags();

                // Build subtitle: Single WordPress category (clickable)
                $subtitle = '';
                $subtitle_url = '';
                if ( $categories && !empty($categories) ) {
                    $primary_cat = $categories[0];
                    $subtitle = $primary_cat->name;
                    $subtitle_url = gem_archive_url('wp_category', $primary_cat->term_id);
                }
                
                // Status badge color mapping
                $status_colors = array(
                    'Active'       => 'active',
                    'Shipped'      => 'shipped',
                    'Draft'        => 'draft',
                    'Backlog'      => 'backlog',
                    'Done'         => 'done',
                    'In Progress'  => 'in-progress',
                    'Live'         => 'live'
                );
                $status_class = isset($status_colors[$gem_status]) ? $status_colors[$gem_status] : 'default';
                
                // Dark card trigger tags (slugs)
                $dark_trigger_slugs = array('system', 'meta', 'architecture', 'dx');
                $is_dark = false;
                if ( $tags ) {
                    foreach ( $tags as $t ) {
                        if ( in_array( $t->slug, $dark_trigger_slugs, true ) ) {
                            $is_dark = true;
                            break;
                        }
                    }
                }
                
                $card_classes = 'st-card' . ( $is_dark ? ' st-card--dark' : '' );
            ?>
            
            <article class="<?php echo esc_attr($card_classes); ?>"
                     data-project="<?php echo esc_attr($project_id); ?>"
                     data-status="<?php echo esc_attr($gem_status); ?>">

                
                <!-- Header: Eyebrow/Title/Subtitle stack left, Badge floats right -->
                <div class="st-card__header">
                    <div class="st-card__header-content">
                        <?php if ( $eyebrow ) : ?>
                            <div class="st-card__eyebrow">
                                <a href="<?php echo esc_url( gem_archive_url('project', $project_id) ); ?>">
                                    <?php echo esc_html($eyebrow); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <h2 class="st-card__title">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>
                        
                        <?php if ( $subtitle ) : ?>
                            <div class="st-card__subtitle">
                                <span class="st-label">Category:</span>
                                <a href="<?php echo esc_url($subtitle_url); ?>">
                                    <?php echo esc_html($subtitle); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ( $gem_status ) : ?>
                        <span class="st-badge st-badge--<?php echo esc_attr($status_class); ?>">
                            <?php echo esc_html($gem_status); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <!-- Body: Tags -->
                <div class="st-card__body">
                    <?php if ( $tags ) : ?>
                        <div class="st-card__meta">
                            <span class="st-label">Tags:</span>
                            <div class="st-card__tags">
                                <?php foreach ( $tags as $tag ) : ?>
                                    <a href="<?php echo esc_url( gem_archive_url('wp_tag', $tag->term_id) ); ?>" 
                                       class="st-card__tag">
                                        <?php echo esc_html( $tag->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Footer: Next Step + Date -->
                <div class="st-card__footer">
                    <?php if ( $action_item ) : ?>
                        <div class="st-card__action">
                            <span class="st-label">Next Step:</span>
                            <span class="st-card__action-text"><?php echo esc_html($action_item); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <time class="st-card__date" datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>
                
            </article>
            
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        
        <!-- Pagination (only show if more than 1 page) -->
        <?php if ( $gem_query->max_num_pages > 1 ) : ?>
            <div class="gem-pagination">
                <?php 
                echo paginate_links(array(
                    'total' => $gem_query->max_num_pages,
                    'current' => $paged,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                    'type' => 'list'
                ));
                ?>
            </div>
        <?php endif; ?>
        
    <?php else : ?>
        <div class="no-gems-message">
            <p>No gems found matching your criteria.</p>
            <?php if ($active_filter) : ?>
                <p><a href="<?php echo esc_url( add_query_arg('post_type', 'gem', home_url('/')) ); ?>" class="button">View All Gems</a></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
