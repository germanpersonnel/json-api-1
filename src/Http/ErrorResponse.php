<?php

/**
 * Copyright 2016 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CloudCreativity\JsonApi\Http;

use CloudCreativity\JsonApi\Contracts\Http\ErrorResponseInterface;
use CloudCreativity\JsonApi\Exceptions\MutableErrorCollection;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use Neomerx\JsonApi\Exceptions\ErrorCollection;

/**
 * Class ErrorResponse
 * @package CloudCreativity\JsonApi
 */
class ErrorResponse implements ErrorResponseInterface
{

    /**
     * @var ErrorInterface|ErrorInterface[]|ErrorCollection
     */
    private $errors;

    /**
     * @var int
     */
    private $defaultHttpCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * ErrorResponse constructor.
     * @param ErrorInterface|ErrorInterface[]|ErrorCollection $errors
     * @param int|null $defaultHttpCode
     * @param array $headers
     */
    public function __construct($errors, $defaultHttpCode = null, array $headers = [])
    {
        $this->errors = $errors;
        $this->defaultHttpCode = $defaultHttpCode;
        $this->headers = $headers;
    }

    /**
     * @inheritdoc
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @inheritdoc
     */
    public function getHttpCode()
    {
        $errors = MutableErrorCollection::cast($this->errors);

        return $errors->getHttpStatus($this->defaultHttpCode);
    }

    /**
     * @inheritdoc
     */
    public function getHeaders()
    {
        return $this->headers;
    }

}
