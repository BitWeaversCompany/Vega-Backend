<?php

namespace ShopEngine_Pro\Util;

defined('ABSPATH') || exit;

class Helper {

	public static $installment_words = [
		'first','second','third','fourth','fifth','sixth','seventh',
		'eighth', 'ninth', 'tenth', 'eleventh', 'twelfth', 'thirteenth', 'fourteenth', 'fifteenth', 'sixteenth', 'seventeenth',
		'eighteenth', 'nineteenth', 'twentieth', 'twenty-first', 'twenty-second', 'twenty-third', 'twenty-fourth', 'twenty-fifth', 'twenty-sixth', 'twenty-seventh',
		'twenty-eighth', 'twenty-ninth', 'thirtieth', 'thirty-first', 'thirty-second', 'thirty-third', 'thirty-fourth', 'thirty-fifth', 'thirty-sixth', 'thirty-seventh',
		'thirty-eighth', 'thirty-ninth', 'fortieth', 'forty-first', 'forty-second', 'forty-third', 'forty-fourth', 'forty-fifth', 'forty-sixth', 'forty-seventh',
		'forty-eighth', 'forty-ninth', 'fiftieth', 'fifty-first', 'fifty-second', 'fifty-third', 'fifty-fourth', 'fifty-fifth', 'fifty-sixth', 'fifty-seventh',
		'fifty-eighth', 'fifty-ninth', 'sixtieth', 'sixty-first', 'sixty-second', 'sixty-third', 'sixty-fourth', 'sixty-fifth', 'sixty-sixth', 'sixty-seventh',
		'sixty-eighth', 'sixty-ninth', 'seventieth', 'seventy-first', 'seventy-second', 'seventy-third', 'seventy-fourth', 'seventy-fifth', 'seventy-sixth', 'seventy-seventh',
		'seventy-eighth', 'seventy-ninth', 'eightieth', 'eighty-first', 'eighty-second', 'eighty-third', 'eighty-fourth', 'eighty-fifth', 'eighty-sixth', 'eighty-seventh',
		'eighty-eighth', 'eighty-ninth', 'ninetieth', 'ninety-first', 'ninety-second', 'ninety-third', 'ninety-fourth', 'ninety-fifth', 'ninety-sixth', 'ninety-seventh',
		'ninety-eighth', 'ninety-ninth', 'one-hundredth'
	];
	
	public static function get_woo_tax_attribute($taxonomy, $trim = true) {

		global $wpdb;

		$attr = $taxonomy;

		if($trim === true) {

			$attr = substr($taxonomy, 3);
		}

		$attr = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s", $attr));

		return $attr;
	}

	public static function get_dummy() {

		return WC()->plugin_url() . '/assets/images/placeholder.png';
	}

		public static function get_kses_array()
	{
		return [
			'a'                             => [
				'class'            => [],
				'href'             => [],
				'rel'              => [],
				'title'            => [],
				'target'           => [],
				'data-quantity'    => [],
				'data-product_id'  => [],
				'data-product_sku' => [],
				'data-pid'         => [],
				'aria-label'       => [],
			],
			'abbr'                          => [
				'title' => [],
			],
			'b'                             => [],
			'blockquote'                    => [
				'cite' => [],
			],
			'cite'                          => [
				'title' => [],
			],
			'code'                          => [],
			'del'                           => [
				'datetime' => [],
				'title'    => [],
			],
			'dd'                            => [],
			'div'                           => [
				'class' 				=> [],
				'title' 				=> [],
				'style' 				=> [],
				'data-product-id' 		=> [],
				'data-attribute_name'	=> [],
				'id'					=> []
			],
			'dl'                            => [],
			'dt'                            => [],
			'em'                            => [],
			'h1'                            => [
				'class' => [],
			],
			'h2'                            => [
				'class' => [],
			],
			'h3'                            => [
				'class' => [],
			],
			'h4'                            => [
				'class' => [],
			],
			'h5'                            => [
				'class' => [],
			],
			'h6'                            => [
				'class' => [],
			],
			'i'                             => [
				'class' => [],
			],
			'img'                           => [
				'alt'      => [],
				'class'    => [],
				'height'   => [],
				'src'      => [],
				'width'    => [],
				'decoding' => [],
				'loading'  => [],
				'srcset'   => [],
				'sizes'    => []
			],
			'li'                            => [
				'class' => [],
			],
			'ol'                            => [
				'class' => [],
			],
			'p'                             => [
				'class' => [],
			],
			'q'                             => [
				'cite'  => [],
				'title' => [],
			],
			'span'                          => [
				'class' => [],
				'title' => [],
				'style' => [],
			],
			'iframe'                        => [
				'width'       => [],
				'height'      => [],
				'scrolling'   => [],
				'frameborder' => [],
				'allow'       => [],
				'src'         => [],
			],
			'strike'                        => [],
			'br'                            => [],
			'strong'                        => [
				'class' => [],
				'id'	=> [],
			],
			'data-wow-duration'             => [],
			'data-wow-delay'                => [],
			'data-wallpaper-options'        => [],
			'data-stellar-background-ratio' => [],
			'ul'                            => [
				'class' => [],
			],
			'button' => [
				'class' => [],
				'title' => [],
				'data-share-url' => [],
				'data-message' => []
			],
		];
	}

	/**
	 * It will retun ordinal numbers like this for input 1 = 1st, for input 2 = 2nd ...
	 * @param int pass integer parameter 
	 * @return  string
	 */
	public static function ordinal(int $number) {
		 
		$ends = array('th','st','nd','rd','th','th','th','th','th','th');
		if ((($number % 100) >= 11) && (($number%100) <= 13))
			return $number. 'th';
		else
			return $number. $ends[$number % 10];
	}

}
