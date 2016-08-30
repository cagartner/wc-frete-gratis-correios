<?php
/*
Plugin Name: WC Frete Grátis para Correios
Description: Ofereça Frete Grátis para PAC ou SEDEX no WooCommerce
Plugin URI: http://fernandoacosta.net
Author: Fernanod Acosta
Author URI: http://fernandoacosta.net
Version: 1.0
License: GPL2
*/

/*

    Copyright (C) 2016  Fernando Acosta  contato@fernandoacosta.net

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
* WC Correios Frete Grátis
*/
class WC_Correios_Frete_Gratis
{

  /**
   * Instance of this class.
   *
   * @var object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin public actions.
   */
  function __construct()
  {

    include_once( 'admin-options.php' );
    include_once( 'apply-rules.php' );

  }

  /**
   * Return an instance of this class.
   *
   * @return object A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

}

add_action( 'plugins_loaded', array( 'WC_Correios_Frete_Gratis', 'get_instance' ) );