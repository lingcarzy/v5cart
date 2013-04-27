<?php
class ControllerToolBackup extends Controller {

	public function index() {
		$this->language->load('tool/backup');
		$this->document->setTitle(L('heading_title'));

		M('tool/backup');

		if ($this->request->isPost() && $this->user->hasPermission('modify', 'tool/backup')) {
			if (is_uploaded_file($this->request->files['import']['tmp_name'])) {
				$content = file_get_contents($this->request->files['import']['tmp_name']);
			} else {
				$content = false;
			}

			if ($content) {
				$this->model_tool_backup->restore($content);
				$this->session->set_flashdata('success', L('text_success'));
				$this->redirect(UA('tool/backup'));
			} else {
				$this->session->set_flashdata('error', L('error_empty'));
			}
		}

		$this->data['success'] = $this->session->flashdata('success');
		$this->data['error_warning'] = $this->session->flashdata('error');

		$this->data['tables'] = $this->model_tool_backup->getTables();

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('tool/backup.tpl');
	}

	public function backup() {
		$this->language->load('tool/backup');

		if (!isset($this->request->post['backup'])) {
			$this->session->set_flashdata('error', L('error_backup'));
			$this->redirect(UA('tool/backup'));
		} elseif ($this->user->hasPermission('modify', 'tool/backup')) {
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . date('Y-m-d_H-i-s', time()).'_backup.sql');
			$this->response->addheader('Content-Transfer-Encoding: binary');

			M('tool/backup');
			$this->response->setOutput($this->model_tool_backup->backup($this->request->post['backup']));
		} else {
			$this->session->set_flashdata('error', L('error_permission'));
			$this->redirect(UA('tool/backup'));
		}
	}
}
?>