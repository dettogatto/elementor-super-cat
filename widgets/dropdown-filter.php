<?php
namespace ElementorSuperCat\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Post Filter
*
* Elementor widget for Post Filter.
*/
class Dropdown_Filter extends \Elementor\Widget_Base {

  public function __construct($data = [], $args = null) {
    parent::__construct($data, $args);

    wp_register_script( 'dropdown-filter-js', plugins_url( '/assets/js/dropdown-filter.js', __DIR__ ), [ 'jquery' ], null, true );
  }

  /**
  * Retrieve the widget name.
  *
  * @access public
  *
  * @return string Widget name.
  */
  public function get_name() {
    return 'dropdown-filter';
  }

  /**
  * Retrieve the widget title.
  *
  * @access public
  *
  * @return string Widget title.
  */
  public function get_title() {
    return __( 'Post Dropdown Filter', 'elementor-super-cat' );
  }

  /**
  * Retrieve the widget icon.
  *
  * @access public
  *
  * @return string Widget icon.
  */
  public function get_icon() {
    return 'fa fa-caret-square-o-down';
  }

  /**
  * Retrieve the list of categories the widget belongs to.
  *
  * Used to determine where to display the widget in the editor.
  *
  * Note that currently Elementor supports only one category.
  * When multiple categories passed, Elementor uses the first one.
  *
  * @access public
  *
  * @return array Widget categories.
  */
  public function get_categories() {
    return [ 'super-cat' ];
  }

  /**
  * Retrieve the list of scripts the widget depended on.
  *
  * Used to set scripts dependencies required to run the widget.
  *
  * @access public
  *
  * @return array Widget scripts dependencies.
  */
  public function get_script_depends() {
    return [ 'dropdown-filter-js' ];
  }

  /**
  * Register the widget controls.
  *
  * Adds different input fields to allow the user to change and customize the widget settings.
  *
  * @access protected
  */
  protected function _register_controls() {

    $this->start_controls_section(
      'section_content',
      [
        'label' => __( 'Content', 'elementor-super-cat' ),
      ]
    );

    $this->add_control(
      'taxonomy',
      [
        'label' => __( 'Name of taxonomy to filter', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SELECT2,
        'label_block' => true,
        'options' => $this->get_taxonomies(),
        // 'default' => $this->get_taxonomies()[0]
      ]
    );

    $this->add_control(
      'post_id',
      [
        'label' => __( 'CSS ID of the post widget', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::TEXT,
      ]
    );

    $this->add_control(
      'order_by',
      [
        'label' => __( 'Order By', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'name',
        'options' => [
          'name'  => __( 'Name', 'elementor-super-cat' ),
          'slug' => __( 'Slug', 'elementor-super-cat' ),
        ],
      ]
    );

    $this->add_control(
      'select_text',
      [
        'label' => __( '<b>Show All</b> text', 'elementor-super-cat' ),
        'type' => \Elementor\Controls_Manager::TEXT,
        'placeholder' => __( 'Enter the default placeholder text', 'elementor-super-cat' ),
        'default' => "all"
      ]
    );
    $this->end_controls_section();



  }

  protected function get_taxonomies() {
    $taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

    $options = [ '' => '' ];

    foreach ( $taxonomies as $taxonomy ) {
      $options[ $taxonomy->name ] = $taxonomy->label;
    }

    return $options;
  }


  /**
  * Render the widget output on the frontend.
  *
  * Written in PHP and used to generate the final HTML.
  *
  * @access protected
  */
  protected function render() {
    wp_enqueue_script('dropdown-filter-js');

    $settings = $this->get_settings_for_display();
    $filtererId = 'filter-' . $settings['taxonomy'] . "-" . $this->get_id();
    $select_text = $settings['select_text'];
    $phpTax = $settings['taxonomy'];
    $jsTax = $settings['taxonomy'];
    if($jsTax == "post_tag"){
      $jsTax = "tag";
    }

    $li = [];
    $terms = get_terms( $phpTax, array( 'hide_empty' => true ) );

    if($settings['order_by'] == "slug"){
      usort($terms, function($a, $b){
        return $a->slug <=> $b->slug;
      });
    }

    $placeholder = '<option
    class="cat-dropdown-filter"
    data-term=""
    data-container="'.$filtererId.'"
    data-posts="'.$settings['post_id'].'">
    '. __($select_text, 'elementor-super-cat').'
    </option>';
    foreach ($terms as $k => $v) {
      $slug = (preg_match("/\p{Hebrew}/u", urldecode($v->slug))?$v->term_id : $v->slug);
      $li[] = '<option
      class="cat-dropdown-filter"
      data-term="'.$jsTax."-".$slug.'"
      data-container="'.$filtererId.'"
      data-posts="'.$settings['post_id'].'">
      '.$v->name.'
      </option>';
    }

    ?>


    <select class="super-cat-dropdown-list cat-filter-for-<?php echo $settings['post_id']; ?>" id="<?php echo $filtererId; ?>">
      <?php echo $placeholder; ?>
      <?php echo(implode($li)); ?>
    </select>

    <?php

  }

  /**
  * Render the widget output in the editor.
  *
  * Written as a Backbone JavaScript template and used to generate the live preview.
  *
  * @access protected
  */
  protected function _content_template() {
    ?>
    <select class="cat-dropdown-list cat-filter-for-{{settings.post_id}}">
      <option class="cat-dropdown-filter elementor-active">{{{settings.select_text}}}</option>
      <option class="cat-dropdown-filter elementor-active">{{{settings.taxonomy}}} 1</option>
      <option class="cat-dropdown-filter">{{{settings.taxonomy}}} 2</option>
      <option class="cat-dropdown-filter">{{{settings.taxonomy}}} 3</option>
    </select>
    <?php
  }
}
