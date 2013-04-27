<?php
class ControllerPaymentPaypalExpress extends Controller {
	protected function index() {		
		$this->data['button_confirm'] = L('button_confirm');
		$this->render('payment/paypal_express.tpl');
	}
	
	public function init() {
		M("payment/paypalexpresscurl");
		$invNum = $this->session->data['order_id'];
		
		M('checkout/order');		
		$order_info = $this->model_checkout_order->getOrder($invNum);
		
		$paymentAmount = $this->currency->convert($order_info['total'], 'USD' , $order_info['currency_code']);
		$paymentAmount = round($paymentAmount, 2);
		$returnURL = U('payment/paypal_express/confirm', '', 'SSL');
		$cancelURL = U('checkout/cart', '', 'SSL');
		$address = array();
		if ($this->customer->isLogged()) {
			M('account/address');
			$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);			
		} elseif (isset($this->session->data['guest'])) {
			$shipping_address = $this->session->data['guest']['shipping'];
		}
		$address['telephone'] = $this->customer->getTelephone();
		$address['shipping_firstname'] = $shipping_address['firstname'];
		$address['shipping_lastname'] = $shipping_address['lastname'];	
		$address['shipping_address_1'] = $shipping_address['address_1'];
		$address['shipping_address_2'] = $shipping_address['address_2'];
		$address['shipping_city'] = $shipping_address['city'];
		$address['shipping_postcode'] = $shipping_address['postcode'];
		$address['shipping_zone'] = $shipping_address['zone'];
		$address['shipping_zone_id'] = $shipping_address['zone_id'];
		$address['shipping_country'] = $shipping_address['country'];
		$address['shipping_country_id'] = $shipping_address['country_id'];
		
		$resArray = $this->model_payment_paypalexpresscurl->SetExpressCheckout($invNum, $paymentAmount, $returnURL, $cancelURL, $address);
		//$resArray = $this->model_payment_paypalexpresscurl->SetExpressCheckout($invNum, $paymentAmount, $returnURL, $cancelURL);
		$json = array();
		if($this->model_payment_paypalexpresscurl->error()) {
			$this->data['error'] = $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
			$json["error"] = $this->render('payment/paypal_express_error.tpl');
		}
		else {
			$json["redirect"] = $this->model_payment_paypalexpresscurl->getPaypalURL();
		}
		
		$this->response->setOutput(json_encode($json));
	}
	
	public function confirm() {
		M("payment/paypalexpresscurl");
		$resArray = $this->model_payment_paypalexpresscurl->getCheckoutDetails();
		if ($this->model_payment_paypalexpresscurl->error()) {
			//$this->data['error'] = $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
			echo $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
			exit(0);
		}
		else {
			$comment = isset($resArray["PAYMENTREQUEST_0_NOTETEXT"]) ? $resArray["PAYMENTREQUEST_0_NOTETEXT"] : '';
			$invNum = $this->session->data['order_id'];
			if ($comment) {
				$this->db->runSql("UPDATE `@@order` SET comment = '".ES($comment)."' WHERE order_id = '$invNum'");
			}
			M('checkout/order');		
			$order_info = $this->model_checkout_order->getOrder($invNum);
			
			$paymentAmount = $this->currency->convert($order_info['total'], 'USD' , $order_info['currency_code']);
			$paymentAmount = round($paymentAmount, 2);
			$resArray = $this->model_payment_paypalexpresscurl->ConfirmPayment($invNum, $paymentAmount);
			if ($this->model_payment_paypalexpresscurl->error()) {
				echo $this->model_payment_paypalexpresscurl->getErrorMsg($resArray);
				exit(0);
			}
			else {
				if($resArray["PAYMENTINFO_0_AMT"] == $paymentAmount) {
					M('checkout/order');
					$this->model_checkout_order->confirm($invNum, C("paypal_express_order_status_id"));
					$this->redirect(U('checkout/success'));
				}
				else {
					echo "Pay Error!";
					exit(0);
				}
			}
		}
	}
}
?>