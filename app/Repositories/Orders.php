<?php 

namespace App\Repositories;

use GuzzleHttp\Client;

class Orders extends GuzzleHttpRequest {

	public $country = "COLOMBIA";

	public function all($params = 'limit=1000') {
        return $this->get("orders?{$params}");
	}

	public function all_carts($params = 'limit=1000') {
        return $this->get("orders/carts?{$params}");
	}

	public function findById($id) {
		return $this->get("orders/{$id}");
	}

	public function create($body) {
		return $this->post("orders", $body);
	}

	public function create_state($id, $body) {
		return $this->post("orders/{$id}/states", $body);
	}

	public function findByUser($email) {
		return $this->get("orders/email/{$email}");
	}

	public function findAllByUser($id) {
		return $this->get("orders/user/{$id}/all");
	}

	public function updateById($id, $update) {
		return $this->put("orders/{$id}", $update);
	}

	public function createPending($id, $address) {
		return $this->put("orders/pending/{$id}", $address);
	}

	public function deleteById($id) {
		return $this->delete("orders/{$id}");
	}
	
}