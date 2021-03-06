<?php
class ModelReportDashboard extends Model {
	public function getTotalSales() {
      	$query = $this->db->query("SELECT SUM(total) AS total FROM `" . DB_PREFIX . "order` WHERE order_status_id > '0'");

		return $query->row['total'];
	}
	
	public function getTotalCustomersOnline() {
		$online_data = array();
		
		for ($i = 0; $i < 60; $i++) {
			$online_data[$i] = array(
				'minute' => date('i', mktime(0, 0, 0, $i)),
				'total'  => 0
			);
		}
			
		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer_online` GROUP BY MINUTE(date_added)");

		foreach ($query->rows as $result) {
			$online_data[date('i', strtotime($result['date_added']))] = array(
				'minute' => date('i', strtotime($result['date_added'])),
				'total'  => $result['total']
			);		
		}
		
		return $online_data;
	}
	
	// Orders
	public function getTotalOrdersByDay() {
		$order_data = array();
		
		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}		
		 
		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}
		
		return $order_data;			
	}
		
	public function getTotalOrdersByWeek() {
		$order_data = array();
		
		$date_start = strtotime('-' . date('w') . ' days'); 
		
		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));
			
			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}	

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}

		return $order_data;
	}	
	
	public function getTotalOrdersByMonth() {
		$order_data = array();
		
		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;
			
			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}		
		
		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}
		
		return $order_data;
	}
	
	public function getTotalOrdersByYear() {
		$order_data = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}		

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}

		return $order_data;
	}
	
	// Customers
	public function getTotalCustomersByDay() {
		$order_data = array();
		
		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'total' => 0
			);
		}		
		 
		$query = $this->db->query("SELECT COUNT(*) AS total, HOUR(date_added) AS hour FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) = DATE(NOW()) GROUP BY HOUR(date_added) ORDER BY date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'total' => $result['total']
			);
		}
		
		return $order_data;			
	}	
	
	public function getTotalCustomersByWeek() {
		$order_data = array();
		
		$date_start = strtotime('-' . date('w') . ' days'); 
		
		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));
			
			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'total' => 0
			);
		}	

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}

		return $order_data;
	}	
				
	public function getTotalCustomersByMonth() {
		$order_data = array();
		
		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;
			
			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'total' => 0
			);
		}		
		
		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}
		
		return $order_data;
	}
	
	public function getTotalCustomersByYear() {
		$order_data = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'total' => 0
			);
		}		

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "customer` WHERE YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'total' => $result['total']
			);		
		}

		return $order_data;
	}
	
	// Marketing
	public function getTotalMarketingsByDay() {
		$order_data = array();
		
		for ($i = 0; $i < 24; $i++) {
			$order_data[$i] = array(
				'hour'  => $i,
				'click' => 0,
				'sale'  => 0
			);
		}		
		 
		$query = $this->db->query("SELECT SUM(m.clicked) AS click, (SELECT COUNT(o.order_id) FROM `" . DB_PREFIX . "order` o WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND o.tracking = m.code AND DATE(o.date_added) = DATE(m.date_added) AND HOUR(o.date_added) = HOUR(m.date_added)) AS sale, HOUR(m.date_added) AS hour FROM `" . DB_PREFIX . "marketing` m WHERE DATE(m.date_added) = DATE(NOW()) GROUP BY HOUR(m.date_added) ORDER BY m.date_added ASC");

		foreach ($query->rows as $result) {
			$order_data[$result['hour']] = array(
				'hour'  => $result['hour'],
				'click' => $result['clicked'],
				'sale'  => $result['sale']
			);
		}
		
		return $order_data;			
	}
		
	public function getTotalMarketingsByWeek() {
		$order_data = array();
		
		$date_start = strtotime('-' . date('w') . ' days'); 
		
		for ($i = 0; $i < 7; $i++) {
			$date = date('Y-m-d', $date_start + ($i * 86400));
			
			$order_data[date('w', strtotime($date))] = array(
				'day'   => date('D', strtotime($date)),
				'click' => 0,
				'sale'  => 0
			);
		}	

		$query = $this->db->query("SELECT SUM(m.clicked) AS click, (SELECT COUNT(o.order_id) FROM `" . DB_PREFIX . "order` o WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND o.tracking = m.code AND DATE(o.date_added) = DATE(m.date_added)) AS sale, m.date_added FROM `" . DB_PREFIX . "marketing` m WHERE DATE(m.date_added) >= DATE('" . $this->db->escape(date('Y-m-d', $date_start)) . "') GROUP BY DAYNAME(m.date_added)");

		foreach ($query->rows as $result) {
			$order_data[date('w', strtotime($result['date_added']))] = array(
				'day'   => date('D', strtotime($result['date_added'])),
				'click' => $result['clicked'],
				'sale'  => $result['sale']
			);		
		}

		return $order_data;
	}	
	
	public function getTotalMarketingsByMonth() {
		$order_data = array();
		
		for ($i = 1; $i <= date('t'); $i++) {
			$date = date('Y') . '-' . date('m') . '-' . $i;
			
			$order_data[date('j', strtotime($date))] = array(
				'day'   => date('d', strtotime($date)),
				'click' => 0,
				'sale'  => 0
			);
		}		
		
		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND DATE(date_added) >= '" . $this->db->escape(date('Y') . '-' . date('m') . '-1') . "' GROUP BY DATE(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('j', strtotime($result['date_added']))] = array(
				'day'   => date('d', strtotime($result['date_added'])),
				'click' => $result['clicked'],
				'sale'  => $result['sale']
			);		
		}
		
		return $order_data;
	}
	
	public function getTotalMarketingsByYear() {
		$order_data = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$order_data[$i] = array(
				'month' => date('M', mktime(0, 0, 0, $i)),
				'click' => 0,
				'sale'  => 0
			);
		}		

		$query = $this->db->query("SELECT COUNT(*) AS total, date_added FROM `" . DB_PREFIX . "order` WHERE order_status_id = '" . (int)$this->config->get('config_complete_status_id') . "' AND YEAR(date_added) = YEAR(NOW()) GROUP BY MONTH(date_added)");
			
		foreach ($query->rows as $result) {
			$order_data[date('n', strtotime($result['date_added']))] = array(
				'month' => date('M', strtotime($result['date_added'])),
				'click' => $result['clicked'],
				'sale'  => $result['sale']
			);		
		}

		return $order_data;
	}
}
?>