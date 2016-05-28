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

namespace CloudCreativity\JsonApi\Validators;

use CloudCreativity\JsonApi\Contracts\Object\DocumentInterface;
use CloudCreativity\JsonApi\Contracts\Validators\DocumentValidatorInterface;
use CloudCreativity\JsonApi\Contracts\Validators\ResourceValidatorInterface;
use CloudCreativity\JsonApi\Contracts\Validators\ValidationMessageFactoryInterface;
use CloudCreativity\JsonApi\Validators\ValidationKeys as Keys;

class ResourceDocumentValidator extends AbstractValidator implements DocumentValidatorInterface
{

    /**
     * @var ResourceValidatorInterface
     */
    private $resourceValidator;

    /**
     * ResourceDocumentValidator constructor.
     * @param ValidationMessageFactoryInterface $messages
     * @param ResourceValidatorInterface $validator
     */
    public function __construct(
        ValidationMessageFactoryInterface $messages,
        ResourceValidatorInterface $validator
    ) {
        parent::__construct($messages);
        $this->resourceValidator = $validator;
    }

    /**
     * @param DocumentInterface $document
     * @return bool
     */
    public function isValid(DocumentInterface $document)
    {
        $this->reset();

        if (!$document->has(DocumentInterface::DATA)) {
            $this->addDataError(
                Keys::MEMBER_REQUIRED,
                [':member' => DocumentInterface::DATA]
            );
            return false;
        }

        $data = $document->get(DocumentInterface::DATA);

        if (!is_object($data)) {
            $this->addDataError(
                Keys::MEMBER_MUST_BE_OBJECT,
                [':member' => DocumentInterface::DATA]
            );
            return false;
        }

        if (!$this->resourceValidator->isValid($document->resource())) {
            $this->addErrors($this->resourceValidator->errors());
            return false;
        }

        return true;
    }
}
