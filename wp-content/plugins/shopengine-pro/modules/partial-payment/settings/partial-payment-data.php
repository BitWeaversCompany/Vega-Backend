<?php

namespace ShopEngine_Pro\Modules\Partial_Payment\Settings;

use ShopEngine\Core\Register\Module_List;
use ShopEngine_Pro\Traits\Singleton;
use WC_Product_Variation;

defined( 'ABSPATH' ) || exit;

class Partial_Payment_Data {
	use Singleton;

	public $product_id;
	public $product;
	public $product_price;
	public $currency;
	public $exist_partial_payment_product_in_cart = false;
	public $subtotal_first_payment = 0;
	public $subtotal_second_payment = 0;
	public $subtotal_installments = [];

	public $settings = [];


	public function __construct() {

		$settings       = Module_List::instance()->get_settings( 'partial-payment' );
		$this->settings = [
			"partial_payment_amount_type" => $settings['partial_payment_amount_type']['value'] ?? "percent_amount",
			"partial_payment_amount"      => $settings['partial_payment_amount']['value'] ?? 10,
			"avoid_payment_methods"       => $settings['avoid_payment_methods']['value'] ?? [ "cod" ],
			"partial_payment_label"       => !empty($settings['partial_payment_label']['value']) ? shopengine_pro_translator('partial-payment__partial_payment_label', $settings['partial_payment_label']['value']) : esc_html__( "Select Options",'shopengine-pro' ),
			"partial_payment_single_product_label"       => !empty($settings['partial_payment_single_product_label']['value']) ? shopengine_pro_translator('partial-payment__partial_payment_single_product_label', $settings['partial_payment_single_product_label']['value']) : esc_html__( "Partial Payment",'shopengine-pro' ),
			"full_payment_label"          => !empty($settings['full_payment_label']['value']) ? shopengine_pro_translator('partial-payment__full_payment_label', $settings['full_payment_label']['value']) : esc_html__( "Full Payment", 'shopengine-pro' ),
			"first_installment_label"     => !empty($settings['first_installment_label']['value']) ? shopengine_pro_translator('partial-payment__first_installment_label', $settings['first_installment_label']['value']) : esc_html__( "First Installment", 'shopengine-pro' ),
			"second_installment_label"    => !empty($settings['second_installment_label']['value']) ? shopengine_pro_translator('partial-payment__second_installment_label', $settings['second_installment_label']['value']) : esc_html__( "Second Installment", 'shopengine-pro' ),
			"to_pay_label"                => !empty($settings['to_pay_label']['value']) ? shopengine_pro_translator('partial-payment__to_pay_label', $settings['to_pay_label']['value']) : esc_html__( "To Pay", 'shopengine-pro' ),
			"day_after_installment_reminder"    => $settings['partial_payment_reminder_email']['value'] ??  "5" ,
			"disable_coupon_from_pp"      => !empty($settings['disable_coupon_from_pp']['value']) && $settings['disable_coupon_from_pp']['value'] === "yes" ? true : false,
		];

		// making dynamic installment label (if comma seperated)
		foreach (preg_split('/,\s*/', rtrim(trim($this->settings['second_installment_label']) , ','),-1, PREG_SPLIT_NO_EMPTY ) as $key => $value) {

			$this->settings[\ShopEngine_Pro\Util\Helper::$installment_words[$key+1].'_installment_label'] = $value;
		}
	}

	/**
	 * get product partial payment status
	 * @return mixed
	 */
	public function get_status_value() {
		return get_post_meta( $this->product_id, 'shopengine_product_pp_status', true );
	}

	/**
	 * calculate single product partial payment amount
	 * @return float|int|mixed
	 */
	public function get_partial_amount() {

		$amount_type    = $this->get_amount_type_value();

		$amount         = $this->get_partial_amount_value();
		$amounts = preg_split('/,/', $amount);
		$deposit_amount = [];

		foreach ($amounts as $key => $amount) {
			if ($amount_type === 'fixed_amount') {
				$deposit_amount[] = $amount;
			}
	
			if ($amount_type === 'percent_amount') {
				$calculated_amount = $this->product_price * ($amount / 100);
				$deposit_amount[] = $calculated_amount;
			}
		}

		$remaining_amount = $this->product_price - array_sum($deposit_amount); // left ammount is our remaining deposit
		if($remaining_amount > 0){

			$deposit_amount[] = $remaining_amount;
		}

		

		return $deposit_amount;
	}

	/**
	 * calculate single product partial payment amount
	 * @return array
	 */
	public function get_multiple_partial_amount() {
		
		$deposit_amount = []; // to store deposit array
		$amount_type = $this->get_amount_type_value();
		$amount = $this->get_partial_amount_value();
		
		$amounts = preg_split('/,/', $amount); // if user inserted amount as comma separted value
		
		foreach ($amounts as $key => $amount) {
			if ($amount_type === 'fixed_amount') {
				$deposit_amount[] = $amount;
			}
	
			if ($amount_type === 'percent_amount') {
				$calculated_amount = $this->product_price * ($amount / 100);
				$deposit_amount[] = $calculated_amount;
			}
		}

	
		// Calculate the remaining amount if user didn't set the value perfectly 
		$remaining_amount = $this->product_price - array_sum($deposit_amount); // left ammount is our remaining deposit
		if($remaining_amount > 0){

			$deposit_amount[] = $remaining_amount;
		}


		return $deposit_amount;
	}
	

