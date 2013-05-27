<?php
class InvalidDocBlocks
{
    /**
     * @var void
     */
    private $null;

    /**
     * @var binary
     */
    private $string;

    /**
     * @var bool
     */
    private $bool;

    /**
     * @var int
     */
    private $int;

    /**
     * @var $this
     */
    private $this;

    /**
     * @var this
     */
    private $_this;

    /**
     * @var real
     */
    private $real;

    /**
     * @var double
     */
    private $double;

    /**
     * @param void $var
     * @return void
     */
    public function nullMethod($var)
    {
    }

    /**
     * @param binary $var
     * @return binary
     */
    public function stringMethod($var)
    {
    }

    /**
     * @param double $var
     * @return double
     */
    public function doubleMethod($var)
    {
    }

    /**
     * @param real $var
     * @return real
     */
    public function realMethod($var)
    {
    }

    /**
     * @param bool $var
     * @return bool
     */
    public function boolMethod($var)
    {
    }

    /**
     * @param int $var
     * @return int
     */
    public function intMethod($var)
    {
    }

    /**
     * @param $this $var
     * @return $this
     */
    public function dollarThisMethod($var)
    {
    }

    /**
     * @param this $var
     * @return this
     */
    public function thisMethod(self $var)
    {
    }

    /**
     * @param int|null
     * @return void|integer
     */
    public function combinedParam($var)
    {
    }
}
