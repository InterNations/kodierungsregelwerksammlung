<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class OneLineMissingDesc
{
    /**
     * @QueryParam(name="descriptionMissing", requirements="\d+", strict=true)
     */
    private $descriptionMissing;
}
