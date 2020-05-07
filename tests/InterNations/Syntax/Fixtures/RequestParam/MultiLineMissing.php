<?php

use FOS\RestBundle\Controller\Annotations\RequestParam;

class MultiLineMissing
{
    /**
     * @RequestParam(
     *     name="multiLineMissing",
     *     requirements="\d+"
     * )
     */
    private $multiLineMissing;
}
