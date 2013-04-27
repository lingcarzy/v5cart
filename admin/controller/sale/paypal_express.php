<?php
class ControllerSalePaypalExpress extends Controller {
	
	public function index() {
		$this->document->setTitle('Paypal Express Sessions');
		//filters
		$page = $this->request->get('page', 1);
		$order_id = $this->request->get('order_id', '');
		$this->data['order_id'] = $order_id;
		
		$sql = "SELECT pe_id,order_id,token,method,ip,date_added FROM @@paypal_express";
		$where = "";
		if ($order_id) {
			$where = " WHERE order_id=$order_id";
		}
		$sql .= "$where ORDER BY pe_id DESC";
		
		if ($page < 0) $page = 1;
		$offset = ($page - 1) * C('config_admin_limit');
		$sql .= " LIMIT $offset, " . C('config_admin_limit');
		
		$total = $this->db->queryOne("SELECT count(*) as total FROM @@paypal_express $where");
		$query = $this->db->query($sql);
		$this->data['sessions'] = $query->rows;
		
		$query_params = $this->request->query('order_id');		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/paypal_express', $query_params . '&page={page}');
		$this->data['pagination'] = $pagination->render();
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('sale/paypal_express_session.tpl');
	}
	
	public function session_info() {
		$id = $this->request->get('id');
		$rs = $this->db->queryOne("SELECT * FROM @@paypal_express WHERE pe_id = $id");
		if ($rs) {
			$output = "<table border=0 bgcolor=#88A8AC cellspacing='1'>";
			$output .= "<tr bgcolor='#ffffff'><th>Order ID</th><td>{$rs['order_id']}</td><th>Token</th><td>{$rs['token']}</td><th>Method</th><td>{$rs['method']}</td></tr>";
			$output .= "<tr bgcolor='#ffffff'><th>Request</th><td colspan='5'><div style='height:150px;overflow:scroll'>".str_replace('&', "<br>", $rs['request'])."</div></td></tr>";
			$output .= "<tr bgcolor='#ffffff'><th>Response</th><td colspan='5'><div style='height:150px;overflow:scroll'>".str_replace('&', "<br>", $rs['response'])."</div></td></tr>";
			$output .= "</table>";
		}
		else {
			$output = "No record";
		}
		$this->response->setOutput($output);
	}
	
	public function payment() {
		$this->document->setTitle('Paypal Express Payments');
		//filters
		$page = $this->request->get('page', 1);
		$order_id = $this->request->get('order_id', '');
		$transaction_id = $this->request->get('transaction_id', '');
		$this->data['order_id'] = $order_id;
		$this->data['transaction_id'] = $transaction_id;
		
		$sql = "SELECT * FROM @@paypal_express_payment";
		$where = " WHERE 1";
		if ($order_id) {
			$where .= " AND order_id=$order_id";
		}
		if ($transaction_id) {
			$where .= " AND transaction_id='$transaction_id'";
		}
		$sql .= "$where ORDER BY id DESC";
		if ($page < 0) $page = 1;
		$offset = ($page - 1) * C('config_admin_limit');
		$sql .= " LIMIT $offset, " . C('config_admin_limit');
		
		$total = $this->db->queryOne("SELECT count(*) as total FROM @@paypal_express_payment $where");
		$query = $this->db->query($sql);
		$this->data['payments'] = $query->rows;
		
		$query_params = $this->request->query('order_id, transaction_id');		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/paypal_express/payment', $query_params . '&page={page}');
		$this->data['pagination'] = $pagination->render();
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('sale/paypal_express_payment.tpl');
	}
	
	public function payment_info() {
		$id = $this->request->get('id');
		$peInfo = $this->db->queryOne("SELECT * FROM @@paypal_express_payment WHERE id = $id");
		if ($peInfo) {
			$output = "<table border=0 bgcolor='#88A8AC' cellspacing='1'><tr bgcolor='#ffffff'>";
			$i = 0;
			foreach($peInfo as $k => $v) {
				$i++;
				$output .= "<th>$k</th><td>$v</td>";
				if ($i % 2 == 0) {
					$output .= "</tr><tr bgcolor='#ffffff'>";
				}				
			}
			$output .= "</tr></table>";
		}
		else {
			$output = "No record";
		}
		$this->response->setOutput($output);
	}
	
	public function error() {
		$this->document->setTitle('Paypal Express Errors');
		//filters
		$page = $this->request->get('page', 1);
		$order_id = $this->request->get('order_id', '');
		$this->data['order_id'] = $order_id;
		
		$sql = "SELECT * FROM @@paypal_express_error";
		$where = '';
		if ($order_id) {
			$where = " WHERE order_id=$order_id";
		}
		$sql .= "$where ORDER BY error_id DESC";
		
		if ($page < 0) $page = 1;
		$offset = ($page - 1) * C('config_admin_limit');
		$sql .= " LIMIT $offset, " . C('config_admin_limit');
		
		$total = $this->db->queryOne("SELECT count(*) as total FROM @@paypal_express_error $where");
		$query = $this->db->query($sql);
		$this->data['errors'] = $query->rows;
		
		$query_params = $this->request->query('order_id');		
		$pagination = new Pagination();
		$pagination->total = $total;
		$pagination->page = $page;
		$pagination->limit = C('config_admin_limit');
		$pagination->text = L('text_pagination');
		$pagination->url = UA('sale/paypal_express/error', $query_params . '&page={page}');
		$this->data['pagination'] = $pagination->render();
		
		$this->children = array(
			'common/header',
			'common/footer',
		);
		$this->display('sale/paypal_express_error.tpl');
	}
}