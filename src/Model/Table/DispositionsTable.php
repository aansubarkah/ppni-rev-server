<?php
namespace App\Model\Table;

use App\Model\Entity\Disposition;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Dispositions Model
 *
 * @property \Cake\ORM\Association\BelongsTo $ParentDispositions
 * @property \Cake\ORM\Association\BelongsTo $Letters
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\HasMany $ChildDispositions
 * @property \Cake\ORM\Association\BelongsToMany $Evidences
 */
class DispositionsTable extends Table
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

        $this->table('dispositions');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Tree');

        $this->belongsTo('ParentDispositions', [
            'className' => 'Dispositions',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsTo('Letters', [
            'foreignKey' => 'letter_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('ChildDispositions', [
            'className' => 'Dispositions',
            'foreignKey' => 'parent_id'
        ]);
        $this->belongsToMany('Evidences', [
            'foreignKey' => 'disposition_id',
            'targetForeignKey' => 'evidence_id',
            'joinTable' => 'evidences_dispositions'
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
            ->add('lft', 'valid', ['rule' => 'numeric'])
            ->requirePresence('lft', 'create')
            ->notEmpty('lft');

        $validator
            ->add('rght', 'valid', ['rule' => 'numeric'])
            ->requirePresence('rght', 'create')
            ->notEmpty('rght');

        $validator
            ->add('recipient', 'valid', ['rule' => 'numeric'])
            ->requirePresence('recipient', 'create')
            ->notEmpty('recipient');

        $validator
            ->requirePresence('content', 'create')
            ->notEmpty('content');

        $validator
            ->add('isread', 'valid', ['rule' => 'boolean'])
            ->requirePresence('isread', 'create')
            ->notEmpty('isread');

        $validator
            ->add('finish', 'valid', ['rule' => 'boolean'])
            ->requirePresence('finish', 'create')
            ->notEmpty('finish');

        $validator
            ->add('active', 'valid', ['rule' => 'boolean'])
            ->requirePresence('active', 'create')
            ->notEmpty('active');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['parent_id'], 'ParentDispositions'));
        $rules->add($rules->existsIn(['letter_id'], 'Letters'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}
