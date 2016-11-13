<?php

namespace App\Controller;

use App\Exception\ActionFailedException;
use App\Exception\NotFoundException;
use Cron\CronBundle\Entity\CronJob;
use JMS\Serializer\SerializerBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;
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
     * @Permission("cron:read")
     *
     * @Route("api/cron/all")
     * @Method("POST")
     *
     * @throws NotFoundException
     *
     * @return Response
     */
    public function getAllCronAction()
    {
        $cron = $this
            ->getDoctrine()
            ->getRepository('Cron\CronBundle\Entity\CronJob')
            ->findAll();

        if (!$cron) {
            throw new NotFoundException('cron');
        }

        $serializer = SerializerBuilder::create()->build();

        return new Response($serializer->serialize($cron, 'json'));
    }

    /**
     * Adds a new cron to the scheduler.
     *
     * @Permission("cron:write")
     *
     * @Route("api/cron/add")
     * @Method("POST")
     *
     * @throws ActionFailedException
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

        if (!$em->contains($cron)) {
            throw new ActionFailedException('save');
        }

        return new JsonResponse('OK');
    }
}