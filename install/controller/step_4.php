<?php
class ControllerStep4 extends Controller {
	public function index() {
		$this->children = array(
			'header',
			'footer'
		);
		$this->display('step_4.tpl');
	}
}
?>