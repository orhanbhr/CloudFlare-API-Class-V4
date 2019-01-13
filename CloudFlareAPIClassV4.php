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
        $url = 'zones';
        $request = $this->postSpider(
            $url,
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

        $url = 'zones/' . $zoneId . '/dns_records';
        $request = $this->postSpider(
            $url,
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
        $url = 'zones/' . $zoneId . '/settings/cache_level';
        $request = $this->postSpider(
            $url,
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
        $url = 'zones/' . $zoneId . '/settings/browser_cache_ttl';
        $request = $this->postSpider(
            $url,
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
        $url = 'zones/' . $zoneId . '/settings/always_online';
        $request = $this->postSpider(
            $url,
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
        $url = 'zones/' . $zoneId . '/settings/development_mode';
        $request = $this->postSpider(
            $url,
            [
                'value' => $value
            ]
        );

        return $request;
    }

    /**
     * cURL Connection for GET Method
     * @param $url
     * @return object
     */
    private function getSpider($url)
    {
        $ch = curl_init();
        $headers = array(
            'X-Auth-Email: ' . $this->email,
            'X-Auth-Key: ' . $this->key,
            'Content-Type: application/json',
        );
        curl_setopt($ch,CURLOPT_URL,$this->apiUrl . $url);
        curl_setopt($ch,CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * cURL Connection for PATCH Method
     * @param $url
     * @param array $data
     * @return object
     */
    private function patchSpider($url, $data = [])
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
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'PATCH');
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * cURL Connection for POST Method
     * @param $url
     * @param array $data
     * @return object
     */
    private function postSpider($url, $data = [])
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
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * cURL Connection for DELETE Method
     * @param $url
     * @param array $data
     * @return object
     */
    private function deleteSpider($url, $data = [])
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
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'DELETE');
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }
}
