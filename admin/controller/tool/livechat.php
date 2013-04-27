<?php 
class ControllerToolLivechat extends Controller {
	public function index() {
		$this->language->load('tool/livechat'); 
		
		$this->data['success'] = $this->session->flashdata('success');
		
		if ($this->user->hasPermission('modify', 'tool/livechat')
				&& !empty($this->request->post['listorder'])) {
			$listorder = $this->request->post['listorder'];
			foreach ($listorder as $k => $v) {
				$this->db->query("UPDATE  @@livechat SET listorder=".intval($v)." where chatid=$k");
				$this->data['success'] = L('text_success');
			}
		}
		
		$this->document->setTitle(L('heading_title'));
		
		$this->data['livechat_type'] = L('livechat_type');
		$this->data['livechat_skin'] = L('livechat_skin');
		
		$this->data['livechats'] = array();
		$query = $this->db->query("SELECT * FROM  @@livechat ORDER BY listorder ASC, type, chatid DESC");
		foreach ($query->rows as $row) {
			$action = array();			
			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('tool/livechat/edit', 'chatid=' . $row['chatid'])
			);
			
			$row['action'] = $action;
			if($row['image'] && file_exists(DIR_IMAGE . $row['image'])) {				
				$row['skin'] = HTTP_IMAGE . $row['image'];
			}
			elseif (isset($this->data['livechat_skin'][$row['type']])
			&& isset($this->data['livechat_skin'][$row['type']][$row['skin']])) {
				
				$row['skin'] = HTTP_IMAGE . 'livechat/' . $this->data['livechat_skin'][$row['type']][$row['skin']];
				
			}			
			else $row['skin'] = '';
			
			if (isset($this->data['livechat_type'][$row['type']])) {
				$row['type'] = $this->data['livechat_type'][$row['type']];
			}
			
			$this->data['livechats'][] = $row;
		}
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->display('tool/livechat_list.tpl');
	}
	
	public function insert() {
		$this->language->load('tool/livechat');
		
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->isPost()) {
			$this->db->insert('livechat', $this->request->post);
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('tool/livechat'));
		}
		$this->document->setTitle(L('heading_title'));
		
		$this->data['token'] =  $this->session->data['token'];
		$this->data['action'] = UA('tool/livechat/insert');
		
		M('tool/image');		
		$this->data['label'] = '';
		$this->data['type'] = 'YMSG';
		$this->data['name'] = '';
		$this->data['ifhide'] = 0;
		$this->data['code'] = '';
		$this->data['skin'] = '';
		$this->data['image'] = '';		
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		$this->data['thumb'] = $this->data['no_image'];
		$this->data['listorder'] = 0;
		$this->data['status'] = 1;
		
		$this->data['livechat_type'] = L('livechat_type');
		$this->data['livechat_skin'] = L('livechat_skin');
		
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->display('tool/livechat_form.tpl');
	}
	
	public function edit() {
		$chatid = $this->request->get['chatid'];
		
		$this->language->load('tool/livechat');
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->isPost()) {
			if (!isset($this->request->post['ifhide'])) {
				$this->request->post['ifhide'] = 0;
			}
			
			$this->db->update('livechat', $this->request->post, array('chatid' => $chatid));
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('tool/livechat'));
		}
		$this->document->setTitle(L('heading_title'));	
		
		$this->data['token'] =  $this->session->data['token'];
		
		$this->data['action'] = UA('tool/livechat/edit', "chatid=$chatid");
		
		$this->data['livechat_type'] = L('livechat_type');
		$this->data['livechat_skin'] = L('livechat_skin');
		
		M('tool/image');
		$this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		
		$chat = $this->db->get('livechat', array('chatid' => $chatid));
		
		$this->data['label'] = $chat['label'];
		$this->data['type'] = $chat['type'];
		$this->data['name'] = $chat['name'];
		$this->data['ifhide'] = $chat['ifhide'];
		$this->data['code'] = html_entity_decode($chat['code']);
		$this->data['skin'] = $chat['skin'];		
		if($chat['image'] && file_exists(DIR_IMAGE . $chat['image'])) {
			$this->data['thumb'] = HTTP_IMAGE.$chat['image'];
			$this->data['image'] = $chat['image'];
		}
		elseif (isset($this->data['livechat_skin'][$chat['type']])
		&& isset($this->data['livechat_skin'][$chat['type']][$chat['skin']])) {
			$this->data['image'] = '';
			$this->data['thumb'] = HTTP_IMAGE . 'livechat/' . $this->data['livechat_skin'][$chat['type']][$chat['skin']];
		}			
		else {
			$this->data['image'] = '';
			$this->data['thumb'] = $this->data['no_image'];
		}
		
		$this->data['listorder'] = $chat['listorder'];
		$this->data['status'] = $chat['status'];
		
		$this->children = array(
			'common/header',
			'common/footer'
		);		
		$this->display('tool/livechat_form.tpl');
	}
	
	public function setting() {
		$this->language->load('tool/livechat'); 
		
		if ($this->user->hasPermission('modify', 'tool/livechat')
		&& $this->request->isPost()) {
			$value = array(
				'title' => $this->request->post['title'],
				'skin' => $this->request->post['skin'],
				'enabled' => $this->request->post['enabled'],
				'posx' => $this->request->post['posx'],
				'posy' => $this->request->post['posy'],
			);
			$this->db->query("UPDATE  @@setting set `value`='".serialize($value)."' WHERE `key`='livechat_setting'");
			
			$this->load->helper('cache');
			cache_setting();
			
			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('tool/livechat'));
		}
		$this->document->setTitle(L('heading_title'));
		
		$this->data['skins'] = array();
		$directories = glob(DIR_CATALOG . 'view/javascript/livechat/skin/*', GLOB_ONLYDIR);		
		foreach ($directories as $directory) {
			$this->data['skins'][] = basename($directory);
		}
		
		$this->data['setting'] = C('livechat_setting');		
		
		$this->children = array(
			'common/header',
			'common/footer'
		);	
		$this->display('tool/livechat_setting.tpl');
	}

	public function delete() {
	
		if (!empty($this->request->post['selected']) 
			&& $this->user->hasPermission('modify', 'tool/livechat')) {
			$this->language->load('tool/livechat'); 
			
			$chatid = implode(',', $this->request->post['selected']);
			$this->db->delete('livechat', "chatid IN ($chatid)");
			
			$this->session->set_flashdata('success', L('text_success'));
		}
		$this->redirect(UA('tool/livechat'));
	}
}