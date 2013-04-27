<?php 
class ControllerTotalReward extends Controller { 
	 
	public function index() { 
		$this->language->load('total/reward');

		$this->document->setTitle(L('heading_title'));
		
		M('setting/setting');
		
		if ($this->request->isPost() && $this->validate()) {
			$this->model_setting_setting->editSetting('reward', $this->request->post);
		
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
			'href'      => UA('total/reward'),
      		'separator' => ' :: '
   		);
		
		if ($this->request->isPost()) {
			$this->data['reward_status'] = P('reward_status');
			$this->data['reward_sort_order'] = P('reward_sort_order');
		} else {
			$this->data['reward_status'] = C('reward_status');
			$this->data['reward_sort_order'] = C('reward_sort_order');
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->display('total/reward.tpl');
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'total/reward')) {
			$this->setMessage('error_warning', L('error_permission'));
			return false;
		}
		return true;
	}
}