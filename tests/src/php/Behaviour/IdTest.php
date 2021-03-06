<?php

namespace Behaviour;

use Cti\Di\Manager;

class IdTest extends \PHPUnit_Framework_TestCase
{
    function testId()
    {
        $manager = getApplication()->getManager();

        /**
         * @var \Cti\Storage\Component\Model $test
         */
        $test = $manager->create('Cti\Storage\Component\Model', array(
            'name'    => 'test',
            'comment' => 'test',
        ));

        $test->addProperty('name', array('type' => 'string'));

        // behaviour id added by default
        // $test->addBehaviour('id');

        $this->assertSame($test->getPk(), array('id_test'));
        $this->assertCount(2, $test->getProperties());

        $id = $test->getProperty('id_test');

        $this->assertSame($id->getType(), 'integer');
        $this->assertSame($id->getComment(), 'Identifier');
        $this->assertSame($id->getRequired(), true, "Need to be notNull field");
    }
}
 