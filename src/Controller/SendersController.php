<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Senders Controller
 *
 * @property \App\Model\Table\SendersTable $Senders
 */
class SendersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('senders', $this->paginate($this->Senders));
        $this->set('_serialize', ['senders']);
    }

    /**
     * View method
     *
     * @param string|null $id Sender id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sender = $this->Senders->get($id, [
            'contain' => ['Letters']
        ]);
        $this->set('sender', $sender);
        $this->set('_serialize', ['sender']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sender = $this->Senders->newEntity();
        if ($this->request->is('post')) {
            $sender = $this->Senders->patchEntity($sender, $this->request->data);
            if ($this->Senders->save($sender)) {
                $this->Flash->success(__('The sender has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sender could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sender'));
        $this->set('_serialize', ['sender']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sender id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sender = $this->Senders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sender = $this->Senders->patchEntity($sender, $this->request->data);
            if ($this->Senders->save($sender)) {
                $this->Flash->success(__('The sender has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sender could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sender'));
        $this->set('_serialize', ['sender']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sender id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sender = $this->Senders->get($id);
        if ($this->Senders->delete($sender)) {
            $this->Flash->success(__('The sender has been deleted.'));
        } else {
            $this->Flash->error(__('The sender could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
