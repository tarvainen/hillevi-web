<?php

namespace App\Controller;

use Cron\CronBundle\Entity\CronJob;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to handle cron actions. Here user may add and modify the application level cron tasks.
 *
 * @package App\Controller
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class CronController extends CController
{
    /**
     * Returns a list of all available crons.
     *
     * @Route("api/cron/all")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function getAllCronAction()
    {
        $cron = $this
            ->getDoctrine()
            ->getRepository('Cron\CronBundle\Entity\CronJob')
            ->findAll();

        $serializer = SerializerBuilder::create()->build();

        return new Response($serializer->serialize($cron, 'json'));
    }

    /**
     * Adds a new cron to the scheduler.
     *
     * TODO: Improve this!! This is just a test.
     *
     * @Route("api/cron/add")
     * @Method("POST")
     *
     * @return JsonResponse
     */
    public function addCronAction()
    {
        $cron = new CronJob();

        $cron->setName('test');
        $cron->setDescription('test');
        $cron->setCommand('date >> date.txt');
        $cron->setEnabled(true);
        $cron->setSchedule('* * * * *');

        $em = $this->getDoctrine()->getManager();

        $em->persist($cron);
        $em->flush();

        return new JsonResponse('OK');
    }
}