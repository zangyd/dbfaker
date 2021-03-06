<?php

/**
 * Created by PhpStorm.
 * User: zangyd
 * Date: 17.09.15
 * Time: 17:05
 */
class TableConfiguratorTest extends PHPUnit_Framework_TestCase
{
    const TABLE_NAME = 'table';
    //todo: do we need this?
    private $columns = [
        'column1' => [1, 2, 34, 5, 6],
        'column2' => ['romeo', 'juliet', 'others'],
        'column3' => ['comment1', 'comment2', 'comment3'],
    ];
    private $configurator;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->configurator = new \zangyd\dbfaker\TableConfigurator(new \zangyd\dbfaker\Table(self::TABLE_NAME, new \zangyd\dbfaker\Generator(), new \zangyd\dbfaker\DbHelper(new Pdo('sqlite::memory:'))));
        parent::__construct($name, $data, $dataName);
    }

    public function testColumns()
    {
        $this->configurator->columns($this->columns);
        $table = Helper::getPrivateValue($this->configurator, 'table');
        $this->assertEquals($this->columns, Helper::getPrivateValue($table, 'columnConfig'));
    }

    public function testRowQuantity()
    {
        $value = 10;
        $this->configurator->rowQuantity($value);
        $table = Helper::getPrivateValue($this->configurator, 'table');
        $this->assertEquals($value, Helper::getPrivateValue($table, 'rowQuantity'));
    }

    public function testData()
    {
        $data = [[]];
        $columnNames = array_keys($this->columns);
        $this->configurator->data($data, $columnNames);
        $table = Helper::getPrivateValue($this->configurator, 'table');
        $this->assertEquals($data, Helper::getPrivateValue($table, 'rawData'));
        $this->assertEquals($columnNames, array_keys($table->getColumns()));
    }

    public function testGetTable() {
        $table = Helper::getPrivateValue($this->configurator, 'table');
        $this->assertInstanceOf('zangyd\dbfaker\Table', $table);
    }

    //todo test preprocess


}
