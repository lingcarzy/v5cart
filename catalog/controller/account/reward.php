<?php
class ControllerAccountReward extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/reward', '', 'SSL');
			
	  		$this->redirect(U('account/login', '', 'SSL'));
    	}		
		
		$this->language->load('account/reward');

		$this->document->setTitle(L('heading_title'));

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => L('text_home'),
			'href'      => HTTP_SERVER,
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => L('text_account'),
			'href'      => U('account/account', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => L('text_reward'),
			'href'      => U('account/reward', '', 'SSL'),
        	'separator' => L('text_separator')
      	);
		
		M('account/reward');
		
		$this->data['heading_title'] = L('heading_title');
		
		$this->data['column_date_added'] = L('column_date_added');
		$this->data['column_description'] = L('column_description');
		$this->data['column_points'] = L('column_points');
		
		$this->data['text_total'] = L('text_total');
		$this->data['text_empty'] = L('text_empty');
		
		$this->data['button_continue'] = L('button_continue');
		
		$page = $this->request->get('page', 1);		
		
		$this->data['rewards'] = array();
		
		$data = array(				  
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
		
		$reward_total = $this->model_account_reward->getTotalRewards($data);
	
		$results = $this->model_account_reward->getRewards($data);
 		
    	foreach ($results as $result) {
			$this->data['rewards'][] = array(
				'order_id'    => $result['order_id'],
				'points'      => $result['points'],
				'description' => $result['description'],
				'date_added'  => date(L('date_format_short'), strtotime($result['date_added'])),
				'href'        => U('account/order/info', 'order_id=' . $result['order_id'], 'SSL')
			);
		}	

		$pagination = new Pagination();
		$pagination->total = $reward_total;
		$pagination->page = $page;
		$pagination->limit = 10; 
		$pagination->text = L('text_pagination');
		$pagination->url = U('account/reward', 'page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();
		
		$this->data['total'] = (int)$this->customer->getRewardPoints();
		
		$this->data['continue'] = U('account/account', '', 'SSL');		
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
		
		$this->display('account/reward.tpl');
	} 		
}
?>