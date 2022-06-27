<?php
namespace ElementorSuperCat\Widgets;
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use Elementor\Includes\Widgets\Traits\Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
* Elementor button widget.
*
* Elementor widget that displays a button with the ability to control every
* aspect of the button design.
*/
class Param_Button extends Widget_Base {

  use Button_Trait;

  /**
  * Get widget name.
  *
  * Retrieve button widget name.
  *
  * @access public
  *
  * @return string Widget name.
  */
  public function get_name() {
    return 'param-button';
  }

  /**
  * Get widget title.
  *
  * Retrieve button widget title.
  *
  * @access public
  *
  * @return string Widget title.
  */
  public function get_title() {
    return __( 'Param Button', 'elementor-super-cat' );
  }

  /**
  * Get widget icon.
  *
  * Retrieve button widget icon.
  *
  * @access public
  *
  * @return string Widget icon.
  */
  public function get_icon() {
    return 'eicon-button';
  }

  /**
  * Get widget categories.
  *
  * Retrieve the list of categories the button widget belongs to.
  *
  * Used to determine where to display the widget in the editor.
  *
  * @access public
  *
  * @return array Widget categories.
  */
  public function get_categories() {
    return [ 'super-cat' ];
  }

  /**
  * Get button sizes.
  *
  * Retrieve an array of button sizes for the button widget.
  *
  * @access public
  * @static
  *
  * @return array An array containing button sizes.
  */
  public static function get_button_sizes() {
    return [
      'xs' => __( 'Extra Small', 'elementor-super-cat' ),
      'sm' => __( 'Small', 'elementor-super-cat' ),
      'md' => __( 'Medium', 'elementor-super-cat' ),
      'lg' => __( 'Large', 'elementor-super-cat' ),
      'xl' => __( 'Extra Large', 'elementor-super-cat' ),
    ];
  }

  /**
  * Register button widget controls.
  *
  * Adds different input fields to allow the user to change and customize the widget settings.
  *
  * @access protected
  */
  protected function register_controls() {
    $this->start_controls_section(
      'section_button',
      [
        'label' => __( 'Button', 'elementor-super-cat' ),
      ]
    );

    $this->custom_register_button_content_controls();

    $this->end_controls_section();

    $this->start_controls_section(
      'section_style',
      [
        'label' => __( 'Button', 'elementor-super-cat' ),
        'tab' => Controls_Manager::TAB_STYLE,
      ]
    );

    $this->register_button_style_controls();

    $this->end_controls_section();
  }

  /**
  * Render button widget output on the frontend.
  *
  * Written in PHP and used to generate the final HTML.
  *
  * @since 1.0.0
  * @access protected
  */
  protected function render() {
    $this->render_button();
  }


  protected function custom_register_button_content_controls( $args = [] ) {
    $default_args = [
      'section_condition' => [],
      'button_default_text' => esc_html__( 'Click here', 'elementor' ),
      'text_control_label' => esc_html__( 'Text', 'elementor' ),
      'alignment_control_prefix_class' => 'elementor%s-align-',
      'alignment_default' => '',
      'icon_exclude_inline_options' => [],
    ];

    $args = wp_parse_args( $args, $default_args );

    $this->add_control(
      'button_type',
      [
        'label' => esc_html__( 'Type', 'elementor' ),
        'type' => Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          '' => esc_html__( 'Default', 'elementor' ),
          'info' => esc_html__( 'Info', 'elementor' ),
          'success' => esc_html__( 'Success', 'elementor' ),
          'warning' => esc_html__( 'Warning', 'elementor' ),
          'danger' => esc_html__( 'Danger', 'elementor' ),
        ],
        'prefix_class' => 'elementor-button-',
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'text',
      [
        'label' => $args['text_control_label'],
        'type' => Controls_Manager::TEXT,
        'dynamic' => [
          'active' => true,
        ],
        'default' => $args['button_default_text'],
        'placeholder' => $args['button_default_text'],
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'link',
      [
        'label' => esc_html__( 'Link', 'elementor' ),
        'type' => Controls_Manager::TEXT,
        'dynamic' => [
          'active' => true,
        ],
        'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
        'default' => [
          'url' => '#',
        ],
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_responsive_control(
      'align',
      [
        'label' => esc_html__( 'Alignment', 'elementor' ),
        'type' => Controls_Manager::CHOOSE,
        'options' => [
          'left'    => [
            'title' => esc_html__( 'Left', 'elementor' ),
            'icon' => 'eicon-text-align-left',
          ],
          'center' => [
            'title' => esc_html__( 'Center', 'elementor' ),
            'icon' => 'eicon-text-align-center',
          ],
          'right' => [
            'title' => esc_html__( 'Right', 'elementor' ),
            'icon' => 'eicon-text-align-right',
          ],
          'justify' => [
            'title' => esc_html__( 'Justified', 'elementor' ),
            'icon' => 'eicon-text-align-justify',
          ],
        ],
        'prefix_class' => $args['alignment_control_prefix_class'],
        'default' => $args['alignment_default'],
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'size',
      [
        'label' => esc_html__( 'Size', 'elementor' ),
        'type' => Controls_Manager::SELECT,
        'default' => 'sm',
        'options' => self::get_button_sizes(),
        'style_transfer' => true,
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'selected_icon',
      [
        'label' => esc_html__( 'Icon', 'elementor' ),
        'type' => Controls_Manager::ICONS,
        'fa4compatibility' => 'icon',
        'skin' => 'inline',
        'label_block' => false,
        'condition' => $args['section_condition'],
        'icon_exclude_inline_options' => $args['icon_exclude_inline_options'],
      ]
    );

    $this->add_control(
      'icon_align',
      [
        'label' => esc_html__( 'Icon Position', 'elementor' ),
        'type' => Controls_Manager::SELECT,
        'default' => 'left',
        'options' => [
          'left' => esc_html__( 'Before', 'elementor' ),
          'right' => esc_html__( 'After', 'elementor' ),
        ],
        'condition' => array_merge( $args['section_condition'], [ 'selected_icon[value]!' => '' ] ),
      ]
    );

    $this->add_control(
      'icon_indent',
      [
        'label' => esc_html__( 'Icon Spacing', 'elementor' ),
        'type' => Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'max' => 50,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'view',
      [
        'label' => esc_html__( 'View', 'elementor' ),
        'type' => Controls_Manager::HIDDEN,
        'default' => 'traditional',
        'condition' => $args['section_condition'],
      ]
    );

    $this->add_control(
      'button_css_id',
      [
        'label' => esc_html__( 'Button ID', 'elementor' ),
        'type' => Controls_Manager::TEXT,
        'dynamic' => [
          'active' => true,
        ],
        'default' => '',
        'title' => esc_html__( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor' ),
        /* translators: %1$s Code open tag, %2$s: Code close tag. */
        'description' => esc_html__( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor' ),
        'separator' => 'before',
        'condition' => $args['section_condition'],
      ]
    );
  }

}
