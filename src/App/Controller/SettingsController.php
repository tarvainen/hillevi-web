<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
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
    public function getAppSettings()
    {
        $settings = $this->getUserEntity()->getSettings();

        return new Response($this->serializer->serialize($settings, 'json'));
    }
}
