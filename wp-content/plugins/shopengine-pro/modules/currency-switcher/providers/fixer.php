<?php

namespace ShopEngine_Pro\Modules\Currency_Switcher\Providers;

defined('ABSPATH') || exit;

use ShopEngine_Pro\Modules\Currency_Switcher\Currency_Providers;

class Fixer extends Currency_Providers {

	public function get_name() {
		return 'fixer';
	}

	public function get_currencies($settings) {
        
        $request = wp_remote_get('http://data.fixer.io/api/latest?access_key=' . $settings['currency-switcher']['settings']['fixer_api_credential']['value']);
		$curr = json_decode($request['body'],true);

        if (is_wp_error($request)) {
            return [
                'status' => 'failed',
                'message' => $curr->get_error_message()
            ];
        }

        if (isset($curr['success']) && $curr['success'] === false) {
            return (isset($curr['error']) && isset($curr['error']['info']))
            ? ['status' => 'failed', 'message' => $curr['error']['info']]
            : ['status' => 'failed', 'message' => 'Error information not available'];
        }

        $default_currency = $settings['currency-switcher']['settings']['default_currency']['value'];

        $rates = isset($curr['rates']) ? $curr['rates'] : [];

        if (!isset($rates[$default_currency])) {
            return [
                'status' => 'failed',
                'message' => esc_html__('Please Select and Save Default Currency','shopengine-pro')
            ];
        }

        $base_currency = $rates[$default_currency];
        $currency = [$default_currency => 1.0];

        foreach ($rates as $key => $value) {
            if ($key !== $default_currency) {
                $currency[$key] = $value / $base_currency;
            }
        }

        return $currency;
    }
}