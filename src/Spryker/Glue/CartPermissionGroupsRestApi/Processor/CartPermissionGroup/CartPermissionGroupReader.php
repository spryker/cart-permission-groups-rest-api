<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CartPermissionGroupReader implements CartPermissionGroupReaderInterface
{
    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface
     */
    protected $cartPermissionGroupResponseBuilder;

    public function __construct(
        CartPermissionGroupsRestApiToSharedCartClientInterface $sharedCartClient,
        CartPermissionGroupResponseBuilderInterface $cartPermissionGroupResponseBuilder
    ) {
        $this->sharedCartClient = $sharedCartClient;
        $this->cartPermissionGroupResponseBuilder = $cartPermissionGroupResponseBuilder;
    }

    public function getCartPermissionGroupList(): RestResponseInterface
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->getQuotePermissionGroupList(
            new QuotePermissionGroupCriteriaFilterTransfer(),
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return $this->cartPermissionGroupResponseBuilder->createEmptyCartPermissionGroupsResponse();
        }

        return $this->cartPermissionGroupResponseBuilder->createCartPermissionGroupsCollectionResponse(
            $quotePermissionGroupResponseTransfer->getQuotePermissionGroups(),
        );
    }

    public function findCartPermissionGroupById(int $idCartPermissionGroup): RestResponseInterface
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->findQuotePermissionGroupById(
            (new QuotePermissionGroupTransfer())->setIdQuotePermissionGroup($idCartPermissionGroup),
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return $this->cartPermissionGroupResponseBuilder->createCartPermissionGroupNotFoundErrorResponse();
        }

        return $this->cartPermissionGroupResponseBuilder->createCartPermissionGroupsResponse(
            $quotePermissionGroupResponseTransfer->getQuotePermissionGroups()->offsetGet(0),
        );
    }
}
