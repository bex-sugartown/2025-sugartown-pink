<?php
/**
 * Template Name: Gem Archive
 * Template for displaying all Gems (Knowledge Graph)
 * 
 * File location: /wp-content/themes/sugartown-pink/archive-gem.php
 */

get_header(); 
?>

<div class="gem-archive">
    <header class="archive-header">
        <h1>Knowledge Graph</h1>
        <p>Topological content nodes • Not chronological blog posts</p>
    </header>

    <?php if ( have_posts() ) : ?>
        <div class="gem-grid">
            <?php while ( have_posts() ) : the_post(); 
                // Get custom meta fields
                $project_id = get_post_meta( get_the_ID(), 'gem_related_project', true );
                $gem_status = get_post_meta( get_the_ID(), 'gem_status', true );
                $gem_category = get_post_meta( get_the_ID(), 'gem_category', true );
                
                // Get project name from content_store projects
                $project_name = '';
                if ( $project_id ) {
                    // This requires adding projects to WordPress (see option below)
                    // For now, display project ID
                    $project_name = $project_id; 
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
            
            <article class="gem-card" 
                     data-project="<?php echo esc_attr($project_id); ?>"
                     data-status="<?php echo esc_attr($gem_status); ?>"
                     data-category="<?php echo esc_attr($gem_category); ?>">
                
                <!-- Status Badge -->
                <?php if ( $gem_status ) : ?>
                    <span class="gem-status status-<?php echo esc_attr($status_color); ?>">
                        <?php echo esc_html($gem_status); ?>
                    </span>
                <?php endif; ?>
                
                <!-- Title -->
                <h2 class="gem-title">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h2>
                
                <!-- Project Info (NEW) -->
                <?php if ( $project_id ) : ?>
                    <div class="gem-project">
                        <span class="project-label">Project:</span>
                        <a href="?project=<?php echo esc_attr($project_id); ?>" class="project-link">
                            <?php echo esc_html($project_id); ?>
                            <?php if ( $project_name && $project_name !== $project_id ) : ?>
                                <span class="project-name"><?php echo esc_html($project_name); ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- Internal Category -->
                <?php if ( $gem_category ) : ?>
                    <div class="gem-category">
                        <a href="?category=<?php echo esc_attr($gem_category); ?>" class="category-link">
                            <?php echo esc_html($gem_category); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <!-- WordPress Categories (if any) -->
                <?php if ( $categories ) : ?>
                    <div class="gem-wp-categories">
                        <?php foreach ( $categories as $cat ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="wp-cat-tag">
                                <?php echo esc_html( $cat->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- WordPress Tags (if any) -->
                <?php if ( $tags ) : ?>
                    <div class="gem-wp-tags">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="wp-cat-tag tag">
                                <?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Date -->
                <time class="gem-date" datetime="<?php echo get_the_date('c'); ?>">
                    <?php echo get_the_date(); ?>
                </time>
                
            </article>
            
            <?php endwhile; ?>
        </div>
        
        <?php the_posts_pagination(); ?>
        
    <?php else : ?>
        <p>No gems found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
