<?php
class ControllerTotalVoucher extends Controller {

	public function index() {
		$this->language->load('total/voucher');

		$this->document->setTitle(L('heading_title'));

		M('setting/setting');

		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('voucher', $this->request->post);

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
			'href'      => UA('total/voucher'),
      		'separator' => ' :: '
   		);


		if (isset($this->request->post['voucher_status'])) {
			$this->data['voucher_status'] = $this->request->post['voucher_status'];
		} else {
			$this->data['voucher_status'] = C('voucher_status');
		}

		if (isset($this->request->post['voucher_sort_order'])) {
			$this->data['voucher_sort_order'] = $this->request->post['voucher_sort_order'];
		} else {
			$this->data['voucher_sort_order'] = C('voucher_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('total/voucher.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/voucher')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>