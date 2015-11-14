<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller\Api;

use Cake\Controller\Controller;
use Cake\Event\Event;
use \Hashids\Hashids;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

	/**
	 * Hashids Settings
	 *
	 * @var array
	 */
	public $hashids = [
		'salt' => '12025ac9ada2b3f443c1106f2a0f2f86e1fc0db4e63d9c02d1d402bbabf285d39dea2012',
		'min_hash_length' => 8,
		'alphabet' => 'abcdefghijklmnopqrstuvwxyz0123456789'
	];

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();

		$this->loadComponent('RequestHandler');
	}

	/**
	 * Before render callback.
	 *
	 * @param \Cake\Event\Event $event The beforeRender event.
	 * @return void
	 */
	public function beforeRender(Event $event)
	{
		if (!array_key_exists('_serialize', $this->viewVars) &&
			in_array($this->response->type(), ['application/json', 'application/xml'])
		) {
			$this->set('_serialize', true);
		}
	}

	/**
	 * Hashids function
	 *
	 * @return object
	 **/
	public function hashids()
	{
		return $hashids = new Hashids(
			$this->hashids['salt'],
			$this->hashids['min_hash_length'],
			$this->hashids['alphabet']
		);
	}
}
