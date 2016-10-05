<?php

namespace App\Util;

/**
 * Wrapper for the basic url usage.
 *
 * @package App\Util
 *
 * @author Atte Tarvainen <atte.tarvainen@pp1.inet.fi>
 */
class Curl
{
    /**
     * Utilizes the basic curl functionality.
     *
     * @param string $url
     * @param string $contentType
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public static function read($url, $contentType = ContentType::TYPE_JSON)
    {
        $header = [
            sprintf(
                'Content-type: %1$s',
                /** 1 */ $contentType
            )
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        $info = curl_getinfo($ch);

        $status = $info['http_code'];

        // Check if the status is 200 and only then return the data fetched
        if ($status === 200) { // OK
            return $response;
        }

        throw new \Exception('Failed to fetch data from the url given.', $status);
    }
}
