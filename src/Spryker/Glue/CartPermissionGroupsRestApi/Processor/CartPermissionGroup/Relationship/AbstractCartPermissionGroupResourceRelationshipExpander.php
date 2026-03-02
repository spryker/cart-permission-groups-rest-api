<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

abstract class AbstractCartPermissionGroupResourceRelationshipExpander implements CartPermissionGroupResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface
     */
    protected $cartPermissionGroupResponseBuilder;

    public function __construct(CartPermissionGroupResponseBuilderInterface $cartPermissionGroupResponseBuilder)
    {
        $this->cartPermissionGroupResponseBuilder = $cartPermissionGroupResponseBuilder;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $quotePermissionGroupTransfer = $this->findQuotePermissionGroupTransferInPayload($resource);
            if (!$quotePermissionGroupTransfer) {
                continue;
            }

            $cartPermissionGroupRestResource = $this->cartPermissionGroupResponseBuilder
                ->createCartPermissionGroupsResource($quotePermissionGroupTransfer);

            $resource->addRelationship($cartPermissionGroupRestResource);
        }
    }

    abstract protected function findQuotePermissionGroupTransferInPayload(RestResourceInterface $resource): ?QuotePermissionGroupTransfer;
}
