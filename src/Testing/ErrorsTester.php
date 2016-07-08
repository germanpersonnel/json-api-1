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

namespace CloudCreativity\JsonApi\Testing;

use Generator;
use Neomerx\JsonApi\Contracts\Document\ErrorInterface;
use PHPUnit_Framework_Assert as PHPUnit;

/**
 * Class ErrorsTester
 * @package CloudCreativity\JsonApi
 */
class ErrorsTester extends AbstractTraversableTester
{

    /**
     * @var array
     */
    private $errors;

    /**
     * ErrorsTester constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @return Generator
     */
    public function getIterator()
    {
        foreach ($this->errors as $index => $error) {

            $tester = ($error instanceof ErrorInterface) ?
                new ErrorTester($error, $index) : ErrorTester::create((array) $error, $index);

            yield $index => $tester;
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->errors);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->errors);
    }

    /**
     * Assert that the supplied code(s) exist somewhere in the error collection.
     *
     * @param string|string[] $codes
     * @return $this
     */
    public function assertCodes($codes)
    {
        $actual = $this->map(function (ErrorTester $error) {
            return $error->getError()->getCode();
        });

        foreach ((array) $codes as $expected) {
            PHPUnit::assertContains($expected, $actual, sprintf(
                'Error code %s not found in codes: [%s]', $expected, implode(',', $actual)
            ));
        }

        return $this;
    }

    /**
     * Assert that the supplied status(es) exist somewhere in the error collection.
     *
     * @param string|string[] $statuses
     * @return $this
     */
    public function assertStatuses($statuses)
    {
        $actual = $this->map(function (ErrorTester $error) {
            return $error->getError()->getStatus();
        });

        foreach ((array) $statuses as $expected) {
            PHPUnit::assertContains($expected, $actual, sprintf(
                'Error status %s not found in statuses: [%s]', $expected, implode(',', $actual)
            ));
        }

        return $this;
    }

    /**
     * Assert that the supplied pointer(s) exist somewhere in the error collection.
     *
     * @param string|string[] $pointers
     *      the pointer(s) that are expected.
     * @return $this
     */
    public function assertPointers($pointers)
    {
        $actual = $this->map(function (ErrorTester $error) {
            return $error->getError()->getSourcePointer();
        });

        foreach ((array) $pointers as $expected) {
            PHPUnit::assertContains($expected, $actual, sprintf(
                'Error pointer %s not found in pointers: [%s]', $expected, implode(',', $actual)
            ));
        }

        return $this;
    }

    /**
     * Assert that the supplied parameter(s) exist somewhere in the error collection.
     *
     * @param string|string[] $parameters
     *      the pointer(s) that are expected.
     * @return $this
     */
    public function assertParameters($parameters)
    {
        $actual = $this->map(function (ErrorTester $error) {
            return $error->getError()->getSourceParameter();
        });

        foreach ((array) $parameters as $expected) {
            PHPUnit::assertContains($expected, $actual, sprintf(
                'Error parameter %s not found in parameters: [%s]', $expected, implode(',', $actual)
            ));
        }

        return $this;
    }
}
