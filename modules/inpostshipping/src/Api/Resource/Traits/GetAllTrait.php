<?php
/**
 * Copyright 2021-2022 InPost S.A.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the EUPL-1.2 or later.
 * You may not use this work except in compliance with the Licence.
 *
 * You may obtain a copy of the Licence at:
 * https://joinup.ec.europa.eu/software/page/eupl
 * It is also bundled with this package in the file LICENSE.txt
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the Licence is distributed on an AS IS basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions
 * and limitations under the Licence.
 *
 * @author    InPost S.A.
 * @copyright 2021-2022 InPost S.A.
 * @license   https://joinup.ec.europa.eu/software/page/eupl
 */

namespace InPost\Shipping\Api\Resource\Traits;

use InPost\Shipping\Api\Resource\ApiResource;

/**
 * @mixin ApiResource
 */
trait GetAllTrait
{
    /** @return static[] */
    public static function getAll(array $options = [])
    {
        $instance = static::cast([]);

        $response = $instance->getRequestFactory()
            ->createRequest('GET', static::getBasePath(), $options)
            ->send();

        return static::castMany($response->json());
    }
}
