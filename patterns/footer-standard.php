<?php
/**
 * Title: Footer Standard (Dark)
 * Slug: sugartown-pink/footer-standard
 * Categories: footer
 * Block Types: core/template-part/footer
 * Description: Standard dark footer with site title, tagline, navigation and copyright
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}},"elements":{"link":{"color":{"text":"#94A3B8"}}},"color":{"text":"#94A3B8"}},"backgroundColor":"accent-5","layout":{"type":"constrained"}} -->
<div class="wp-block-group has-accent-5-background-color has-text-color has-background has-link-color" style="color:#94A3B8;padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">

  <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
  <div class="wp-block-group">

    <!-- wp:site-title /-->

    <!-- wp:site-tagline {"textAlign":"center"} /-->

  </div>
  <!-- /wp:group -->

  <!-- wp:spacer {"height":"var:preset|spacing|20"} -->
  <div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
  <!-- /wp:spacer -->

  <!-- wp:navigation {"layout":{"type":"flex","justifyContent":"center","flexWrap":"nowrap"}} -->
    <!-- wp:navigation-link {"label":"AI Ethics &amp; Operations","url":"#"} /-->
    <!-- wp:navigation-link {"label":"Privacy &amp; Terms","url":"#"} /-->
    <!-- wp:social-links {"layout":{"type":"flex","justifyContent":"center"}} -->
    <ul class="wp-block-social-links">
      <!-- wp:social-link {"url":"https://www.linkedin.com/in/beckyhead/","service":"linkedin"} /-->
    </ul>
    <!-- /wp:social-links -->
    <!-- wp:navigation-link {"label":"Contact","url":"#"} /-->
  <!-- /wp:navigation -->

  <!-- wp:spacer {"height":"var:preset|spacing|20"} -->
  <div style="height:var(--wp--preset--spacing--20)" aria-hidden="true" class="wp-block-spacer"></div>
  <!-- /wp:spacer -->

  <!-- wp:paragraph {"align":"center","fontSize":"small"} -->
  <p class="has-text-align-center has-small-font-size">© <?php echo date('Y'); ?> <a href="<?php echo esc_url( home_url('/') ); ?>">Sugartown Digital</a>. All rights reserved.</p>
  <!-- /wp:paragraph -->

</div>
<!-- /wp:group -->
