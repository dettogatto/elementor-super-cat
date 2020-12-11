<?php

namespace ElementorSuperCat\Widgets;
use \Elementor\Modules\DynamicTags\Module as TagsModule;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
* Elementor video widget.
*
* Elementor widget that displays a video player.
*/
class Autostop_Video extends \Elementor\Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_style( 'autostop-video-css', plugins_url( '/assets/css/autostop-video.css', __DIR__ ));
  }

  /**
  * Get widget name.
  *
  * Retrieve video widget name.
  *
  * @access public
  *
  * @return string Widget name.
  */
  public function get_name() {
    return 'autostop-video';
  }

  /**
  * Get widget title.
  *
  * Retrieve video widget title.
  *
  * @access public
  *
  * @return string Widget title.
  */
  public function get_title() {
    return __( 'Video CTA', 'elementor-super-cat' );
  }

  /**
  * Get widget icon.
  *
  * Retrieve video widget icon.
  *
  * @access public
  *
  * @return string Widget icon.
  */
  public function get_icon() {
    return 'eicon-youtube';
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
      'xs' => __( 'Extra Small', 'elementor' ),
      'sm' => __( 'Small', 'elementor' ),
      'md' => __( 'Medium', 'elementor' ),
      'lg' => __( 'Large', 'elementor' ),
      'xl' => __( 'Extra Large', 'elementor' ),
    ];
  }

  /**
  * Get widget categories.
  *
  * Retrieve the list of categories the video widget belongs to.
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
  * Get widget keywords.
  *
  * Retrieve the list of keywords the widget belongs to.
  *
  * @access public
  *
  * @return array Widget keywords.
  */
  public function get_keywords() {
    return [ 'autostop', 'cta', 'video', 'player', 'embed' ];
  }

  /**
  * Retrieve the list of styles the widget depended on.
  *
  * Used to set styles dependencies required to run the widget.
  *
  * @access public
  *
  * @return array Widget styles dependencies.
  */
  public function get_style_depends() {
    return [ 'autostop-video-css' ];
  }

  /**
  * Register video widget controls.
  *
  * Adds different input fields to allow the user to change and customize the widget settings.
  *
  * @access protected
  */
  protected function _register_controls() {
    $this->start_controls_section(
      'section_video',
      [
        'label' => __( 'Video', 'elementor' ),
      ]
    );


    $this->add_control(
      'insert_url',
      [
        'label' => __( 'External URL', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
      ]
    );

    $this->add_control(
      'hosted_url',
      [
        'label' => __( 'Choose File', 'elementor' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
        'dynamic' => [
          'active' => true,
          'categories' => [
            TagsModule::MEDIA_CATEGORY,
          ],
        ],
        'media_type' => 'video',
        'condition' => [
          'insert_url' => '',
        ],
      ]
    );

    $this->add_control(
      'external_url',
      [
        'label' => __( 'URL', 'elementor' ),
        'type' => \Elementor\Controls_Manager::URL,
        'autocomplete' => false,
        'show_external' => false,
        'label_block' => true,
        'show_label' => false,
        'dynamic' => [
          'active' => true,
          'categories' => [
            TagsModule::POST_META_CATEGORY,
            TagsModule::URL_CATEGORY,
          ],
        ],
        'media_type' => 'video',
        'placeholder' => __( 'Enter your URL', 'elementor' ),
        'condition' => [
          'video_type' => 'hosted',
          'insert_url' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'start',
      [
        'label' => __( 'Start Time', 'elementor' ),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'description' => __( 'Specify a start time (in seconds)', 'elementor' ),
        'condition' => [
          'loop' => '',
        ],
      ]
    );

    $this->add_control(
      'end',
      [
        'label' => __( 'End Time', 'elementor' ),
        'type' => \Elementor\Controls_Manager::NUMBER,
        'description' => __( 'Specify an end time (in seconds)', 'elementor' ),
        'condition' => [
          'loop' => '',
        ],
      ]
    );

    $this->add_control(
      'overlay_block',
      [
        'label' => __( 'Overlay', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'description' => __( 'If ON the video will be blocked by an overlay with a Text and a Button at end time.', 'elementor' ),
      ]
    );

    $this->add_control(
      'auto_open',
      [
        'label' => __( 'Auto-open link', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'description' => __( 'Set if a link should be opened after video stops. It can be an Elementor Popup.', 'elementor' ),
      ]
    );

    $this->add_control(
      'auto_link',
      [
        'label' => __( 'Link', 'elementor' ),
        'type' => \Elementor\Controls_Manager::URL,
        'dynamic' => [
          'active' => true,
        ],
        'placeholder' => __( 'https://your-link.com', 'elementor' ),
        'default' => [
          'url' => '#',
        ],
        'condition' => [
          'auto_open' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'video_options',
      [
        'label' => __( 'Video Options', 'elementor' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'autoplay',
      [
        'label' => __( 'Autoplay', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
      ]
    );

    $this->add_control(
      'mute',
      [
        'label' => __( 'Mute', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
      ]
    );

    $this->add_control(
      'loop',
      [
        'label' => __( 'Loop', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
      ]
    );

    $this->add_control(
      'controls',
      [
        'label' => __( 'Player Controls', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_off' => __( 'Hide', 'elementor' ),
        'label_on' => __( 'Show', 'elementor' ),
        'default' => 'yes',
      ]
    );

    $this->add_control(
      'showinfo',
      [
        'label' => __( 'Video Info', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_off' => __( 'Hide', 'elementor' ),
        'label_on' => __( 'Show', 'elementor' ),
        'default' => 'yes',
      ]
    );



    $this->add_control(
      'download_button',
      [
        'label' => __( 'Download Button', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'label_off' => __( 'Hide', 'elementor' ),
        'label_on' => __( 'Show', 'elementor' ),
      ]
    );

    $this->add_control(
      'poster',
      [
        'label' => __( 'Poster', 'elementor' ),
        'type' => \Elementor\Controls_Manager::MEDIA,
      ]
    );

    $this->add_control(
      'view',
      [
        'label' => __( 'View', 'elementor' ),
        'type' => \Elementor\Controls_Manager::HIDDEN,
        'default' => 'hosted',
      ]
    );

    $this->end_controls_section();

    // Heading Content

    $this->start_controls_section(
      'section_heading',
      [
        'label' => __( 'Title', 'elementor' ),
        'condition' => [
          'overlay_block' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'title_text',
      [
        'label' => __( 'Text', 'elementor' ),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'dynamic' => [
          'active' => true,
        ],
        'placeholder' => __( 'Enter your title', 'elementor' ),
        'default' => __( 'Add Your Heading Text Here', 'elementor' ),
      ]
    );

    $this->end_controls_section();


    // Button Content

    $this->start_controls_section(
      'section_button',
      [
        'label' => __( 'Button', 'elementor' ),
        'condition' => [
          'overlay_block' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'button_type',
      [
        'label' => __( 'Type', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => '',
        'options' => [
          '' => __( 'Default', 'elementor' ),
          'info' => __( 'Info', 'elementor' ),
          'success' => __( 'Success', 'elementor' ),
          'warning' => __( 'Warning', 'elementor' ),
          'danger' => __( 'Danger', 'elementor' ),
        ],
        'prefix_class' => 'elementor-button-',
      ]
    );

    $this->add_control(
      'text',
      [
        'label' => __( 'Text', 'elementor' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'dynamic' => [
          'active' => true,
        ],
        'default' => __( 'Click here', 'elementor' ),
        'placeholder' => __( 'Click here', 'elementor' ),
      ]
    );

    // $this->add_control(
    //     'link',
    //     [
    //         'label' => __( 'Link', 'elementor' ),
    //         'type' => \Elementor\Controls_Manager::TEXT,
    //         'dynamic' => [
    //             'active' => true,
    //         ],
    //         'placeholder' => __( 'https://your-link.com', 'elementor' ),
    //         'default' => __( '#', 'elementor' ),
    //
    //     ]
    // );
    $this->add_control(
      'link',
      [
        'label' => __( 'Link', 'elementor' ),
        'type' => \Elementor\Controls_Manager::URL,
        'dynamic' => [
          'active' => true,
        ],
        'placeholder' => __( 'https://your-link.com', 'elementor' ),
        'default' => [
          'url' => '#',
        ],
      ]
    );

    $this->add_control(
      'new_tab',
      [
        'label' => __( 'Open link in new tab', 'your-plugin' ),
        'label_on' => __( 'new', 'your-plugin' ),
        'label_off' => __( 'same', 'your-plugin' ),
        'type' => \Elementor\Controls_Manager::SWITCHER,
        'return_value' => 'yes',
        'default' => 'no'
      ]
    );

    $this->add_responsive_control(
      'align',
      [
        'label' => __( 'Alignment', 'elementor' ),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'options' => [
          'left'    => [
            'title' => __( 'Left', 'elementor' ),
            'icon' => 'fa fa-align-left',
          ],
          'center' => [
            'title' => __( 'Center', 'elementor' ),
            'icon' => 'fa fa-align-center',
          ],
          'right' => [
            'title' => __( 'Right', 'elementor' ),
            'icon' => 'fa fa-align-right',
          ],
          'justify' => [
            'title' => __( 'Justified', 'elementor' ),
            'icon' => 'fa fa-align-justify',
          ],
        ],
        'prefix_class' => 'elementor%s-align-',
        'default' => '',
      ]
    );

    $this->add_control(
      'size',
      [
        'label' => __( 'Size', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'sm',
        'options' => self::get_button_sizes(),
        'style_transfer' => true,
      ]
    );

    $this->add_control(
      'icon',
      [
        'label' => __( 'Icon', 'elementor' ),
        'type' => \Elementor\Controls_Manager::ICON,
        'label_block' => true,
        'default' => '',
      ]
    );

    $this->add_control(
      'icon_align',
      [
        'label' => __( 'Icon Position', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'left',
        'options' => [
          'left' => __( 'Before', 'elementor' ),
          'right' => __( 'After', 'elementor' ),
        ],
        'condition' => [
          'icon!' => '',
        ],
      ]
    );

    $this->add_control(
      'icon_indent',
      [
        'label' => __( 'Icon Spacing', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'max' => 50,
          ],
        ],
        'condition' => [
          'icon!' => '',
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
          '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'view',
      [
        'label' => __( 'View', 'elementor' ),
        'type' => \Elementor\Controls_Manager::HIDDEN,
        'default' => 'traditional',
      ]
    );

    $this->add_control(
      'button_css_id',
      [
        'label' => __( 'Button ID', 'elementor' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'dynamic' => [
          'active' => true,
        ],
        'default' => '',
        'title' => __( 'Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor-super-cat' ),
        'label_block' => false,
        'description' => __( 'Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor-super-cat' ),
        'separator' => 'before',

      ]
    );

    $this->end_controls_section();

    // $this->start_controls_section(
    // 	'section_image_overlay',
    // 	[
    // 		'label' => __( 'Image Overlay', 'elementor-super-cat' ),
    // 	]
    // );
    //
    // $this->add_control(
    // 	'show_image_overlay',
    // 	[
    // 		'label' => __( 'Image Overlay', 'elementor-super-cat' ),
    // 		'type' => \Elementor\Controls_Manager::SWITCHER,
    // 		'label_off' => __( 'Hide', 'elementor-super-cat' ),
    // 		'label_on' => __( 'Show', 'elementor-super-cat' ),
    // 	]
    // );
    //
    // $this->add_control(
    // 	'image_overlay',
    // 	[
    // 		'label' => __( 'Choose Image', 'elementor-super-cat' ),
    // 		'type' => \Elementor\Controls_Manager::MEDIA,
    // 		'default' => [
    // 			'url' => \Elementor\Utils::get_placeholder_image_src(),
    // 		],
    // 		'dynamic' => [
    // 			'active' => true,
    // 		],
    // 		'condition' => [
    // 			'show_image_overlay' => 'yes',
    // 		],
    // 	]
    // );
    //
    // $this->add_group_control(
    // 	\Elementor\Group_Control_Image_Size::get_type(),
    // 	[
    // 		'name' => 'image_overlay', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_overlay_size` and `image_overlay_custom_dimension`.
    // 		'default' => 'full',
    // 		'separator' => 'none',
    // 		'condition' => [
    // 			'show_image_overlay' => 'yes',
    // 		],
    // 	]
    // );
    //
    // $this->add_control(
    // 	'show_play_icon',
    // 	[
    // 		'label' => __( 'Play Icon', 'elementor-super-cat' ),
    // 		'type' => \Elementor\Controls_Manager::SWITCHER,
    // 		'default' => 'yes',
    // 		'condition' => [
    // 			'show_image_overlay' => 'yes',
    // 			'image_overlay[url]!' => '',
    // 		],
    // 	]
    // );
    //
    // $this->add_control(
    // 	'lightbox',
    // 	[
    // 		'label' => __( 'Lightbox', 'elementor-super-cat' ),
    // 		'type' => \Elementor\Controls_Manager::SWITCHER,
    // 		'frontend_available' => true,
    // 		'label_off' => __( 'Off', 'elementor-super-cat' ),
    // 		'label_on' => __( 'On', 'elementor-super-cat' ),
    // 		'condition' => [
    // 			'show_image_overlay' => 'yes',
    // 			'image_overlay[url]!' => '',
    // 		],
    // 		'separator' => 'before',
    // 	]
    // );
    //
    // $this->end_controls_section();

    // Video Style

    $this->start_controls_section(
      'section_video_style',
      [
        'label' => __( 'Video', 'elementor-super-cat' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
      ]
    );

    $this->add_control(
      'aspect_ratio',
      [
        'label' => __( 'Aspect Ratio', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          '169' => '16:9',
          '219' => '21:9',
          '43' => '4:3',
          '32' => '3:2',
          '11' => '1:1',
        ],
        'default' => '169',
        'prefix_class' => 'elementor-widget-video elementor-aspect-ratio-',
        'frontend_available' => true,
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Css_Filter::get_type(),
      [
        'name' => 'css_filters',
        'selector' => '{{WRAPPER}} .elementor-wrapper',
      ]
    );

    $this->add_control(
      'play_icon_title',
      [
        'label' => __( 'Play Icon', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::HEADING,
        'condition' => [
          'show_image_overlay' => 'yes',
          'show_play_icon' => 'yes',
        ],
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'play_icon_color',
      [
        'label' => __( 'Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} .elementor-custom-embed-play i' => 'color: {{VALUE}}',
        ],
        'condition' => [
          'show_image_overlay' => 'yes',
          'show_play_icon' => 'yes',
        ],
      ]
    );

    $this->add_responsive_control(
      'play_icon_size',
      [
        'label' => __( 'Size', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'min' => 10,
            'max' => 300,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-custom-embed-play i' => 'font-size: {{SIZE}}{{UNIT}}',
        ],
        'condition' => [
          'show_image_overlay' => 'yes',
          'show_play_icon' => 'yes',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'play_icon_text_shadow',
        'selector' => '{{WRAPPER}} .elementor-custom-embed-play i',
        'fields_options' => [
          'text_shadow_type' => [
            'label' => _x( 'Shadow', 'Text Shadow Control', 'elementor-super-cat' ),
          ],
        ],
        'condition' => [
          'show_image_overlay' => 'yes',
          'show_play_icon' => 'yes',
        ],
      ]
    );

    $this->end_controls_section();


    // Overlay Style

    $this->start_controls_section(
      'section_overlay_style',
      [
        'label' => __( 'Overlay', 'elementor' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'overlay_block' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'overlay_color',
      [
        'label' => __( 'Overlay Color', 'elementor' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'scheme' => [
          'type' => \Elementor\Scheme_Color::get_type(),
          'value' => \Elementor\Scheme_Color::COLOR_1,
        ],
        'selectors' => [
          // Stronger selector to avoid section style from overwriting
          '{{WRAPPER}} .super-video-stopper' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->end_controls_section();



    // Heading Style

    $this->start_controls_section(
      'section_title_style',
      [
        'label' => __( 'Title', 'elementor' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'overlay_block' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'margin_bottom_tit',
      [
        'label' => __( 'Bottom Margin', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'range' => [
          'px' => [
            'max' => 100,
          ],
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-heading-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'title_color',
      [
        'label' => __( 'Text Color', 'elementor' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'scheme' => [
          'type' => \Elementor\Scheme_Color::get_type(),
          'value' => \Elementor\Scheme_Color::COLOR_1,
        ],
        'selectors' => [
          // Stronger selector to avoid section style from overwriting
          '{{WRAPPER}} .elementor-heading-title' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography',
        'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
        'selector' => '{{WRAPPER}} .elementor-heading-title',
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Text_Shadow::get_type(),
      [
        'name' => 'text_shadow',
        'selector' => '{{WRAPPER}} .elementor-heading-title',
      ]
    );

    $this->add_control(
      'blend_mode',
      [
        'label' => __( 'Blend Mode', 'elementor' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
          '' => __( 'Normal', 'elementor' ),
          'multiply' => 'Multiply',
          'screen' => 'Screen',
          'overlay' => 'Overlay',
          'darken' => 'Darken',
          'lighten' => 'Lighten',
          'color-dodge' => 'Color Dodge',
          'saturation' => 'Saturation',
          'color' => 'Color',
          'difference' => 'Difference',
          'exclusion' => 'Exclusion',
          'hue' => 'Hue',
          'luminosity' => 'Luminosity',
        ],
        'selectors' => [
          '{{WRAPPER}} .elementor-heading-title' => 'mix-blend-mode: {{VALUE}}',
        ],
        'separator' => 'none',
      ]
    );

    $this->end_controls_section();

    // Lightbox

    $this->start_controls_section(
      'section_lightbox_style',
      [
        'label' => __( 'Lightbox', 'elementor-super-cat' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'show_image_overlay' => 'yes',
          'image_overlay[url]!' => '',
          'lightbox' => 'yes',
        ],
      ]
    );

    $this->add_control(
      'lightbox_color',
      [
        'label' => __( 'Background Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '#elementor-lightbox-{{ID}}' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'lightbox_ui_color',
      [
        'label' => __( 'UI Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button' => 'color: {{VALUE}}',
        ],
      ]
    );

    $this->add_control(
      'lightbox_ui_color_hover',
      [
        'label' => __( 'UI Hover Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '#elementor-lightbox-{{ID}} .dialog-lightbox-close-button:hover' => 'color: {{VALUE}}',
        ],
        'separator' => 'after',
      ]
    );

    $this->add_control(
      'lightbox_video_width',
      [
        'label' => __( 'Content Width', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'default' => [
          'unit' => '%',
        ],
        'range' => [
          '%' => [
            'min' => 50,
          ],
        ],
        'selectors' => [
          '(desktop+)#elementor-lightbox-{{ID}} .elementor-video-container' => 'width: {{SIZE}}{{UNIT}};',
        ],
      ]
    );

    $this->add_control(
      'lightbox_content_position',
      [
        'label' => __( 'Content Position', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'frontend_available' => true,
        'options' => [
          '' => __( 'Center', 'elementor-super-cat' ),
          'top' => __( 'Top', 'elementor-super-cat' ),
        ],
        'selectors' => [
          '#elementor-lightbox-{{ID}} .elementor-video-container' => '{{VALUE}}; transform: translateX(-50%);',
        ],
        'selectors_dictionary' => [
          'top' => 'top: 60px',
        ],
      ]
    );

    $this->add_responsive_control(
      'lightbox_content_animation',
      [
        'label' => __( 'Entrance Animation', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::ANIMATION,
        'frontend_available' => true,
      ]
    );

    $this->end_controls_section();


    // Button Style

    $this->start_controls_section(
      'section_style',
      [
        'label' => __( 'Button', 'elementor-super-cat' ),
        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
        'condition' => [
          'overlay_block' => 'yes',
        ],
      ]
    );


    $this->start_controls_tabs( 'tabs_button_style' );

    $this->start_controls_tab(
      'tab_button_normal',
      [
        'label' => __( 'Normal', 'elementor-super-cat' ),
      ]
    );

    $this->add_control(
      'button_text_color',
      [
        'label' => __( 'Text Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'default' => '',
        'selectors' => [
          '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'color: {{VALUE}};',
        ],
      ]
    );


    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography_btn',
        'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
        'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
      ]
    );

    $this->add_control(
      'background_color',
      [
        'label' => __( 'Background Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'scheme' => [
          'type' => \Elementor\Scheme_Color::get_type(),
          'value' => \Elementor\Scheme_Color::COLOR_4,
        ],
        'selectors' => [
          '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->end_controls_tab();

    $this->start_controls_tab(
      'tab_button_hover',
      [
        'label' => __( 'Hover', 'elementor-super-cat' ),
      ]
    );

    $this->add_control(
      'hover_color',
      [
        'label' => __( 'Text Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Typography::get_type(),
      [
        'name' => 'typography_btn_hover',
        'scheme' => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
        'selector' => '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus',
      ]
    );

    $this->add_control(
      'button_background_hover_color',
      [
        'label' => __( 'Background Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
          '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'button_hover_border_color',
      [
        'label' => __( 'Border Color', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::COLOR,
        'condition' => [
          'border_border!' => '',
        ],
        'selectors' => [
          '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
        ],
      ]
    );

    $this->add_control(
      'hover_animation',
      [
        'label' => __( 'Hover Animation', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::HOVER_ANIMATION,
      ]
    );

    $this->end_controls_tab();

    $this->end_controls_tabs();

    $this->add_group_control(
      \Elementor\Group_Control_Border::get_type(),
      [
        'name' => 'border',
        'selector' => '{{WRAPPER}} .elementor-button',
        'separator' => 'before',
      ]
    );

    $this->add_control(
      'border_radius',
      [
        'label' => __( 'Border Radius', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', '%' ],
        'selectors' => [
          '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
      ]
    );

    $this->add_group_control(
      \Elementor\Group_Control_Box_Shadow::get_type(),
      [
        'name' => 'button_box_shadow',
        'selector' => '{{WRAPPER}} .elementor-button',
      ]
    );

    $this->add_responsive_control(
      'text_padding',
      [
        'label' => __( 'Padding', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => [ 'px', 'em', '%' ],
        'selectors' => [
          '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        'separator' => 'before',
      ]
    );

    $this->end_controls_section();

  }

  /**
  * Render video widget output on the frontend.
  *
  * Written in PHP and used to generate the final HTML.
  *
  * @access protected
  */
  protected function render() {
    wp_enqueue_style('autostop-video-css');

    $settings = $this->get_settings_for_display();


    $video_url = $this->get_hosted_video_url();

    if ( empty( $video_url ) ) {
      return;
    }

    ob_start();

    $this->render_hosted_video();

    $video_html = ob_get_clean();


    if ( empty( $video_html ) ) {
      echo esc_url( $video_url );

      return;
    }

    $this->add_render_attribute( 'video-wrapper', 'class', 'elementor-wrapper' );

    if ( ! $settings['lightbox'] ) {
      $this->add_render_attribute( 'video-wrapper', 'class', 'elementor-fit-aspect-ratio' );
    }

    $this->add_render_attribute( 'video-wrapper', 'class', 'elementor-open-' . ( $settings['lightbox'] ? 'lightbox' : 'inline' ) );


    if ( ! empty( $settings['link']['url'] ) ) {
      $this->add_render_attribute( 'button', 'href', $settings['link']['url'] );
      $this->add_render_attribute( 'button', 'class', 'elementor-button-link' );

      if ( $settings['new_tab'] == "yes" ) {
        $this->add_render_attribute( 'button', 'target', '_blank' );
      }
    }
    $this->add_render_attribute( 'button', 'class', 'elementor-button' );
    $this->add_render_attribute( 'button', 'role', 'button' );
    if ( ! empty( $settings['button_css_id'] ) ) {
      $this->add_render_attribute( 'button', 'id', $settings['button_css_id'] );
    }
    if ( ! empty( $settings['size'] ) ) {
      $this->add_render_attribute( 'button', 'class', 'elementor-size-' . $settings['size'] );
    }
    if ( $settings['hover_animation'] ) {
      $this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
    }

    ?>
    <div <?php echo $this->get_render_attribute_string( 'video-wrapper' ); ?>>

      <?php  if($settings['overlay_block'] == "yes"): ?>

        <div class="super-video-stopper" id="video-stopper-<?php echo($this->get_id()); ?>"
          <?php if(!(\Elementor\Plugin::$instance->preview->is_preview_mode() || \Elementor\Plugin::$instance->editor->is_edit_mode())){echo ('style="display:none;"');} ?> >
            <div>
              <center>
                <div class="elementor-heading-title">
                  <?php echo($settings['title_text']); ?>
                </div>
                <div class="elementor-widget-container">
                  <div class="elementor-button-wrapper">
                    <a <?php echo $this->get_render_attribute_string( 'button' ); ?>>
                      <?php $this->render_text(); ?>
                    </a>
                  </div>
                </div>
              </center>
            </div>
          </div>

        <?php endif; ?>


        <?php
        if ( ! $settings['lightbox'] ) {
          echo $video_html; // XSS ok.
        }

        if ( $this->has_image_overlay() ) {
          $this->add_render_attribute( 'image-overlay', 'class', 'elementor-custom-embed-image-overlay' );

          if ( $settings['lightbox'] ) {
            $lightbox_url = $video_url;

            $lightbox_options = [
              'type' => 'video',
              'videoType' => 'hosted',
              'url' => $lightbox_url,
              'modalOptions' => [
                'id' => 'elementor-lightbox-' . $this->get_id(),
                'entranceAnimation' => $settings['lightbox_content_animation'],
                'entranceAnimation_tablet' => $settings['lightbox_content_animation_tablet'],
                'entranceAnimation_mobile' => $settings['lightbox_content_animation_mobile'],
                'videoAspectRatio' => $settings['aspect_ratio'],
              ],
            ];

            $lightbox_options['videoParams'] = $this->get_hosted_params();

            $this->add_render_attribute( 'image-overlay', [
              'data-elementor-open-lightbox' => 'yes',
              'data-elementor-lightbox' => wp_json_encode( $lightbox_options ),
              ] );

              if ( Plugin::$instance->editor->is_edit_mode() ) {
                $this->add_render_attribute( 'image-overlay', [
                  'class' => 'elementor-clickable',
                  ] );
                }
              } else {
                $this->add_render_attribute( 'image-overlay', 'style', 'background-image: url(' . \Elementor\Group_Control_Image_Size::get_attachment_image_src( $settings['image_overlay']['id'], 'image_overlay', $settings ) . ');' );
              }
              ?>
              <div <?php echo $this->get_render_attribute_string( 'image-overlay' ); ?>>
                <?php if ( $settings['lightbox'] ) : ?>
                  <?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings, 'image_overlay' ); ?>
                <?php endif; ?>



                <?php if ( 'yes' === $settings['show_play_icon'] ) : ?>
                  <div class="elementor-custom-embed-play" role="button">
                    <i class="eicon-play" aria-hidden="true"></i>
                    <span class="elementor-screen-only"><?php echo __( 'Play Video', 'elementor-super-cat' ); ?></span>
                  </div>
                <?php endif; ?>
              </div>
            <?php } ?>
          </div>
          <script>
          var $jq = jQuery.noConflict();
          var video = $jq("#video-<?php echo($this->get_id()); ?>");
          var stopper = $jq("#video-stopper-<?php echo($this->get_id()); ?>");
          var end = parseInt("<?php echo($settings['end']); ?>");
          var interval_<?php echo($this->get_id()); ?> = setInterval(function(){
            if(end && video.get(0).currentTime >= end){
              if(video.get(0).webkitExitFullScreen){video.get(0).webkitExitFullScreen()}
              if(document.webkitExitFullscreen){document.webkitExitFullscreen()}
              if(document.mozCancelFullscreen){document.mozCancelFullscreen()}
              if(document.exitFullscreen){document.exitFullscreen()}
              if (document.exitPictureInPicture){document.exitPictureInPicture()}
              video.get(0).pause();
              <?php
              //window.elementorProFrontend.modules.linkActions.runAction("#elementor-action%3Aaction%3Dpopup%3Aopen%20settings%3DeyJpZCI6IjI2MSIsInRvZ2dsZSI6ZmFsc2V9");
              if($settings['auto_open'] == "yes"){
                if(substr($settings["auto_link"]["url"], 0, 17) == "#elementor-action"){
                  echo('window.elementorProFrontend.modules.linkActions.runAction("'.$settings["auto_link"]["url"].'");');
                }else{
                  echo('window.location = "'.$settings["auto_link"]["url"].'";');
                }
              }
              if($settings['overlay_block'] == "yes"){
                echo('$jq("#video-stopper-' . $this->get_id() . '").show();');
              }

              ?>
              clearInterval(interval_<?php echo($this->get_id()); ?>);
            }
          }, 300);
          </script>
          <?php
        }

        /**
        * Render video widget as plain content.
        *
        * Override the default behavior, by printing the video URL insted of rendering it.
        *
        * @access public
        */
        public function render_plain_content() {
          $settings = $this->get_settings_for_display();

          if ( 'hosted' !== $settings['video_type'] ) {
            $url = $settings[ $settings['video_type'] . '_url' ];
          } else {
            $url = $this->get_hosted_video_url();
          }

          echo esc_url( $url );
        }

        /**
        * Get embed params.
        *
        * Retrieve video widget embed parameters.
        *
        * @access public
        *
        * @return array Video embed parameters.
        */
        public function get_embed_params() {
          $settings = $this->get_settings_for_display();

          $params = [];

          if ( $settings['autoplay'] && ! $this->has_image_overlay() ) {
            $params['autoplay'] = '1';
          }

          $params_dictionary = [];


          foreach ( $params_dictionary as $key => $param_name ) {
            $setting_name = $param_name;

            if ( is_string( $key ) ) {
              $setting_name = $key;
            }

            $setting_value = $settings[ $setting_name ] ? '1' : '0';

            $params[ $param_name ] = $setting_value;
          }

          return $params;
        }

        /**
        * Whether the video widget has an overlay image or not.
        *
        * Used to determine whether an overlay image was set for the video.
        *
        * @access protected
        *
        * @return bool Whether an image overlay was set for the video.
        */
        protected function has_image_overlay() {
          $settings = $this->get_settings_for_display();

          return ! empty( $settings['image_overlay']['url'] ) && 'yes' === $settings['show_image_overlay'];
        }

        /**
        * @access private
        */
        private function get_embed_options() {
          $settings = $this->get_settings_for_display();

          $embed_options = [];

          return $embed_options;
        }

        /**
        * @access private
        */
        private function get_hosted_params() {
          $settings = $this->get_settings_for_display();

          $video_params = [];

          foreach ( [ 'autoplay', 'loop', 'controls' ] as $option_name ) {
            if ( $settings[ $option_name ] ) {
              $video_params[ $option_name ] = '';
            }
          }

          if ( $settings['mute'] ) {
            $video_params['muted'] = 'muted';
          }

          if ( ! $settings['download_button'] ) {
            $video_params['controlsList'] = 'nodownload';
          }

          if ( $settings['poster']['url'] ) {
            $video_params['poster'] = $settings['poster']['url'];
          }

          return $video_params;
        }

        /**
        * @param bool $from_media
        *
        * @return string
        * @access private
        */
        private function get_hosted_video_url() {
          $settings = $this->get_settings_for_display();

          if ( ! empty( $settings['insert_url'] ) ) {
            $video_url = $settings['external_url']['url'];
          } else {
            $video_url = $settings['hosted_url']['url'];
          }

          if ( empty( $video_url ) ) {
            return '';
          }

          if ( $settings['start'] || $settings['end'] ) {
            $video_url .= '#t=';
          }

          if ( $settings['start'] ) {
            $video_url .= $settings['start'];
          }

          if ( $settings['end'] ) {
            $video_url .= ',' . $settings['end'];
          }

          return $video_url;
        }

        /**
        *
        * @access private
        */
        private function render_hosted_video() {
          $video_url = $this->get_hosted_video_url();
          if ( empty( $video_url ) ) {
            return;
          }

          $video_params = $this->get_hosted_params();
          ?>
          <video class="elementor-video" id="video-<?php echo($this->get_id()); ?>" src="<?php echo esc_url( $video_url ); ?>" <?php echo \Elementor\Utils::render_html_attributes( $video_params ); ?> disablePictureInPicture></video>
          <?php
        }

        /**
        * Render button text.
        *
        * Render button widget text.
        *
        * @access protected
        */
        protected function render_text() {
          $settings = $this->get_settings_for_display();

          $this->add_render_attribute( [
            'content-wrapper' => [
              'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
              'class' => [
                'elementor-button-icon',
                'elementor-align-icon-' . $settings['icon_align'],
              ],
            ],
            'text' => [
              'class' => 'elementor-button-text',
            ],
            ] );

            $this->add_inline_editing_attributes( 'text', 'none' );
            ?>
            <span <?php echo $this->get_render_attribute_string( 'content-wrapper' ); ?>>
              <?php if ( ! empty( $settings['icon'] ) ) : ?>
                <span <?php echo $this->get_render_attribute_string( 'icon-align' ); ?>>
                  <i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
                </span>
              <?php endif; ?>
              <span <?php echo $this->get_render_attribute_string( 'text' ); ?>><?php echo $settings['text']; ?></span>
            </span>
            <?php
          }

        }
