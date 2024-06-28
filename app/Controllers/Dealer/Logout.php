<?php

namespace App\Controllers\dealer;

use App\Controllers\BaseController;

/**
 * end session controller
 */
class Logout extends BaseController {
	public function index() {
		/*// Save logout log */
		$this->saveLogoutLog();

		session()->destroy();
		return redirect()->to('./dealer/login');
	}

	private function saveLogoutLog() {
		$UserModel = new \App\Models\UserModel();
		$logId = session()->get('login_log_id');
		if ($logId) {
			$UserModel->updateloginLogs($logId, [
				'logoutTime' => date('Y-m-d H:i:s'),
			]);
			session()->remove('login_log_id');
		}
	}
}
