<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class MultiLineMissing
{
    /**
     * @QueryParam(
     *     name="multiLineMissing",
     *     requirements="\d+"
     * )
     */
    private $multiLineMissing;
}
