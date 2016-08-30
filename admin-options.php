<?php
/**
*
*/
class WC_Correios_Frete_Gratis_Admin_Options
{

  function __construct()
  {

    add_filter( 'woocommerce_get_sections_shipping', array( $this, 'section_wc_correios_frete_gratis' ), 10, 1 );

    add_action( 'woocommerce_settings_shipping', array( $this, 'output' ) );

    add_action( 'woocommerce_settings_save_shipping', array( $this, 'save' ) );

  }


  /**
   * Add custom section to WooCommerce Shipping Dashboard
   */
  public function section_wc_correios_frete_gratis( $sections ) {

    $sections['correios-frete-gratis'] = 'Frete Grátis Correios';

    return $sections;

  }

  /**
   * Create Section Options
  */
  private function get_settings() {

    $settings =  array(
      array( 'title' => 'Opções de Frete Grátis para PAC', 'type' => 'title', 'id' => 'wc_correios_frete_gratis_pac' ),

      array(
        'title'    => 'Mínimo da compra',
        'desc'     => 'Informe o valor mínimo da compra para que PAC fique gratuito.',
        'id'       => 'wc_correios_frete_gratis_pac_minimum',
        'type'     => 'number',
        'default'  => '',
        'desc_tip' => true,
      ),

      array(
        'title'         => 'CEPs permitidos (opcional)',
        'desc'          => 'Utilize. Pipe (|) para separar CEPS e traços (-) para indicar faixas. Ex.: 97650000 | 97650000-97670000',
        'placeholder'   => 'Insira CEPS ou faixas de CEP autorizados a ganhar frete grátis.',
        'id'            => 'wc_correios_frete_gratis_pac_allowed_postcodes',
        'default'       => '',
        'type'          => 'textarea',
        'css'           => 'width: 450px; height: 105px;',
        'desc_tip'      => true,
      ),

      array( 'type' => 'sectionend', 'id' => 'wc_correios_frete_gratis_pac' ),


      array( 'title' => 'Opções de Frete Grátis para SEDEX', 'type' => 'title', 'id' => 'wc_correios_frete_gratis_sedex' ),

      array(
        'title'    => 'Mínimo da compra',
        'desc'     => 'Informe o valor mínimo da compra para que SEDEX fique gratuito.',
        'id'       => 'wc_correios_frete_gratis_sedex_minimum',
        'type'     => 'number',
        'default'  => '',
        'desc_tip' => true,
      ),

      array(
        'title'         => 'CEPs permitidos (opcional)',
        'desc'          => 'Utilize. Pipe (|) para separar CEPS e traços (-) para indicar faixas. Ex.: 97650000 | 97650000-97670000',
        'placeholder'   => 'Insira CEPS ou faixas de CEP autorizados a ganhar frete grátis.',
        'id'            => 'wc_correios_frete_gratis_sedex_allowed_postcodes',
        'default'       => '',
        'type'          => 'textarea',
        'css'           => 'width: 450px; height: 105px;',
        'desc_tip'      => true,
      ),

      array( 'type' => 'sectionend', 'id' => 'wc_correios_frete_gratis_pac' ),

    );

    return $settings;

  }

  /**
   * Check section
  */
  public function is_section() {

    global $current_section;

    if ( 'correios-frete-gratis' === $current_section ) {

      return true;

    }

    return false;

  }

  /**
   * Output the section fields
   */
  public function output( $settings ) {

      global $current_section;

      if ( $this->is_section() ) {

        $settings = $this->get_settings();

        WC_Admin_Settings::output_fields( $settings );

      }

  }

  /**
   * Save options
   */
  public function save() {

    if ( $this->is_section() ) {
      WC_Admin_Settings::save_fields( $this->get_settings() );
    }

    // Increments the transient version to invalidate cache
    WC_Cache_Helper::get_transient_version( 'shipping', true );

  }

}

new WC_Correios_Frete_Gratis_Admin_Options();
