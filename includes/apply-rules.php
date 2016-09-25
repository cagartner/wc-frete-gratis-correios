<?php
/**
*
*/
class WC_Correios_Frete_Gratis_Apply
{

  function __construct()
  {

    // apply free shipping
    add_filter( 'woocommerce_correios_correios-pac_rate', array( $this, 'pac_gratis' ), 100, 1 );
    add_filter( 'woocommerce_correios_sedex_rate', array( $this, 'sedex_gratis' ), 10, 1 );

  }

  public function pac_gratis( $rate ) {
    return $this->shipping_cost( $rate, 'pac' );
  }

  public function sedex_gratis( $rate ) {
    return $this->shipping_cost( $rate, 'sedex' );
  }


  public function shipping_cost( $rate, $method ) {

    $method_info = $this->method_info( $method );

    $minimum = get_option( 'wc_correios_frete_gratis_' . $method_info['id'] . '_minimum', '' );

    if ( '' !== $this->get_method_postcodes( $method_info['id'] ) && ! $this->is_allowed_postcode( $method_info['id'] ) ) {

      return $rate;

    }

    if ( '' === $minimum ) {
      return $rate;
    }

    if ( isset( WC()->cart->subtotal ) && WC()->cart->subtotal >= $minimum ) {

      $rate['label'] = str_replace( $method_info['name'], $method_info['name'] . ' Grátis', $rate['label'] );
      $rate['cost'] = 0;

    }

    return $rate;

  }


  public function method_info( $method = 'pac' ) {

    if ( 'pac' === $method ) {
      $method = array(
        'name'  => 'PAC',
        'id'    => 'pac'
      );
    } elseif ( 'sedex' === $method ) {
      $method = array(
        'name'  => 'PAC',
        'id'    => 'pac'
      );
    } else {
      $method = array(
        'name'  => $method,
        'id'    => $method
      );
    }

    return $method;

  }


  /**
   * Get postcode zones
   * Based on Elias Junior function
  */
  public function postcode_zones( $method = 'pac' ) {

    $allowed_postcodes = $this->get_method_postcodes( $method );

    // Break each line into array
    $rules = preg_split( '/\r\n|[\r\n]/', $allowed_postcodes );
    // Empty range set
    $ranges = array();
    // Loop into each line
    foreach ( $rules as $rule ) {
      // Check if it's a empty line
      if ( empty( $rule ) ) {
        continue;
      }
      // Explode price
      $rule = explode( ':', $rule );
      // Store the $rule size
      $rule_size = count( $rule );
      // Store the price exploded before
      $price = $rule_size > 1 ? (float) $rule[1] : 0;
      // Loop into each postcode range
      foreach ( explode( '|', $rule[0] ) as $range ) {
        // Check if it's range
        $rg = explode( '-', $range );
        if ( count( $rg ) > 1 ) {
          // In case it's a range
          $ranges[] = array(
            'start' => trim( $rg[0] ),
            'end'   => trim( $rg[1] ),
            'multi' => true,
            'price' => $price,
          );
        } else {
          // if isn't range
          $ranges[] = array(
            'start' => trim( $rg[0] ),
            'multi' => false,
            'price' => $price,
          );
        }
      }
    }

    return $ranges;

  }


  public function get_method_postcodes( $method = 'pac' ) {
    return get_option( 'wc_correios_frete_gratis_' . $method . '_allowed_postcodes', '' );
  }

  /**
   * Check if user postcode is allowed
  */
  public function is_allowed_postcode( $method, $postcode = false ) {

    if ( ! $postcode ) {
      $postcode = WC()->customer->get_postcode();
    }

    // Sanitize postcode
    $postcode = wc_normalize_postcode( wc_clean( $postcode ) );


    // Get postcode rules
    $rules = $this->postcode_zones( $method );

    // Loop into each postcode rule
    foreach ( $rules as $rule ) {

      // If it's a single postcode rule
      if ( false == $rule['multi'] && $postcode == $rule['start'] ) {
        return true;
      }

      // If it's a multiple postcode rule
      if ( true == $rule['multi'] && $postcode >= $rule['start'] && $postcode <= $rule['end'] ) {
        return true;
      }

    }

    return false;

  }

}

new WC_Correios_Frete_Gratis_Apply();
