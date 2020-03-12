<?php
/**
 * Tafoya Ventures Cloud Management Platform
 *
 * Copyright (c) 2020, tafoyaventures.com
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or
 * without modification, are not permitted without specific
 * prior written permission.
 *
 * @copyright  Copyright (c) 2020, tafoyaventures.com.
 * @author     Brian Tafoya <btafoya@tafoyaventures.com>
 * @version    1.0
 * @license    MIT License
 */

/**
 * Based on https://www.virtualmin.com/documentation/developer/cli
 */

/**
 * Class VirtualMin
 */
class VirtualMin
{

    /**
     * @var
     */
    public $serverUrl, $serverUsername, $serverPassword;

    public $lastError, $lastErrorCode;

    /**
     * VirtualMin constructor.
     * @param $serverUrl
     * @param $serverUsername
     * @param $serverPassword
     */
    public function __construct($serverUrl, $serverUsername, $serverPassword)
    {
        $this->serverUrl = $serverUrl;
        $this->serverUsername = $serverUsername;
        $this->serverPassword = $serverPassword;
    }

    /**
     * @param $program
     * @param array $actionsArray
     * @return bool|mixed
     */
    public function action($program, Array $actionsArray)
    {
        $actions = http_build_query($actionsArray);
        $url = "virtual-server/remote.cgi?program=" . (string)$program . "&json=1" . ($actions?"&" . $actions:"");

        return $this->client($url);
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    private function client($url)
    {
        $client = new GuzzleHttp\Client(
            [
                'allow_redirects' => false,
                'timeout' => 60,
                'http_errors' => false,
                'verify' => false,
                'base_uri' => $this->validateUrl($this->serverUrl),
                'auth' => [
                    $this->serverUsername, $this->serverPassword
                ]
            ]
        );

        $res = $client->request('GET', $url);

        if ($res->getStatusCode() == 200) {
            return json_decode((string)$res->getBody(), true);
        } else {
            $this->lastError = $res->getReasonPhrase();
            $this->lastErrorCode = $res->getStatusCode();
            return false;
        }
    }

    /**
     * @param $url
     * @return string
     */
    private function validateUrl($url)
    {
        $parse_url = parse_url($url);
        if(!isset($parse_url['path'])) $parse_url['path'] = '/';
        return $url['scheme']."://".$parse_url['host'].$parse_url['path'].'?'.$parse_url['query'].'#'.$parse_url['fragment'];
    }
}