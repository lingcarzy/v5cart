<?php 
class ControllerToolExport extends Controller { 

	public function index() {
		$this->language->load('tool/export');
		$this->document->setTitle(L('heading_title'));
		
		M('tool/export');

		if ($this->request->isPost() && ($this->validate())) {
			if ((isset( $this->request->files['upload'] )) && (is_uploaded_file($this->request->files['upload']['tmp_name']))) {
				$file = $this->request->files['upload']['tmp_name'];
				if ($this->model_tool_export->upload($file)) {
					$this->session->set_flashdata('success', L('text_success'));
					$this->redirect(UA('tool/export'));
				}
				else {
					$this->error['warning'] = L('error_upload');
				}
			}
		}
		
		$this->data['success'] = $this->session->flashdata('success');
		
		
		$this->document->addScript('view/javascript/jquery/jquery.checkboxtree.min.js');
		$this->document->addStyle('view/stylesheet/jquery.checkboxtree.min.css');
		
		M('catalog/category');
		$this->data['categories'] = $this->model_catalog_category->getCategoryTree();
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('tool/export.tpl');
	}


	public function download() {
		if ($this->validate()) {
			//no category selected
			if (empty($this->request->post['product_category'])) {
				$this->redirect(UA('tool/export'));
			}
			// set appropriate memory and timeout limits
			ini_set("memory_limit","128M");
			set_time_limit( 1800 );

			// send the categories, products and options as a spreadsheet file
			M('tool/export');
			$this->model_tool_export->download($this->request->post);

		} else {
			// return a permission error page
			return $this->forward('error/permission');
		}
	}


	protected function validate() {
		return $this->user->hasPermission('modify', 'tool/export');
	}
}
?>