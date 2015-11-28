<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class SendersController extends AppController {

    public $limit = 25;

    public function initialize()
    {
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
                'order' => ['name' => 'ASC'],
                'limit' => $limit,
                'page' => $offset
            ];

            if (!empty(trim($query))) {
                $fetchDataOptions['conditions']['LOWER(name) LIKE'] = '%' . strtolower($query) . '%';
            }

            $this->paginate = $fetchDataOptions;
            $data = $this->paginate('Senders');

            $allRows = $this->Senders->find('all', $fetchDataOptions);
            $total = $allRows->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'senders' => $data,
                'meta' => $meta,
                '_serialize' => ['senders', 'meta']
            ]);
        }
    }

    public function checkExistence($name = null, $limit = 25)
    {
        $data = [
            [
                'id' => 0,
                'name' => '',
                'active' => 0
            ]
        ];

        $fetchDataOptions = [
            'order' => ['name' => 'ASC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(name) LIKE'] = '%' . $query . '%';
        }
        $fetchDataOptions['conditions']['active'] = true;

        $datum = $this->Senders->find('all', $fetchDataOptions);

        if ($datum->count() > 0) {
            $data = $datum;
        }

        $this->set([
            'sender' => $data,
            '_serialize' => ['sender']
        ]);
    }
}
