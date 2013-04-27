<?php
class ControllerTotalCoupon extends Controller {

	public function index() {
		$this->language->load('total/coupon');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('coupon', $this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('extension/total'));
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_home'),
			'href'      => UA('common/home'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('text_total'),
			'href'      => UA('extension/total'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => L('heading_title'),
			'href'      => UA('total/coupon'),
      		'separator' => ' :: '
   		);

		$this->data['coupon_status'] = P('coupon_status', C('coupon_status'));
		$this->data['coupon_sort_order'] = P('coupon_sort_order', C('coupon_sort_order'));

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/coupon.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/coupon')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>