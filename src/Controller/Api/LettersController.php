<?php
namespace App\Controller\Api;
use Cake\I18n\I18n;//cakephp need this to save datetime field
use Cake\I18n\Time;//cakephp need this to save datetime field
use Cake\Database\Type;//cakephp need this to save datetime field

use App\Controller\Api\AppController;

class LettersController extends AppController {
    public $limit = 25;

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $limit = $this->limit;
        if (isset($this->request->query['limit'])) {
            if (is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
        }

        if (isset($this->request->query['name'])) {
            $searchName = trim($this->request->query['name']);
            $this->checkExistence($searchName, $limit);
        } else {
            $offset = 0;
            if (isset($this->request->query['page'])) {
                if (is_numeric($this->request->query['page'])) {
                    $offset = $this->request->query['page'] - 1;
                }
            }

            $query = '';
            if (isset($this->request->query['query'])) {
                if (!empty(trim($this->request->query['query']))) {
                    $query = trim($this->request->query['query']);
                }
            }

            $fetchDataOptions = [
                'conditions' => ['active' => true],
                'order' => ['date' => 'DESC'],
                'limit' => $limit,
                'page' => $offset
            ];

            if (!empty(trim($query))) {
                $fetchDataOptions['conditions']['LOWER(content) LIKE'] = '%' . strtolower($query) . '%';
            }

            $this->paginate = $fetchDataOptions;
            $letters = $this->paginate('Letters');

            $allLetters = $this->Letters->find('all', $fetchDataOptions);
            $total = $allLetters->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'letters' => $letters,
                'meta' => $meta,
                '_serialize' => ['letters', 'meta']
            ]);
        }
    }

    public function checkExistence($name = null, $limit = 25)
    {
        $data = [
            [
                'id' => 0,
                'number' => '',
                'active' => 0
            ]
        ];

        $fetchDataOptions = [
            'order' => ['date' => 'DESC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(number) LIKE'] = '%' . $query . '%';
        }
        $fetchDataOptions['conditions']['active'] = true;

        $letter = $this->Letters->find('all', $fetchDataOptions);

        if ($letter->count() > 0) {
            $data = $letter;
        }

        $this->set([
            'letter' => $data,
            '_serialize' => ['letter']
        ]);
    }

    public function add() {
        $letter = $this->Letters->newEntity();
        if($this->request->is('post')) {
            $errorMessages = [];
            /**
             * letter =
             * [
             * sender_id,
             * user_id,
             * via_id,
             * number,
             * date,
             * content,
             * created = null,
             * modified = null,
             * isread = false,
             * active = true,
             * senderName = lorem,
             * fileName = 1.png,
             * dispositions = [],
             * evidences = [],
             * sender = null,
             * user = null,
             * via = null,
             *
             * ]
             */
            unset($this->request->data['letter']['active']);
            unset($this->request->data['letter']['created']);
            unset($this->request->data['letter']['modified']);
            $this->request->data['letter']['created'] = null;
            $this->request->data['letter']['modified'] = null;
            $this->request->data['letter']['active'] = true;

            $date = date("Y-m-d", strtotime($this->request->data['letter']['date']));
            Type::build('datetime')->useLocaleParser();//cakephp need this to save datetime field
            $this->request->data['letter']['date'] = new Time($date);

            if($this->request->data['letter']['user_id'] === null) {
                $errorMessages[] = 'Pengguna tidak sah';
            }
            if($this->request->data['letter']['number'] === null) {
                $errorMessages[] = 'Nomor tidak boleh kosong';
            }
            if($this->request->data['letter']['content'] === null) {
                $errorMessages[] = 'Perihal tidak boleh kosong';
            }
            if($this->request->data['letter']['senderName'] === null) {
                $errorMessages[] = 'Pengirim tidak boleh kosong';
            } else {
                $this->request->data['letter']['sender_id'] = $this->Letters->Senders->findAndSave($this->request->data['letter']['senderName']);
            }
            if($this->request->data['letter']['fileName'] === null) {
                $errorMessages[] = 'Berkas harus sudah diunggah';
            }

            //$letter = $this->Letters->newEntity($this->request->data['letter']);
            //$letter = $this->request->data['letter'];
            //$letter = $this->Letters->Senders->findAndSave($this->request->data['letter']['senderName']);

            if(count($errorMessages) > 0) {
                $dataToReturn = $errorMessages;
            } else {
                $letter = $this->Letters->patchEntity($letter, $this->request->data['letter']);
                if($this->Letters->save($letter)) {
                    $dataToReturn = 1;
                } else {
                    $dataToReturn = $letter;
                }
            }

            $this->set([
                'letter' => $dataToReturn,
                '_serialize' => ['letter']
            ]);
        }
    }
}