	/**
	 * get product partial payment amount type : percent_amount/fixed_amount
	 * @return mixed
	 */
	public function get_amount_type_value() {
		$savedValue = get_post_meta( $this->product_id, 'shopengine_product_pp_amount_type', true );

		if ( ! $savedValue ) {
			$savedValue = $this->settings['partial_payment_amount_type'];
		}

		return $savedValue;
	}

	/**
	 * get product partial payment amount
	 * @return mixed
	 */
	public function get_partial_amount_value() {
		
		
		$savedValue = get_post_meta( $this->product_id, 'shopengine_product_pp_amount', true );
		if ( ! $savedValue ) {
			
			if($this->get_amount_type_value()  == 'fixed_amount'){
				$savedValue = (float)wc_get_product($this->product_id)->get_price();
			}else{
				$savedValue = $this->settings['partial_payment_amount'];
			}

		}

		return $savedValue;
	}


	/**
	 * set partial payment subtotal( first and second installment ) & set status of pp product exist in cart.
	 */

	public function set_partial_subtotal() {
		$this->exist_partial_payment_product_in_cart = false ;
		$this->subtotal_first_payment = 0 ;
		$this->subtotal_second_payment = 0 ;
		$firstPayment = 0;
		$secondPayment = 0;
		
		
		if ( !WC()->cart ) {
			return;
		}

		/**
		 * Loop through cart items to seperately calculate partial item and non partial item
		 */
		foreach ( WC()->cart->get_cart() as $key => $cart_item ) {

			$product_id =  $cart_item['product_id'] ;

			if(! $this->check_partial_payment_status( $product_id )){

				
				
				unset(WC()->cart->get_cart()[$key]['cart_partial_payment_status']) ;

			}else{

				
				if ( isset( $cart_item['cart_partial_payment_status'] ) ) {

					$this->exist_partial_payment_product_in_cart = true;

					$variant_id = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ? $cart_item['variation_id'] : null;


					if($variant_id) {
						$this->set_variant_product( $variant_id );
					}else{
						$this->set_product( $product_id );
					}
				
				//current cart item has partial product then calculate it's installment
					$partials_payments = $this->get_multiple_partial_amount(); // get_multiple_partial_amount installment array

					foreach ($partials_payments as $n_key => $payment) {
					
						if($n_key == 0){

							$firstPayment += $payment;
						}
						if($n_key == 1){
							$secondPayment = $payment;
						}
						// Product ID wise installment setup  
						$this->subtotal_installments[$product_id][$n_key] = $payment * $cart_item['quantity'];
					}

					$this->subtotal_first_payment += $firstPayment;
		
					$this->subtotal_second_payment += $secondPayment;

				} else 
				{
					$this->subtotal_first_payment += (float) $cart_item['line_total'];
				}
			}
		}
		// Return the installment array
		return $this->subtotal_installments;
	}

	/**
	 * Get calculated installment array list
	 */
	function get_installment(){
		
		$items = []; // for storing installments payment
		$installments = $this->set_partial_subtotal(); //receving product wise installments 
		

			//Product ID wise installment calculating  
			foreach ($installments as $Pkey => $value) {
				foreach ($value as $key => $price) {
					if(isset($items[$key])){
						$items[$key] += $price;
					}else{
						$items[$key] = $price;

					}
				}
			} 
		
		// total installments are sum of partial product and left amount is non partial and other costs
		$leftamout =  WC()->cart->get_total( 'f' )  - array_sum($items) ; 

		if(isset($items[0])){
			$items[0] += $leftamout; // adding the non partial ammount and other fees
		}

		return $items;
		
	}



	public function check_partial_payment_status( $product_id ){

		return get_post_meta( $product_id,'shopengine_product_pp_status', true) == 'yes';
	}


	/**
	 * set product
	 *
	 * @param $product_id
	 */
	public function set_product( $product_id ) {
		$this->product_id = $product_id;
		$this->product = wc_get_product( $this->product_id );
		// phpcs:disable WordPress.Security.NonceVerification
		if ( $this->product->is_type( 'variable' ) && isset( $_POST['variation_id'] ) ) {
			if ( sanitize_text_field(wp_unslash($_POST['variation_id'])) ) {
				$this->product = new WC_Product_Variation(sanitize_text_field(wp_unslash($_POST['variation_id'])));
			}
		}
		// phpcs:enable
		$this->product_price = $this->product->get_price();

		if ( $this->product->is_type( 'grouped' )){

			$grouped_product_ids = get_post_meta($product_id, '_children', true) ? get_post_meta($product_id, '_children', true) : [];
			$grouped_products_price = 0;

			foreach($grouped_product_ids as $grouped_product_id){

				$grouped_product =  wc_get_product( $grouped_product_id );
				$grouped_products_price += $grouped_product->get_price();
			}

			$this->product_price = $grouped_products_price;
		}
	}


	/**
	 * set product
	 *
	 * @param $variation_id
	 */
	public function set_variant_product( $variation_id ) {
	  $this->product = new WC_Product_Variation( $variation_id );
	  $this->product_id = $this->product->get_parent_id();
	  $this->product_price = $this->product->get_price();
	}

}