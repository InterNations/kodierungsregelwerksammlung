<?php

use FOS\RestBundle\Controller\Annotations\RequestParam;

class OneLineOk
{
    /**
     * @RequestParam(name="ok", requirements="\d+", strict=true, description="Useless request parameter")
     */
    private $ok;
}
