<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ViasTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ViasTable Test Case
 */
class ViasTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.vias',
        'app.letters',
        'app.senders',
        'app.users',
        'app.groups',
        'app.dispositions',
        'app.evidences',
        'app.evidences_letters',
        'app.evidences_dispositions',
        'app.departements',
        'app.departements_users'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Vias') ? [] : ['className' => 'App\Model\Table\ViasTable'];
        $this->Vias = TableRegistry::get('Vias', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Vias);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
