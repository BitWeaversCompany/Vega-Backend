<?php

namespace ShopEngine_Pro\Modules\Currency_Switcher\Providers;

defined('ABSPATH') || exit;

use ShopEngine_Pro\Modules\Currency_Switcher\Currency_Providers;

class Currency_Freaks extends Currency_Providers
{
    public function get_name()
    {
        return 'currency_freaks';
    }

    /**
     * @param $settings
     */
    public function get_currencies($settings) {


        $response      = wp_remote_get('https://api.currencyfreaks.com/latest?apikey=' . str_replace(' ', '', $settings['currency-switcher']['settings']['currency_freaks_api_credential']['value']) . '&format=json');
        $response_body = json_decode(wp_remote_retrieve_body($response), true);
        $response_code = wp_remote_retrieve_response_code($response);

        if (is_wp_error($response)) {
            return [
                'status' => 'failed',
                'message' => $response->get_error_message()
            ];
        }

        if (isset($response_body['success']) && $response_body['success'] === false) {
            return (isset($response_body['error']) && isset($response_body['error']['info']))
            ? ['status' => 'failed', 'message' => $response_body['error']['info']]
            : ['status' => 'failed', 'message' => 'Error information not available'];
        }

        $default_currency = $settings['currency-switcher']['settings']['default_currency']['value'];

        $rates = isset($response_body['rates']) ? $response_body['rates'] : [];

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
        

        if ($response_code === 200) {

            return $currency;
        }
    }
}
