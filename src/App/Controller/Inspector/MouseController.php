<?php

namespace App\Controller\Inspector;

use App\Controller\CController;
use App\Util\DateUtil;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use App\Annotation\Permission;

/**
 * Controller for mouse inspection data handlings.
 *
 * @package App\Controller\Inspector
 *
 * @Route("api/inspector/mouse/")
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class MouseController extends CController
{
    /**
     * Action to fetch mouse patch chart.
     *
     * @Permission
     *
     * @Route("path")
     * @Method("POST")
     *
     * @return Response
     */
    public function getMouseChartAction()
    {
        list($startTime, $endTime) = $this->mapFromRequest(['startDate', 'endDate']);

        if (!isset($startTime)) {
            $startTime = new \DateTime('1990-01-01');
        } else {
            $startTime = new \DateTime($startTime);
        }

        if (!isset($endTime)) {
            $endTime = new \DateTime('2036-01-01');
        } else {
            $endTime = new \DateTime($endTime);
        }

        $dql = '
            SELECT
              m.path
            FROM App:MousePath m
            WHERE
              m.startTime >= :startTime AND
              m.endTime <= :endTime AND
              m.user = :userId
        ';

        /** @var Query $query */
        $query = $this
            ->manager()
            ->createQuery($dql)
            ->setParameter('startTime', $startTime->format(DateUtil::DATETIME_DB))
            ->setParameter('endTime', $endTime->format(DateUtil::DATETIME_DB))
            ->setParameter('userId', $this->getUserEntity()->getId())
        ;

        $data = $query->getArrayResult();

        // TODO: Fetch from somewhere
        $quality = 1;
        $width = 1920 * $quality;
        $height = 1080 * $quality;

        $image = imagecreatetruecolor($width, $height);
        $background = imagecolorallocate($image, 255, 255, 255);
        $lineColor = imagecolorallocate($image, 50, 50, 50);

        // Fill the image with white color
        imagefill($image, 0, 0, $background);

        $lastCoordinate = null;

        foreach ($data as $item) {
            $coordinates = array_filter(explode(';', $item['path']));

            foreach ($coordinates as $coordinate) {
                $coordinate = explode('|', $coordinate);

                if (empty($coordinate)) {
                    continue;
                }

                if (is_null($lastCoordinate)) {
                    $lastCoordinate = $coordinate;
                    continue;
                }

                // Draw line through last two coordinates
                imageline(
                    $image,
                    $lastCoordinate[0] * $quality,
                    $lastCoordinate[1] * $quality,
                    $coordinate[0] * $quality,
                    $coordinate[1] * $quality,
                    $lineColor
                );

                $lastCoordinate = $coordinate;
            }
        }

        // Write to the output
        ob_start();
        imagejpeg($image, NULL, 100);

        // Get contents
        $data = ob_get_clean();

        // Convert image to base64
        $base64 = sprintf(
            'data:image/jpeg;base64,%1$s',
            /** 1 */ base64_encode($data)
        );

        return new Response($base64, 200);
    }
}