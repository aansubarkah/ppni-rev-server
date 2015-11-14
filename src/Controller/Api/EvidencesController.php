<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use UploadHandler;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

class EvidencesController extends AppController {
	public function initialize() {
		parent::initialize();
	}

	public function add() {
	}

	public function upload() {
		$evidence = $this->Evidences->newEntity();
		require_once(ROOT . DS . 'vendor' . DS . 'blueimp' . DS . 'jquery-file-upload' . DS . 'server' . DS . 'php'  . DS . 'UploadHandler.php');

		// for greater max_file_size,
		// sudo vim /etc/php5/cli/php.ini
		// change
		// upload_max_filesize = 1024M
		// post_max_size = 1024M
		$options = array(
			'upload_dir' => WWW_ROOT . 'files' . DS,
			'accept_file_types' => '/\.(gif|jpe?g|png|txt|pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip|odt|tar|gz)$/i'
		);

		$upload_handler = new UploadHandler($options);

		$file = new File(WWW_ROOT . 'files' . DS . $upload_handler->get_file_name_param);
		$file->copy(WWW_ROOT . 'files' . DS . '1.pdf', true);
		exit;
	}
}
