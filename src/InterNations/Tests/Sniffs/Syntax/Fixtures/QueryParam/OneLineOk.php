<?php

use FOS\RestBundle\Controller\Annotations\QueryParam;

class OneLineOk
{
    /**
     * @QueryParam(name="ok", requirements="\d+", strict=true, description="Useless query parameter")
     */
    private $ok;
}
