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
     * @var boolean
     */
    private $boolean;

    /**
     * @var integer
     */
    private $integer;

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
     * @param boolean $var
     * @return boolean
     */
    public function booleanMethod($var)
    {
    }

    /**
     * @param integer $var
     * @return integer
     */
    public function integerMethod($var)
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
     * @param integer|null
     * @return void|integer
     */
    public function combinedParam($var)
    {
    }
}
