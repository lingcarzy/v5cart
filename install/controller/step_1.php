<?php
class ControllerStep1 extends Controller {
	
	public function index() {
		if ($this->request->isPost() && ($this->validate())) {
			$this->redirect(HTTP_SERVER . 'index.php?route=step_2');
		}
		
		$this->data['action'] = HTTP_SERVER . 'index.php?route=step_1';
		
		$this->children = array(
			'header',
			'footer'
		);
		
		$this->display('step_1.tpl');
	}
	
	private function validate() {
		if (!isset($this->request->post['agree'])) {
			$this->setMessage('error_warning', 'You must agree to the license before you can install V5Cart!');
			return false;
		}
		
   		return true;
	}	
}
?>