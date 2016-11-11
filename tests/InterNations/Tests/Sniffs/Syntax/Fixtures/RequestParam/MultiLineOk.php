<?php

use FOS\RestBundle\Controller\Annotations\RequestParam;

class MultiLineOk
{
    /**
     * @RequestParam(
     *     name="multiLineOk",
     *     requirements="\d+",
     *     strict=true,
     *     description="Useless request parameter"
     * )
     */
    private $multiLineOk;
}
