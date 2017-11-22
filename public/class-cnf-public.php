<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://viewup.com.br/
 * @since      1.0.0
 *
 * @package    Cnf
 * @subpackage Cnf/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cnf
 * @subpackage Cnf/public
 * @author     Viewup <apps@viewup.com.br>
 */
class Cnf_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin template
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string
	 */
	private $template_path;

	private $customizer_section = 'cnf-section';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of the plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name   = $plugin_name;
		$this->version       = $version;
		$this->template_path = defined( 'CUSTOM_404_DIR_PATH' )
			? CUSTOM_404_DIR_PATH . 'public/partials/cnf-404.php'
			: '';

		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'plugins_loaded', array( $this, 'customizer_setup' ) );
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_action( 'wp_head', array( $this, 'custom_css' ) );
	}

	public function register_admin_menu() {
		add_menu_page( __( 'Customize 404 Page', 'cnf' ), __( 'Customize 404 Page', 'cnf' ), 'edit_theme_options', $this->get_customizer_link(),
			'', 'dashicons-hidden' );
	}

	public function get_customizer_link( $admin = true ) {
		$page = get_home_url( null, '/404-page-not-found' );
		if ( ! $admin ) {
			return $page;
		}
		$url = add_query_arg( array(
			'url'                => urlencode( $page ),
			'autofocus[section]' => $this->customizer_section,
		), 'customize.php' );

		return $url;
	}

	public function custom_css() {
		if ( is_customize_preview() || ( is_404() && get_theme_mod( 'cnf-custom-css' ) ) ) {
			echo sprintf( '<style type="text/css" id="cnf-custom-css">%s</style>', get_theme_mod( 'cnf-custom-css', '' ) );
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cnf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cnf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cnf-public.css', array(), $this->version, 'all' );
		if ( is_404() ) {
			wp_enqueue_style( $this->plugin_name );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cnf_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cnf_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cnf-public.js', array( 'jquery' ), $this->version, false );
		if ( is_404() ) {
			wp_enqueue_script( $this->plugin_name );
		}
	}

	public function widgets_init() {
		register_sidebar( array(
			'name'          => __( '404 Widgets', 'cnf' ),
			'description'   => __( 'Add here widgets to appear on your 404 page.', 'cnf' ) .
			                   ( is_customize_preview() ?
				                   sprintf( '<br /><a href="javascript:wp.customize.section( \'%s\' ).focus();">', $this->customizer_section ) .
				                   __( 'Click here to customize 404 page', 'cnf' ) .
				                   '</a>' : '' ),
			'id'            => 'cnf-widgets',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => "</div>\n",
		) );
	}

	/**
	 * Get the template file
	 *
	 * @since 1.0.0
	 *
	 * @param string $template
	 * @param string $type
	 * @param array $templates
	 *
	 * @return string
	 */
	public function get_template( $template = '', $type = '', $templates = array() ) {
		$template = get_theme_mod( 'cnf-custom-page' ) ? $this->template_path : $template;

		return apply_filters( 'cnf_404_template', $template, $type, $templates );
	}

	public function customizer_setup() {
		if ( ! class_exists( 'Cnf_Kirki' ) ) {
			return;
		}

		$option_name = $this->plugin_name . '-customizer';
		new Cnf_Kirki();

		Cnf_Kirki::add_config( $this->plugin_name, array(
			'capability'  => 'edit_theme_options',
			'option_type' => 'theme_mod',
		) );

		Cnf_Kirki::add_section( $this->customizer_section, array(
			'title'       => esc_attr__( '404 Page' ),
			'description' => esc_attr__( 'Customize your 404 Page.', 'cnf' ),
			'priority'    => 160,
		) );

		$this->register_customizer_fields();

	}

	private function register_customizer_fields() {

		$cp_enabled = array(
			array(
				'setting'  => 'cnf-custom-page',
				'operator' => '==',
				'value'    => '1',
			)
		);

		$this
			->add_field( 'cnf-404-link', array(
				'type'    => 'custom',
				'default' => sprintf( '<a class="button cnf-404-button" href="javascript:wp.customize.previewer.previewUrl( \'%1$s\' )">%2$s</a>',
					$this->get_customizer_link( false ), __( 'Go to 404 page.', 'fa2' ) ),
			) )
			->add_field( 'cnf-custom-page', array(
				'type'            => 'checkbox',
				'label'           => __( 'Custom 404 Page', 'cnf' ),
				'description'     => __( 'Overrides the theme 404 page and create a brand new customizable page.', 'cnf' ),
				'default'         => '0',
				// FIXME: check a way to force refresh onchange
				'partial_refresh' => array(
					'cnf-force-refresh' => array(
						'selector'        => '.cnf-never-selector',
						'render_callback' => '__return_false',
					)
				)
			) )
			->add_field( 'cnf-body-background', array(
				'type'        => 'background',
				'label'       => __( 'Body Background', 'cnf' ),
				'description' => __( 'Customize Body Background on 404 Page', 'cnf' ),
				'output'      => array(
					array(
						'element'  => 'body.error404',
						'property' => 'background',
					),
				),
			) )
			->add_field( 'cnf-body-color', array(
				'type'    => 'color',
				'label'   => __( 'Body Text Color', 'cnf' ),
				'choices' => array(
					'alpha' => true,
				),
				'output'  => array(
					array(
						'element'  => 'body.error404',
						'property' => 'color'
					),
				),
			) )
			->add_field( 'cnf-content', array(
				'type'            => 'editor',
				'label'           => __( 'Content', 'cnf' ),
				'description'     => __( 'Add some custom message on your 404 page.', 'cnf' ),
				'active_callback' => $cp_enabled,
				'partial_refresh' => array(
					'cnf-content' => array(
						'selector'        => '.cnf-text',
						'render_callback' => function () {
							echo get_theme_mod( 'cnf-content' );
						},
					),
				),
			) )
			->add_field( 'cnf-widget-message', array(
				'type'            => 'custom',
				'label'           => __( 'Add Widgets', 'cnf' ),
				'active_callback' => $cp_enabled,
				'description'     => __( 'Customize your 404 page with widgets.', 'cnf' ),
				'default'         => sprintf( '<a class="button add-new-widget cnf-add-widgets" href="javascript:wp.customize.section( \'sidebar-widgets-cnf-widgets\' ).focus();">%s</a>',
					__( 'Add a Widget', 'cnf' ) ),
			) )
			->add_field( 'cnf-container-width', array(
				'type'            => 'dimension',
				'label'           => __( 'Container Max Width', 'cnf' ),
				'description'     => __( 'Change container Maximum width', 'cnf' ),
				'default'         => '960px',
				'active_callback' => $cp_enabled,
				'output'          => array(
					array(
						'element'  => '.cnf-content',
						'property' => 'max-width'
					),
				),

			) )
			->add_field( 'cnf-container-padding', array(
				'type'            => 'spacing',
				'label'           => __( 'Container Padding', 'cnf' ),
				'description'     => __( 'Change container spacing', 'cnf' ),
				'active_callback' => $cp_enabled,
				'default'         => array(
					'top'    => '0px',
					'right'  => '20px',
					'bottom' => '0px',
					'left'   => '20px',
				),
				'output'          => array(
					array(
						'element'  => '.cnf-content',
						'property' => 'padding'
					),
				),

			) )
			->add_field( 'cnf-custom-css', array(
				'type'            => 'code',
				'label'           => __( 'Custom CSS', 'cnf' ),
				'choices'         => array( 'language' => 'css' ),
				'priority'        => 1000,
				'default'         => sprintf( "/*\n%s\n*/\n", __( 'Add your custom 404 page CSS here. ', '_s' ) ),
				'partial_refresh' => array(
					'cnf-css-refresh' => array(
						'selector'        => '#cnf-custom-css',
						'render_callback' => function () {
							echo get_theme_mod( 'cnf-custom-css' );
						},
					)
				)
			) )
			///
			/// End adding fields
			///
		;
	}

	private function add_field( $config_id, $args = array() ) {
		$args = wp_parse_args( $args, array(
			'type'     => 'text',
			'settings' => $config_id,
			'section'  => $this->customizer_section,
		) );

		$args['label']       = isset( $args['label'] ) ? __( $args['label'], 'cnf' ) : '';
		$args['description'] = isset( $args['description'] ) ? __( $args['description'], 'cnf' ) : '';
		$args['transport']   = isset( $args['output'] ) ? 'auto' : '';

		Cnf_Kirki::add_field( $this->plugin_name, $args );

		return $this;
	}

}
