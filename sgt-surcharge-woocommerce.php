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

load_plugin_textdomain('sgt-surcharge-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages/' );

if (is_admin())
{
	add_action('admin_menu', 'add_surcharge_menu');
	add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'add_surcharge_plugin_actions', -10);
	add_action('admin_init', 'add_surcharge_register_setting');
}

function add_surcharge_plugin_actions($links)
{
	$add_surcharge_links = array(
		'<a href="options-general.php?page=sgt-surcharge-woocommerce/sgt-surcharge-woocommerce.php">'.__('Settings').'</a>'
	);
	return array_merge($add_surcharge_links, $links);
}

function add_surcharge_register_setting()
{
	register_setting('add_surcharge_options', 'add_surcharge_settings');
}

function add_surcharge_menu()
{
	add_options_page(__('Add Surcharge', 'sgt-surcharge-woocommerce'), __('Add Surcharge', 'sgt-surcharge-woocommerce'), 'manage_options', __FILE__, 'add_surcharge_options_page');
}

function add_surcharge_options_page()
{
	if (!current_user_can('manage_options'))
		wp_die(__('You do not have sufficient permissions to access this page.'));
	$options = get_option('add_surcharge_settings');
	update_option('add_surcharge_settings', $options);
?>
<div class="wrap">
	<div id="icon-themes" class="icon32"><br/></div>
	<h2><?php echo __('Add Surcharge', 'sgt-surcharge-woocommerce') ?></h2>
	<form name="form" method="post" action="options.php" id="frm1">
	<?php
		settings_fields('add_surcharge_options');
		$option = get_option('add_surcharge_options');
	?>
	<table class="form-table">
	<tbody>
		<tr>
			<th scope="row"><label for="enable"><?php _e('Enable surcharges', 'sgt-surcharge-woocommerce'); ?></label></th>
			<td><input name="add_surcharge_settings[enable_surcharges]" id="enable" type="checkbox" value="1"
				<?php if (isset($options['enable_surcharges'])) echo 'checked="checked"';?> /></td>
		</tr>
		<tr>
			<th scope="row"><label for="surcharge"><?php _e('Surcharge percentage', 'sgt-surcharge-woocommerce'); ?></label></th>
			<td><input name="add_surcharge_settings[surcharge]" id="surcharge" type="input" value="<?php
				echo esc_attr($options['surcharge']); ?>" /></td>
		</tr>
	</tbody>
	</table>
	<?php submit_button(); ?>
	</form>
<?php
}

function add_surcharge($cart)
{
	$fee_name = __('Surcharge', 'sgt-surcharge-woocommerce');
	$options = get_option('add_surcharge_settings');
	if (!isset($options['enable_surcharges']))
		return;
	if (!isset($options['surcharge']))
		return;
	$percentage = $options['surcharge'];
	$user_amount =  ($percentage * $cart->cart_contents_total)/100;
	$user_charge = ($user_amount);
	$cart->add_fee($fee_name, $user_charge, false, '');
	$cart->cart_contents_total = $cart->cart_contents_total + $user_amount;
}

add_action('woocommerce_calculate_totals', 'add_surcharge');
?>
