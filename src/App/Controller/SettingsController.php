<?php

namespace App\Controller;

use App\Entity\SearchSetting;
use App\Exception\ActionFailedException;
use App\Util\Json;
use Doctrine\DBAL\Query\QueryBuilder;
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

    /**
     * Action for fetching search settings for action.
     *
     * @param string  $action
     * @param Request $request
     *
     * @Route("search/{action}")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getSearchSettingsAction($action, Request $request)
    {
        $query = $this
            ->manager()
            ->createQueryBuilder();

        /** @var QueryBuilder $query */
        $data = $query->select('partial s.{id, name, setting}')
            ->from('App:SearchSetting', 's')
            ->where('s.user = :id AND s.module = :module')
            ->setParameter(':id', $this->getUserEntity()->getId())
            ->setParameter(':module', $action)
            ->getQuery()
            ->getArrayResult();

        foreach ($data as &$item) {
            $item['setting'] = Json::decode($item['setting']);
        }

        return new JsonResponse($data);
    }

    /**
     * Action for saving search settings for action.
     *
     * @param string  $action
     * @param Request $request
     *
     * @Route("search/{action}/save")
     * @Method("POST")
     *
     * @return Response
     */
    public function saveSearchSettingsAction($action, Request $request)
    {
        list($name, $settings, $id) = $this->mapFromRequest(['name', 'setting', 'id']);

        if (!is_null($id)) {
            $setting = $this
                ->manager()
                ->getRepository('App:SearchSetting')
                ->find($id);
        } else {
            $setting = new SearchSetting();
        }

        $setting
            ->setName($name)
            ->setSetting(Json::encode($settings))
            ->setModule($action)
            ->setUser($this->getUserEntity());

        $this->manager()->persist($setting);
        $this->manager()->flush();

        if (!$this->manager()->contains($setting)) {
            throw new ActionFailedException('save');
        }

        $data = $this->serializer->toArray($setting);
        $data['setting'] = Json::decode($data['setting']);

        return new JsonResponse($data);
    }

    /**
     * Action for removing saved settings.
     *
     * @Route("search/{action}/delete")
     * @Method("POST")
     *
     * @param string  $action
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function removeSavedSettingsAction($action, Request $request)
    {
        $settings = $request->get('settings', []);

        $settingObjects = $this
            ->manager()
            ->getRepository('App:SearchSetting')
            ->findBy(
                [
                    'user' => $this->getUserEntity()->getId(),
                    'id' => $settings
                ]
            );

        foreach ($settingObjects as $settingObject) {
            $this->manager()->remove($settingObject);
        }

        $this->manager()->flush();

        return new JsonResponse('OK');
    }
}
