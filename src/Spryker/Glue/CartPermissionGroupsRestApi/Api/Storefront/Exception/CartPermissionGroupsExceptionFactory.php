<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CartPermissionGroupsRestApi\Api\Storefront\Exception;

use Spryker\ApiPlatform\Exception\GlueApiException;
use Spryker\Glue\CartPermissionGroupsRestApi\CartPermissionGroupsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class CartPermissionGroupsExceptionFactory
{
    public function createCartPermissionGroupNotFoundException(): GlueApiException
    {
        return new GlueApiException(
            Response::HTTP_NOT_FOUND,
            CartPermissionGroupsRestApiConfig::RESPONSE_CODE_CART_PERMISSION_GROUP_NOT_FOUND,
            CartPermissionGroupsRestApiConfig::RESPONSE_DETAIL_CART_PERMISSION_GROUP_NOT_FOUND,
        );
    }
}
