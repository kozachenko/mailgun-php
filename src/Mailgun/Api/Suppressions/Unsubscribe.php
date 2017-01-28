<?php

/*
 * Copyright (C) 2013-2016 Mailgun
 *
 * This software may be modified and distributed under the terms
 * of the MIT license. See the LICENSE file for details.
 */

namespace Mailgun\Api;

use Mailgun\Assert;
use Mailgun\Resource\Api\Suppressions\Unsubscribe\CreateResponse;
use Mailgun\Resource\Api\Suppressions\Unsubscribe\DeleteResponse;
use Mailgun\Resource\Api\Suppressions\Unsubscribe\IndexResponse;
use Mailgun\Resource\Api\Suppressions\Unsubscribe\ShowResponse;

/**
 * @author Sean Johnson <sean@mailgun.com>
 */
class Unsubscribe extends HttpApi
{
    use Pagination;

    /**
     * @return string
     */
    protected function getPaginationResponse()
    {
        return IndexResponse::class;
    }

    /**
     * @param string $domain
     *
     * @return IndexResponse
     */
    public function index($domain)
    {
        Assert::notEmpty($domain);
        Assert::range($limit, 1, 10000, 'Limit parameter must be between 1 and 10000');

        $params = [
            'limit' => $limit,
        ];

        $response = $this->httpGet(sprintf('/v3/%s/unsubscribes', $domain), $params);

        return $this->safeDeserialize($response, IndexResponse::class);
    }

    /**
     * @param string $domain
     * @param string $address
     *
     * @return ShowResponse
     */
    public function show($domain, $address)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);
        $response = $this->httpGet(sprintf('/v3/%s/unsubscribes/%s', $domain, $address));

        return $this->safeDeserialize($response, ShowResponse::class);
    }

    /**
     * @param string         $domain
     * @param string         $address
     * @param string|null    $code      optional
     * @param string|null    $error     optional
     * @param \DateTime|null $createdAt optional
     *
     * @return CreateResponse
     */
    public function create($domain, $address, $code = null, $error = null, $createdAt = null)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);

        $params = [
            'address' => $address,
        ];

        foreach (['code' => $code, 'error' => $error, 'created_at' => $createdAt] as $k => $v) {
            if (!empty($v)) {
                $params[$k] = $v;
            }
        }

        $response = $this->httpPost(sprintf('/v3/%s/unsubscribes', $domain), $params);

        return $this->safeDeserialize($response, CreateResponse::class);
    }

    /**
     * @param string $domain
     * @param string $address
     *
     * @return DeleteResponse
     */
    public function delete($domain, $address)
    {
        Assert::notEmpty($domain);
        Assert::notEmpty($address);
        $response = $this->httpDelete(sprintf('/v3/%s/unsubscribes/%s', $domain, $address));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }

    /**
     * @param string $domain
     *
     * @return DeleteResponse
     */
    public function deleteAll($domain)
    {
        Assert::notEmpty($domain);
        $response = $this->httpDelete(sprintf('/v3/%s/unsubscribes', $domain));

        return $this->safeDeserialize($response, DeleteResponse::class);
    }
}