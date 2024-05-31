<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;

/**
 * end session controller
 */
class Logout extends BaseController {
	public function index() {
		$session = session();
		$session->destroy();
		return redirect()->to('./dealer/login');
	}
}
