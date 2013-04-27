<?php 
class ControllerTotalKlarnaFee extends Controller { 
	 
	public function index() { 
		$this->language->load('total/klarna_fee');

		$this->document->setTitle(L('heading_title'));
		
		M('setting/setting');
		
		if ($this->request->isPost() && $this->validate()) {
		
			$status = false;			
			foreach ($this->request->post['klarna_fee'] as $klarna_account) {
				if ($klarna_account['status']) {
					$status = true;					
					break;
				}
			}
			$this->request->post['klarna_fee_status'] = $status;			
			$this->model_setting_setting->editSetting('klarna_fee', $this->request->post);
		
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
			'href'      => UA('total/klarna_fee'),
      		'separator' => ' :: '
   		);
		
		$this->data['countries'] = array();
		
		$this->data['countries'][] = array(
			'name' => L('text_germany'),
			'code' => 'DEU'
		);
		
		$this->data['countries'][] = array(
			'name' => L('text_netherlands'),
			'code' => 'NLD'
		);
		
		$this->data['countries'][] = array(
			'name' => L('text_denmark'),
			'code' => 'DNK'
		);
		
		$this->data['countries'][] = array(
			'name' => L('text_sweden'),
			'code' => 'SWE'
		);
		
		$this->data['countries'][] = array(
			'name' => L('text_norway'),
			'code' => 'NOR'
		);
		
		$this->data['countries'][] = array(
			'name' => L('text_finland'),
			'code' => 'FIN'
		);
		
		if (isset($this->request->post['klarna_fee_fee'])) {
			$this->data['klarna_fee_fee'] = $this->request->post['klarna_fee_fee'];
		} else {
			$this->data['klarna_fee_fee'] = C('klarna_fee_fee');
		}
		
		M('localisation/tax_class');		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('total/klarna_fee.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/klarna_fee')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}
?>