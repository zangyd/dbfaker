<?php

namespace zangytd\dbfaker;

/**
 * Class GeneratorConfigurator
 * @package zangyd\dbfaker
 */
class GeneratorConfigurator
{

    public $pk = Generator::PK;
    private $fakerConfigurator;

    public function __construct()
    {
        $this->fakerConfigurator = new FakerConfigurator();
    }

    public function pk() {
        return Generator::PK;
    }

    public function relation($table, $column)
    {
        return [Generator::RELATION, $table, $column];
    }

    /**
     * @return FakerConfigurator
     */
    public function getFakerConfigurator()
    {
        return $this->fakerConfigurator;
    }


}