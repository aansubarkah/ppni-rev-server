<?php
namespace App\Controller\Api;

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
        if($this->request->is('post')) {
            $letter = $this->Letters->newEntity($this->request->data['letter']);
            $this->set([
                'letter' => $letter,
               '_serialize' => ['letter']
            ]);
        }
    }
}
