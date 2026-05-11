<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CartPermissionGroupsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\CartPermissionGroupsStorefrontResource;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;

/**
 * Builds `CartPermissionGroups` sub-resources for a `Carts` parent. Mirrors the legacy
 * {@see \Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship\CartPermissionGroupByQuoteResourceRelationshipExpander}
 * by reading the per-share-detail `quotePermissionGroup` carried on the parent cart's
 * `shareDetails` array. Only the share detail matching the current viewer's company user
 * is exposed — same scoping the legacy {@see \Spryker\Zed\SharedCart\Business\QuoteCollectionExpander\SharedCartQuoteCollectionExpander}
 * applied via `QuotePermissionGroup` on the quote itself for collection lookups.
 */
class CartsCartPermissionGroupsRelationshipResolver extends AbstractRelationshipResolver
{
    /**
     * Returns one CartPermissionGroups resource per unique permission group referenced
     * by any of the cart's share details. Cart owner and shared-with viewer both see
     * the full set so the relationship matches the legacy expander output regardless
     * of which side issued the request.
     *
     * @return array<\Generated\Api\Storefront\CartPermissionGroupsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        $resources = [];
        $seen = [];

        foreach ($this->getParentResources() as $parent) {
            $shareDetails = $parent->shareDetails ?? [];

            foreach ($shareDetails as $shareDetailTransfer) {
                if (!$shareDetailTransfer instanceof ShareDetailTransfer) {
                    continue;
                }

                $quotePermissionGroupTransfer = $shareDetailTransfer->getQuotePermissionGroup();

                if ($quotePermissionGroupTransfer === null) {
                    continue;
                }

                $idQuotePermissionGroup = $quotePermissionGroupTransfer->getIdQuotePermissionGroup();

                if ($idQuotePermissionGroup === null || isset($seen[$idQuotePermissionGroup])) {
                    continue;
                }

                $seen[$idQuotePermissionGroup] = true;
                $resources[] = $this->mapQuotePermissionGroupToResource($quotePermissionGroupTransfer);
            }
        }

        return $resources;
    }

    protected function mapQuotePermissionGroupToResource(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer,
    ): CartPermissionGroupsStorefrontResource {
        $resource = new CartPermissionGroupsStorefrontResource();
        $resource->idQuotePermissionGroup = $quotePermissionGroupTransfer->getIdQuotePermissionGroup();
        $resource->name = $quotePermissionGroupTransfer->getName();
        $resource->isDefault = $quotePermissionGroupTransfer->getIsDefault();

        return $resource;
    }
}
