<?php
/**
* Plugin Name: Surcharge for WooCommerce
* Plugin URI: https://github.com/fransklaver/sgt-surcharge-woocommerce
* Description: A plugin adding surcharge management to WooCommerce
* Version: 0.1.0
* Author: Frans Klaver
* Author URI: https://github.com/fransklaver
* License: GPL2
*/

/*  Copyright 2014  Frans Klaver  (email : fransklaver@gmail.com)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License, version 2, as
 *  published by the Free Software Foundation.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
	exit;

function add_surcharge($cart)
{
	$fee_name = 'Surcharge';
	$user_amount = 0.1 * $cart->cart_contents_total;
	$user_charge = ($user_amount);
	$cart->add_fee($fee_name, $user_charge, false, '');
	$cart->cart_contents_total = $cart->cart_contents_total + $user_amount;
}

add_action('woocommerce_calculate_totals', 'add_surcharge');
?>
