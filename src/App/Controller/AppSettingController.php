<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;

/**
 * Controller for the app settings.
 *
 * @package App\Controller
 *
 * @Route("api/appsetting/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class AppSettingController extends CController
{
}