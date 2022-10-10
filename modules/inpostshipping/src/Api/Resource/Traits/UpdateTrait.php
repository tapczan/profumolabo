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
trait UpdateTrait
{
    /**
     * Update an ApiResource by id using an array of attributes.
     *
     * @param int|string $id
     * @param array $attributes
     * @param array $options
     *
     * @return static
     */
    public static function update($id, array $attributes, array $options = [])
    {
        $instance = static::cast(array_merge($attributes, [
            static::getIdField() => $id,
        ]));

        return $instance->saveChanges($options);
    }

    /**
     * Update an ApiResource using the instance attributes.
     *
     * @param array $options
     *
     * @return $this
     */
    public function saveChanges(array $options = [])
    {
        $response = $this->getRequestFactory()
            ->createRequest('PUT', static::getResourcePath())
            ->setPathParams([static::getIdField() => $this->getId()])
            ->setJson($this->toArray())
            ->setOptions($options)
            ->send();

        return $this->mergeAttributes(is_null($attributes = $response->json()) ? [] : $attributes);
    }
}
