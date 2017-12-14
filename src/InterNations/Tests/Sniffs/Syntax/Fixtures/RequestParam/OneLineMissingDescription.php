<?php

use FOS\RestBundle\Controller\Annotations\RequestParam;

class OneLineMissingDescription
{
    /**
     * @RequestParam(name="descriptionMissing", requirements="\d+", strict=true)
     */
    private $descriptionMissing;
}
