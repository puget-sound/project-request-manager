<?php

namespace App\Classes;

class LiquidPlannerClass {
	private $_base_uri = "https://app.liquidplanner.com/api";
	private $_ch;
	public  $workspace_id;

	function __construct($email, $password) {
		$this->_ch = curl_init();
		curl_setopt($this->_ch, CURLOPT_HEADER, false);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->_ch, CURLOPT_USERPWD, "$email:$password");
		curl_setopt($this->_ch, CURLOPT_HTTPHEADER, array('content-type: application/json'));
		curl_setopt($this->_ch, CURLOPT_ENCODING, 'gzip');
	}

	public function get($url) {
		curl_setopt($this->_ch, CURLOPT_HTTPGET, true);
		curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
		return json_decode(curl_exec($this->_ch));
	}

	public function post($url, $body=null) {
		curl_setopt($this->_ch, CURLOPT_POST, true);
		curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($body));
		return json_decode(curl_exec($this->_ch));
	}

	public function put($url, $body=null) {
		curl_setopt($this->_ch, CURLOPT_CUSTOMREQUEST, 'PUT');
		curl_setopt($this->_ch, CURLOPT_URL, $this->_base_uri.$url);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, json_encode($body));
		return json_decode(curl_exec($this->_ch));
	}

	public function members() {
    return $this->get("/workspaces/{$this->workspace_id}/members");
  }

	public function clients() {
    return $this->get("/workspaces/{$this->workspace_id}/clients");
  }

	public function project() {
    return $this->get("/workspaces/{$this->workspace_id}/projects/{$this->project_id}");
  }

	public function timesheet_entries() {
    return $this->get("/workspaces/{$this->workspace_id}/timesheet_entries?project_id={$this->project_id}&end_date=2017-09-06");
  }

	public function create_client($data) {
    return $this->post("/workspaces/{$this->workspace_id}/clients", array("client"=>$data));
  }

	public function create_task($data) {
		return $this->post("/workspaces/{$this->workspace_id}/tasks", array("task"=>$data));
	}

	public function create_project($data) {
		return $this->post("/workspaces/{$this->workspace_id}/projects", array("project"=>$data));
	}

	public function create_link($data) {
		return $this->post("/workspaces/{$this->workspace_id}/links", array("link"=>$data));
	}

	public function update_task($data) {
		return $this->put("/workspaces/{$this->workspace_id}/tasks/{$data['id']}", array("task"=>$data));
	}

}
