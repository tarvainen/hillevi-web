<?php

namespace App\Controller\AppSetting;
use App\Controller\CController;
use App\Entity\Settings;
use App\Entity\User;
use App\Util\Json;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\SerializationContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for the app user settings.
 *
 * @package App\Controller
 *
 * @Route("api/appsetting/users/")
 * @Method("POST")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class UsersController extends CController
{
    /**
     * Action to find all users.
     *
     * @Permission
     *
     * @Route("find")
     * @Method("POST")
     *
     * @return Response
     */
    public function findAction()
    {
        $data = $this
            ->manager()
            ->getRepository('App:User')
            ->findAll();

        return new Response(
            $this->serializer->serialize(
                $data, 'json', SerializationContext::create()->setGroups(['list'])
            )
        );
    }

    /**
     * Action to save user.
     *
     * @Permission
     *
     * @Route("save")
     * @Method("POST")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function saveAction(Request $request)
    {
        $json = Json::encode($request->request->all());

        /** @var User $user */
        $user = $this->serializer->deserialize($json, 'App\Entity\User', 'json');

        // Generate default password out of username and a current year
        $defaultPassword = $user->getUsername() . date('Y');

        // Set password and api key
        $user->setPassword($defaultPassword);
        $user->refreshApiKey();

        $settings = new Settings();
        $this->manager()->persist($settings);

        $user->setSettings($settings);
        $user->setRights(new ArrayCollection());

        // Save user
        $this->manager()->persist($user);
        $this->manager()->flush();

        return new Response(
            $this->serializer->serialize($user, 'json')
        );
    }
}