<?php

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;

class QueryParams
{
    /**
     * @QueryParam(name="bothMissing", requirements="\d+")
     */
    private $bothMissing;

    /**
     * @Rest\QueryParam(name="bothMissing2", requirements="\d+")
     */
    private $bothMissing2;

    /**
     * @FOS\RestBundle\Controller\Annotations\QueryParam(name="bothMissing3", requirements="\d+")
     */
    private $bothMissing3;

    /**
     * @QueryParam(name="descriptionMissing", requirements="\d+", strict=true)
     */
    private $descriptionMissing;

    /**
     * @QueryParam(name="strictMissing", requirements="\d+", description="Useless query parameter")
     */
    private $strictMissing;
}
