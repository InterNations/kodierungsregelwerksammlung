<?php
namespace InterNations\Bundle\WhateverBundle\Controller\Api\Whatever;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route(
 *     service="whatever"
 * )
 */
class InvalidMultiActionsController extends AbstractController
{
    /** @var FooSomething */
    private $fooSomething;

    public function __construct(FooSomething $fooSomething)
    {
        $this->fooSomething = $fooSomething;
    }

    /**
     *
     * @ApiDoc(
     *      description="Whatever",
     *      section="Whatever",
     *      output="InterNations\Bundle\WhateverBundle\DataTransfer\Api\WhateverData",
     *      statusCodes={
     *          200 = "Success",
     *          403 = "Access Denied"
     *      },
     *      deprecated=true
     * )
     *
     * @Rest\Get(
     *      "/api/whatever",
     *      name="whatever",
     *      requirements = {"whatever" = "\w+"}
     * )

     * @Rest\QueryParam(
     *     name="whatever",
     *     requirements="\w+",
     *     default=0,
     *     strict=true,
     *     description="Whatever"
     * )
     *
     * @Secure(roles="ROLE_USER")
     *
     * @param Request $request
     * @param Whatever $whatever
     * @return View
     */
    public function indexAction(Request $request, $whatever)
    {
        return $this->createView($whatever);
    }

    /**
     *
     * @ApiDoc(
     *      description="Whatever",
     *      section="Whatever",
     *      output="InterNations\Bundle\WhateverBundle\DataTransfer\Api\WhateverData",
     *      statusCodes={
     *          200 = "Success",
     *          403 = "Access Denied"
     *      },
     *      deprecated=true
     * )
     *
     * @Rest\Get(
     *      "/api/whatever",
     *      name="whatever",
     *      requirements = {"whatever" = "\w+"}
     * )

     * @Rest\QueryParam(
     *     name="whatever",
     *     requirements="\w+",
     *     default=0,
     *     strict=true,
     *     description="Whatever"
     * )
     *
     * @Secure(roles="ROLE_USER")
     *
     * @param Request $request
     * @param Whatever $whatever
     * @return View
     */
    public function getAction(Activity $activity, $attendanceId)
    {
        return $this->createView($whatever);
    }

    /**
     *
     * @ApiDoc(
     *      description="Whatever",
     *      section="Whatever",
     *      output="InterNations\Bundle\WhateverBundle\DataTransfer\Api\WhateverData",
     *      statusCodes={
     *          200 = "Success",
     *          403 = "Access Denied"
     *      },
     *      deprecated=true
     * )
     *
     * @Rest\Put(
     *      "/api/whatever",
     *      name="whatever",
     *      requirements = {"whatever" = "\w+"}
     * )

     * @Rest\QueryParam(
     *     name="whatever",
     *     requirements="\w+",
     *     default=0,
     *     strict=true,
     *     description="Whatever"
     * )
     *
     * @Secure(roles="ROLE_USER")
     *
     * @param Request $request
     * @param Whatever $whatever
     * @return View
     */
    public function putAction(Activity $activity, $whatever)
    {
        return $this->createView($whatever);
    }

    /**
     *
     * @ApiDoc(
     *      description="Whatever",
     *      section="Whatever",
     *      output="InterNations\Bundle\WhateverBundle\DataTransfer\Api\WhateverData",
     *      statusCodes={
     *          200 = "Success",
     *          403 = "Access Denied"
     *      },
     *      deprecated=true
     * )
     *
     * @Rest\Delete(
     *      "/api/whatever",
     *      name="whatever",
     *      requirements = {"whatever" = "\w+"}
     * )

     * @Rest\QueryParam(
     *     name="whatever",
     *     requirements="\w+",
     *     default=0,
     *     strict=true,
     *     description="Whatever"
     * )
     *
     * @Secure(roles="ROLE_USER")
     *
     * @param Request $request
     * @param Whatever $whatever
     * @return View
     */
    public function deleteAction(Activity $activity, $whatever)
    {
        return $this->createView($whatever);
    }
}
