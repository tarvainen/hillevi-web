<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle all application settings CRUD operations.
 *
 * @Route("api/settings/")
 *
 * @package App\Controller
 */
class SettingsController extends CController
{
    /**
     * Returns the users custom application settings.
     *
     * @Permission
     *
     * @Route("all")
     * @Method("POST")
     *
     * @return Response
     */
    public function getAppSettingsAction()
    {
        $settings = $this->getUserEntity()->getSettings();

        return new Response($this->serializer->serialize($settings, 'json'));
    }

    /**
     * Saves the app setting data for the user.
     *
     * @Permission
     *
     * @Route("save")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function saveAppSettingsAction(Request $request)
    {
        $data = $request->request->all();

        $em = $this->getDoctrine()->getManager();

        $settings = $this->getUserEntity()->getSettings();

        $settings->fromArray($data);

        $em->persist($settings);
        $em->flush();

        return new JsonResponse('OK');
    }
}
