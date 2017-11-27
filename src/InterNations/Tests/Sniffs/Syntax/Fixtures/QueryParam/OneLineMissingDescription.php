<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class OneLineMissingDescription
{
    /**
     * @QueryParam(name="descriptionMissing", requirements="\d+", strict=true)
     */
    private $descriptionMissing;
}
