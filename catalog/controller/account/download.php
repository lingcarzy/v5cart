<?php
class ControllerAccountDownload extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/download', '', 'SSL');
			$this->redirect(U('account/login', '', 'SSL'));
		}

		$this->language->load('account/download');

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
        	'text'      => L('text_downloads'),
			'href'      => U('account/download', '', 'SSL'),
        	'separator' => L('text_separator')
      	);

		M('account/download', 'download');

		$download_total = $this->download->getTotalDownloads();

		if ($download_total) {
			
			$this->data['heading_title'] = L('heading_title');

			$this->data['text_order'] = L('text_order');
			$this->data['text_date_added'] = L('text_date_added');
			$this->data['text_name'] = L('text_name');
			$this->data['text_remaining'] = L('text_remaining');
			$this->data['text_size'] = L('text_size');
			
			$this->data['button_download'] = L('button_download');
			$this->data['button_continue'] = L('button_continue');
			
			$page = G('page', 1);

			$this->data['downloads'] = array();

			$results = $this->download->getDownloads(($page - 1) * C('config_catalog_limit'), C('config_catalog_limit'));

			foreach ($results as $result) {
				if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
					$size = filesize(DIR_DOWNLOAD . $result['filename']);

					$i = 0;

					$suffix = array(
						'B',
						'KB',
						'MB',
						'GB',
						'TB',
						'PB',
						'EB',
						'ZB',
						'YB'
					);

					while (($size / 1024) > 1) {
						$size = $size / 1024;
						$i++;
					}

					$this->data['downloads'][] = array(
						'order_id'   => $result['order_id'],
						'date_added' => date(L('date_format_short'), strtotime($result['date_added'])),
						'name'       => $result['name'],
						'remaining'  => $result['remaining'],
						'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
						'href'       => U('account/download/download', 'order_download_id=' . $result['order_download_id'], 'SSL')
					);
				}
			}

			$pagination = new Pagination();
			$pagination->total = $download_total;
			$pagination->page = $page;
			$pagination->limit = C('config_catalog_limit');
			$pagination->text = L('text_pagination');
			$pagination->url = U('account/download', 'page={page}', 'SSL');

			$this->data['pagination'] = $pagination->render();

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);

			$this->display('account/download.tpl');
		} else {
			$this->data['heading_title'] = L('heading_title');
			
			$this->data['text_error'] = L('text_empty');
			
			$this->data['continue'] = U('account/account', '', 'SSL');
			
			$this->data['button_continue'] = L('button_continue');
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
			$this->display('error/not_found.tpl');
		}
	}

	public function download() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = U('account/download', '', 'SSL');
			$this->redirect(U('account/login', '', 'SSL'));
		}

		M('account/download', 'download');
		
		$order_download_id = (int) G('order_download_id', 0);
		$download_info = $this->download->getDownload($order_download_id);

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					
					if (ob_get_level()) ob_end_clean();
					
					readfile($file, 'rb');

					$this->download->updateRemaining($order_download_id);

					exit;
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
			$this->redirect(U('account/download', '', 'SSL'));
		}
	}
}
?>