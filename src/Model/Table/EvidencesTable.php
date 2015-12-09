<?php
namespace App\Model\Table;

use App\Model\Entity\Evidence;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;

/**
 * Evidences Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Users
 * @property \Cake\ORM\Association\BelongsToMany $Dispositions
 * @property \Cake\ORM\Association\BelongsToMany $Letters
 * @property \Cake\ORM\Association\HasMany $EvidencesLetters
 */
class EvidencesTable extends Table
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

        $this->table('evidences');
        $this->displayField('name');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsToMany('Dispositions', [
            'foreignKey' => 'evidence_id',
            'targetForeignKey' => 'disposition_id',
            'joinTable' => 'evidences_dispositions'
        ]);
        $this->belongsToMany('Letters', [
            'foreignKey' => 'evidence_id',
            'targetForeignKey' => 'letter_id',
            'joinTable' => 'evidences_letters'
        ]);
        $this->hasMany('EvidencesLetters', [
            'foreignKey' => 'evidence_id'
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
            ->allowEmpty('name');

        $validator
            ->requirePresence('extension', 'create')
            ->notEmpty('extension');

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
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }

    public function saveAndRenameLetterJoin($filename, $userId, $letterId) {
        $evidenceId = $this->saveAndRename($filename, $userId, 'Surat Masuk');
        $evidenceLetter = $this->EvidencesLetters->newEntity();
        $evidenceLetter->evidence_id = $evidenceId;
        $evidenceLetter->letter_id = $letterId;

        if($this->EvidencesLetters->save($evidenceLetter)) {
            return 1;
        }
    }

    private function saveAndRename($filename, $userId, $name) {
        $evidence = $this->newEntity();
        // find extension
        $ext = strtolower(substr(strchr($filename, '.'), 1));

        // data to save
        $evidence->user_id = $userId;
        $evidence->name = $name;
        $evidence->extension = $ext;

        if($this->save($evidence)) {
            $evidenceId = $evidence->id;
            // rename filename
            $file = new File(WWW_ROOT . 'files' . DS . $filename);
            $file->copy(WWW_ROOT . 'files' . DS . $evidenceId . '.' . $ext);
            // delete original file
            $file->delete();

            return $evidenceId;
            // @todo error handling if this process fail
        }
    }
}
