<?php
namespace App\Model\Table;

use App\Model\Entity\Sender;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Senders Model
 *
 * @property \Cake\ORM\Association\HasMany $Letters
 */
class SendersTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('senders');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->hasMany('Letters', [
            'foreignKey' => 'sender_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->add('active', 'valid', ['rule' => 'boolean'])
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }

    public function findAndSave($name) {
        $idToReturn = 0;
        $name = strtolower(trim($name));
        $query = $this->find('all', [
            'conditions' => ['Lower(name) LIKE' => '%' . $name . '%'],
            'order' => ['name' => 'DESC']
        ]);
        $count = $query->count();
        $row = $query->first();

        // if record didn't exists, insert it, else use it
        if($count < 1) {
            $sender = $this->newEntity();
            $sender->name = $name;

            if($this->save($sender)) {
                $idToReturn = $sender->id;
            }
        } else {
            $idToReturn = $row->id;
        }

        return $idToReturn;
    }
}
