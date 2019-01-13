<?php
/**
 * Created by PhpStorm.
 * User: orhanbhr
 * Date: 2019-01-13
 * Time: 19:34
 */

namespace App;

class CloudFlareAPIClassV4 {
    private $apiUrl = 'https://api.cloudflare.com/client/v4/';
    private $email = null;
    private $key = null;

    /**
     * CloudFlareAPIClassV4 constructor.
     * @param $cloudFlareEmail
     * @param $cloudFlareKey
     */
    public function __construct($cloudFlareEmail, $cloudFlareKey)
    {
        $this->email = $cloudFlareEmail;
        $this->key = $cloudFlareKey;
    }

    /**
     * Add Domain on CloudFlare
     * @param $name
     * @param bool $jumpStart
     * @return object
     */
    public function addDomain($name, $jumpStart = false)
    {
        $request = $this->connection(
            'POST',
            'zones',
            [
                'name' => $name,
                'jump_start' => $jumpStart
            ]
        );

        return $request;
    }

    /**
     * Add DNS Record on CloudFlare Domain
     * @param $zoneId
     * @param $type
     * @param $name
     * @param $content
     * @param int $ttl
     * @param bool $proxied
     * @param string $priority
     * @return object
     */
    public function addDns($zoneId, $type, $name, $content, $ttl = 0, $proxied = true, $priority = '')
    {
        $data = [
            'type' => $type,
            'name' => $name,
            'content' => $content,
            'proxied' => $proxied
        ];

        if ($ttl > 0) {
            $data['ttl'] = $ttl;
        }

        if (!empty($priority)) {
            $data['priority'] = (int)$priority;
        }

        $request = $this->connection(
            'POST',
            'zones/' . $zoneId . '/dns_records',
            $data
        );

        return $request;
    }

    /**
     * Change Cache Level on CloudFlare Domain
     * @param $zoneId
     * @param string $value
     * @return object
     */
    public function changeCacheLevel($zoneId, $value = 'aggressive')
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/cache_level',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * Change Browser Cache TTL on CloudFlare Domain
     * @param $zoneId
     * @param int $value
     * @return object
     */
    public function changeBrowserCacheTtl($zoneId, $value = 14400)
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/browser_cache_ttl',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * Change Always Online on CloudFlare Domain
     * @param $zoneId
     * @param string $value
     * @return object
     */
    public function changeAlwaysOnline($zoneId, $value = 'on')
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/always_online',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * Change Development Mode on CloudFlare Domain
     * @param $zoneId
     * @param string $value
     * @return object
     */
    public function changeDevelopmentMode($zoneId, $value = 'off')
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/development_mode',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * Change Automatic Https Rewrites on CloudFlare Domain
     * @param $zoneId
     * @param string $value
     * @return object
     */
    public function changeAutomaticHttpsRewrites($zoneId, $value = 'off')
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/automatic_https_rewrites',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * Change Always Use Https on CloudFlare Domain
     * @param $zoneId
     * @param string $value
     * @return object
     */
    public function changeAlwaysUseHttps($zoneId, $value = 'off')
    {
        $request = $this->connection(
            'PATCH',
            'zones/' . $zoneId . '/settings/always_use_https',
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * cURL Connection Method
     * @param string $method
     * @param $url
     * @param array $data
     * @return object
     */
    private function connection($method, $url, $data = [])
    {
        $ch = curl_init();
        $headers = array(
            'X-Auth-Email: ' . $this->email,
            'X-Auth-Key: ' . $this->key,
            'Content-Type: application/json',
        );
        $json = json_encode($data);
        curl_setopt($ch,CURLOPT_URL,$this->apiUrl . $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch,CURLOPT_VERBOSE,0);
        curl_setopt($ch,CURLOPT_FORBID_REUSE,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);

        if ($method != 'GET')
            curl_setopt($ch,CURLOPT_POSTFIELDS, $json);

        if ($method == 'DELETE')
            curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'DELETE');

        if ($method == 'PATCH')
            curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'PATCH');

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }
}
