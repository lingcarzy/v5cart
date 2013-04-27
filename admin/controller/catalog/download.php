<?php
class ControllerCatalogDownload extends Controller {

  	public function index() {
		$this->language->load('catalog/download');

		M('catalog/download');

		$this->getList();
  	}

  	public function insert() {
		$this->language->load('catalog/download');

		M('catalog/download');

		if ($this->request->isPost() && $this->validateForm()) {
			$this->model_catalog_download->addDownload($this->request->post);

			$this->session->set_flashdata('success', L('text_success'));
			$this->redirect(UA('catalog/download'));
		}
    	$this->getForm();
  	}

  	public function update() {
		$this->language->load('catalog/download');

		M('catalog/download');

    	if ($this->request->isPost() && $this->validateForm()) {
			//delete old file
			$download = $this->model_catalog_download->getDownload(G('download_id'));
			if ($download['filename'] !== P('filename')
				&& file_exists(DIR_DOWNLOAD . $download['filename'])) {
					unlink(DIR_DOWNLOAD . $download['filename']);
			}
			
			$this->model_catalog_download->editDownload(G('download_id'), $this->request->post);
			
			$this->data['success'] = $this->session->flashdata('success');
			$this->redirect(UA('catalog/download'));
		}

    	$this->getForm();
  	}

  	public function delete() {
		$this->language->load('catalog/download');
		M('catalog/download');

    	if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $download_id) {
				//delete file
				$download = $this->model_catalog_download->getDownload($download_id);
				if (file_exists(DIR_DOWNLOAD . $download['filename'])) {
					unlink(DIR_DOWNLOAD . $download['filename']);
				}
				$this->model_catalog_download->deleteDownload($download_id);
			}

