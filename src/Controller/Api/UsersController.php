<?php
namespace App\Controller\Api;

use App\Controller\Api\AppController;

class UsersController extends AppController {

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
                'order' => ['fullname' => 'ASC'],
                'limit' => $limit,
                'page' => $offset
            ];

            if (!empty(trim($query))) {
                $fetchDataOptions['conditions']['LOWER(fullname) LIKE'] = '%' . strtolower($query) . '%';
            }

            $this->paginate = $fetchDataOptions;
            $users = $this->paginate('Users');

            $allUsers = $this->Users->find('all', $fetchDataOptions);
            $total = $allUsers->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'users' => $users,
                'meta' => $meta,
                '_serialize' => ['users', 'meta']
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
            'order' => ['fullname' => 'ASC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(fullname) LIKE'] = '%' . $query . '%';
        }
        $fetchDataOptions['conditions']['active'] = true;

        $hierarchy = $this->Users->find('all', $fetchDataOptions);

        if ($hierarchy->count() > 0) {
            $data = $hierarchy;
        }

        $this->set([
            'hierarchy' => $data,
            '_serialize' => ['hierarchy']
        ]);
    }

    public function calling() {
        $query = $this->request->query['query'];
        $keywordsList = [
            0 => [
                "keyword" => "name",
                "column" => "fullname"
            ],
            1 => [
                "keyword" => "username",
                "column" => "username"
            ]
        ];
        $this->complexQuery($query, $keywordsList);
    }

    public function complexQuery($query = null, $keywordsList)
    {
        $conditions = [];
        $conditions['active'] = true;

        $fetchDataOptions = [
            'order' => ['fullname' => 'ASC'],
            'limit' => $limit
        ];

        // explode query string to query
        $queryString = preg_split("/:(\w+)/", $query);
        $queryQueryString = [];

        foreach($queryString as $value) {
            if(!empty(trim($value))) {
                $queryQueryString[] = strtolower(trim($value));
            }
        }

        // explode query string to keyword
        preg_match_all("/:(\w+)/", $query, $pregOutput);

        $queryQueryKeyword = [];
        $i = 0;

        foreach($pregOutput[0] as $value) {
            $value = trim(str_replace(":", "", $value));
            if(!empty($value)) {
                $key = array_search($value, array_column($keywordsList, 'keyword'));
                if(is_numeric($key)) {
                    $queryQueryKeyword[] = strtolower($value);
                } else {
                    unset($queryQueryString[$i]);
                    $queryQueryString = array_values($queryQueryString);
                }
                $i++;
            }
        }

        // todo add to conditions array, use this url to test on postman
        // http://localhost:9876/api/users/calling?query=lorem ex sit
        $this->set([
            'hierarchy' => $queryQueryString,
            '_serialize' => ['hierarchy']
        ]);
    }
}
