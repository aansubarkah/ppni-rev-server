<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class DepartementsController extends AppController {

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
                'conditions' => ['active' => true, 'NOT' => ['parent_id' => 0]],
                'order' => ['id' => 'ASC'],
                'limit' => $limit,
                'page' => $offset
            ];

            if (!empty(trim($query))) {
                $fetchDataOptions['conditions']['LOWER(name) LIKE'] = '%' . strtolower($query) . '%';
            }

            $this->paginate = $fetchDataOptions;
            $departements = $this->paginate('Departements');

            $allHierarchy = $this->Departements->find('all', $fetchDataOptions);
            $total = $allHierarchy->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'departements' => $departements,
                'meta' => $meta,
                '_serialize' => ['departements', 'meta']
            ]);
        }
    }

    /**
     * jsTree method
     *
     * @return void
     */
    public function tree()
    {
        $query = $this->Departements->find();
        $query->select(['id', 'parent'=>'parent_id', 'text'=>'name']);
        $query->where(['active'=>true]);

        $departements=[];
        foreach($query as $datum){
            $opened = false;
            if($datum['id'] == 0) $opened = true;

            if($datum['parent'] == 0){
                $datum['parent'] = '#';
                $opened = true;
            }
            $departements[] = [
                'id' => $datum['id'],
                'parent' => $datum['parent'],
                'text' => $datum['text'],
                'state' => ['opened' => $opened]
            ];
        }
        $this->set([
            'hierarchies' => $departements,
            '_serialize' => ['hierarchies']
        ]);
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

        $departement = $this->Departements->find('all', $fetchDataOptions);

        if ($departement->count() > 0) {
            $data = $departement;
        }

        $this->set([
            'departement' => $data,
            '_serialize' => ['departement']
        ]);
    }
}
