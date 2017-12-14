<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class OneLineMissingStrict
{
    /**
     * @QueryParam(name="strictMissing", requirements="\d+", description="Useless query parameter")
     */
    private $strictMissing;
}