			$this->data['success'] = $this->session->flashdata('success');
			$this->redirect(UA('catalog/download'));
    	}
    	$this->getList();
  	}

  	protected function getList() {
		$this->document->setTitle(L('heading_title'));

		$this->load->helper('query_filter');
		$qf = new  Query_filter();
		$sort = $qf->get('sort', 'dd.name');
		$order = $qf->get('order', 'ASC');
		$page = $qf->get('page', 1);

		$this->data['downloads'] = array();

		$data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * C('config_admin_limit'),
			'limit' => C('config_admin_limit')
		);

		$download_total = $this->model_catalog_download->getTotalDownloads();

		$results = $this->model_catalog_download->getDownloads($data);

    	foreach ($results as $result) {
			$action = array();

			$action[] = array(
				'text' => L('text_edit'),
				'href' => UA('catalog/download/update', 'download_id=' . $result['download_id'])
			);
			$this->data['downloads'][] = array(
				'download_id' => $result['download_id'],
				'name'        => $result['name'],
				'remaining'   => $result['remaining'],
				'selected'    => isset($this->request->post['selected']) && in_array($result['download_id'], $this->request->post['selected']),
				'action'      => $action
			);
		}

		$url = ($order == 'ASC') ? 'DESC' : 'ASC';

		$this->data['sort_name'] = UA('catalog/download', 'sort=dd.name&order=' . $url);
		$this->data['sort_remaining'] = UA('catalog/download', 'sort=d.remaining&order=' . $url);

		$pagination = new Pagination();
		$pagination->total = $download_total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('catalog/download', 'page={page}', 'SSL');

		$this->data['pagination'] = $pagination->render();

		$this->data['sort'] = $sort;
		$this->data['order'] = strtolower($order);

		$this->data['success'] = $this->session->flashdata('success');

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/download_list.tpl');
  	}

  	protected function getForm() {
		$this->document->setTitle(L('heading_title'));

		$download_id = $this->request->get('download_id', 0);

		if ($download_id) {
			$this->data['action'] = UA('catalog/download/update', 'download_id=' . $download_id);
		} else {
			$this->data['action'] = UA('catalog/download/insert');
		}

		$this->data['languages'] = C('cache_language');

    	if ($download_id && !$this->request->isPost()) {
			$download_info = $this->model_catalog_download->getDownload($download_id);
    	}

  		$this->data['download_id'] = $download_id;

		if (!empty($download_info)) {
			$this->data['filename'] = $download_info['filename'];
			$this->data['mask'] = $download_info['mask'];
			$this->data['remaining'] = $download_info['remaining'];
		}
		else {
			$this->data['filename'] = P('filename');
			$this->data['mask'] = P('mask');
			$this->data['remaining'] = P('remaining', 1);
		}

		$this->data['update'] = P('update', false);

		if (isset($this->request->post['download_description'])) {
			$this->data['download_description'] = $this->request->post['download_description'];
		} elseif ($download_id) {
			$this->data['download_description'] = $this->model_catalog_download->getDownloadDescriptions($download_id);
		} else {
			$this->data['download_description'] = array();
		}

		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->display('catalog/download_form.tpl');
  	}

  	protected function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}
		$this->load->library('form_validation', true);
    	foreach ($this->request->post['download_description'] as $language_id => $value) {
			$this->form_validation->set_rules("download_description[$language_id][name]", '', 'required|range_length[3,64]', L('error_name'));
    	}

		$this->form_validation->set_rules("filename", '', 'required|range_length[3,128]', L('error_filename'));

		$this->form_validation->set_rules("mask", '', 'required|range_length[3,128]', L('error_mask'));

		if ($this->form_validation->run()) {
			if (!file_exists(DIR_DOWNLOAD . $this->request->post['filename']) && !is_file(DIR_DOWNLOAD . $this->request->post['filename'])) {
				$this->form_validation->set_error('filename', L('error_exists'));
				return false;
			}
			return true;
		}
		else return false;
  	}

  	protected function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$this->setMessage('error_warning', L('error_permission'));
			return false;
    	}

		M('catalog/product');
		foreach ($this->request->post['selected'] as $download_id) {
  			$product_total = $this->model_catalog_product->getTotalProductsByDownloadId($download_id);

			if ($product_total) {
	  			$this->setMessage('error_warning', sprintf(L('error_product'), $product_total));
				return false;
			}
		}
		return true;
  	}

	public function upload() {
		$this->language->load('sale/order');

		$json = array();

		if (!$this->user->hasPermission('modify', 'catalog/download')) {
      		$json['error'] = L('error_permission');
    	}

		if (!isset($json['error'])) {
			if (!empty($this->request->files['file']['name'])) {
				$filename = basename(html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8'));

				if (!range_length($filename, 3, 128)) {
					$json['error'] = L('error_filename');
				}

				// Allowed file extension types
				$allowed = array();

				$filetypes = explode(",", C('config_file_extension_allowed'));
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
					$json['error'] = L('error_filetype');
				}

				// Allowed file mime types
				$allowed = array();

				$filetypes = explode("\n", C('config_file_mime_allowed'));
				foreach ($filetypes as $filetype) {
					$allowed[] = trim($filetype);
				}

				if (!in_array($this->request->files['file']['type'], $allowed)) {
					$json['error'] = L('error_filetype');
				}

				if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
					$json['error'] = L('error_upload_' . $this->request->files['file']['error']);
				}
			} else {
				$json['error'] = L('error_upload');
			}
		}

		if (!isset($json['error'])) {
			if (is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
				$ext = md5(mt_rand());

				$json['filename'] = $filename . '.' . $ext;
				$json['mask'] = $filename;

				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_DOWNLOAD . $filename . '.' . $ext);
			}

			$json['success'] = L('text_upload');
		}

		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			M('catalog/download');

			$data = array(
				'filter_name' => G('filter_name'),
				'start'       => 0,
				'limit'       => 20
			);

			$results = $this->model_catalog_download->getDownloads($data);

			foreach ($results as $result) {
				$json[] = array(
					'download_id' => $result['download_id'],
					'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->setOutput(json_encode($json));
	}
}
?>