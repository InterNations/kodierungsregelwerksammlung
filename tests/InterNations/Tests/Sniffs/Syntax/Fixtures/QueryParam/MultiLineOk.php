<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class MultiLineOk
{
    /**
     * @QueryParam(
     *     name="multiLineOk",
     *     requirements="\d+",
     *     strict=true,
     *     description="Useless query parameter"
     * )
     */
    private $multiLineOk;
}
