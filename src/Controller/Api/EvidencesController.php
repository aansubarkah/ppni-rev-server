<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;
use UploadHandler;

class EvidencesController extends AppController {
    public function initialize() {
        parent::initialize();
    }

    public function add() {
        $evidence = $this->Evidences->newEntity();
        //require_once(ROOT . 'vendor' . DS  . 'Facebook' . DS . 'src' . DS . 'facebook.php');
        require_once(ROOT . DS . 'vendor' . DS . 'blueimp' . DS . 'jquery-file-upload' . DS . 'server' . DS . 'php'  . DS . 'UploadHandler.php');
        //if($this->request->is('post')) {
        //$extension = 'pdf';
        //$this->set('_serialize', ['evidence']);
        //}
        $options = array(
            'upload_dir' => WWW_ROOT . 'files' . DS,
            'accept_file_types' => '/\.(gif|jpe?g|png|txt|pdf|doc|docx|xls|xlsx|ppt|pptx|rar|zip|odt|tar|gz)$/i'
        );

        $upload_handler = new UploadHandler($options);

        exit;
        //$message ='success';
        //$this->set('_serialize', ['message']);
    }
}
