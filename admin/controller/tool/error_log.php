<?php
class ControllerToolErrorLog extends Controller {

	public function index() {
		$this->language->load('tool/error_log');
		$this->document->setTitle(L('heading_title'));

		$this->data['success'] = $this->session->flashdata('success');

		$file = DIR_LOGS . C('config_error_filename');
		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->display('tool/error_log.tpl');
	}

	public function clear() {
		$this->language->load('tool/error_log');

		$file = DIR_LOGS . C('config_error_filename');
		$handle = fopen($file, 'w+');
		fclose($handle);

		$this->session->set_flashdata('success', L('text_success'));
		$this->redirect(UA('tool/error_log'));
	}
}
?>