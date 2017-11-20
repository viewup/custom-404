<?php
/**
 * CNF 404 page
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://viewup.com.br/
 * @since      1.0.0
 *
 * @package    Cnf
 * @subpackage Cnf/public/partials
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<main class="cnf-wrapper">
    <div class="cnf-position">
        <section class="cnf-content">

            <div class="cnf-text"><?php echo get_theme_mod( 'cnf-content', is_customize_preview() ? __( 'Customize your 404 message and widgets', 'cnf' ) : sprintf( '<h1>%s</h1>', __( '404 - Page not found', 'cnf' ) ) ) ?></div>

		    <?php if ( is_active_sidebar( 'cnf-widgets' ) ) : ?>
                <aside id="cnf-widgets" class="cnf-widgets widget-area">
				    <?php dynamic_sidebar( 'cnf-widgets' ); ?>
                </aside><!-- #cnf-widgets -->
		    <?php endif; ?>

        </section>
    </div>
</main>

<body <?php body_class(); ?>>

<?php wp_footer(); ?>
</body>
</html>
