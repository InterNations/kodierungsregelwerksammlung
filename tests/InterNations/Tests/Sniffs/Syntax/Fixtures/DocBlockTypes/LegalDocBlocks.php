<?php
class LegalDocBlocks
{
    /**
     * @var null
     */
    private $null;

    /**
     * @var string
     */
    private $string;

    /**
     * @var array
     */
    private $array;

    /**
     * @var boolean
     */
    private $boolean;

    /**
     * @var self
     */
    private $self;

    /**
     * @param null $var
     * @return null
     */
    public function nullMethod($var)
    {
    }

    /**
     * @param string $var
     * @return string
     */
    public function stringMethod($var)
    {
    }

    /**
     * @param array $var
     * @return array
     */
    public function arrayMethod(array $var)
    {
    }

    /**
     * @param float $var
     * @return float
     */
    public function floatMethod($var)
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
     * @param self $var
     * @return self
     */
    public function self(self $var)
    {
    }

    /**
     * @param string|integer $var
     * @return LegalDocBlocks|null
     */
    public function multipleTypeMethods($var)
    {
    }
}
