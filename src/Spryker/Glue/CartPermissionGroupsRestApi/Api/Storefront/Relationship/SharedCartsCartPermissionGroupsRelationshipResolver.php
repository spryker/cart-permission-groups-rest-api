<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CartPermissionGroupsRestApi\Api\Storefront\Relationship;

use Generated\Api\Storefront\CartPermissionGroupsStorefrontResource;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\ApiPlatform\Relationship\AbstractRelationshipResolver;
use Spryker\ApiPlatform\Relationship\PerItemRelationshipResolverInterface;
use Spryker\Client\SharedCart\SharedCartClientInterface;
use Spryker\Service\Serializer\SerializerServiceInterface;

class SharedCartsCartPermissionGroupsRelationshipResolver extends AbstractRelationshipResolver implements PerItemRelationshipResolverInterface
{
    public function __construct(
        protected SharedCartClientInterface $sharedCartClient,
        protected SerializerServiceInterface $serializer,
    ) {
    }

    /**
     * @return array<\Generated\Api\Storefront\CartPermissionGroupsStorefrontResource>
     */
    protected function resolveRelationship(): array
    {
        $allResources = [];

        foreach ($this->resolvePerItem($this->getParentResources(), $this->context) as $resources) {
            array_push($allResources, ...$resources);
        }

        return $allResources;
    }

    /**
     * @param array<object> $parentResources
     * @param array<string, mixed> $context
     *
     * @return array<string, array<\Generated\Api\Storefront\CartPermissionGroupsStorefrontResource>>
     */
    public function resolvePerItem(array $parentResources, array $context): array
    {
        $result = [];

        /** @var array<int, \Generated\Api\Storefront\CartPermissionGroupsStorefrontResource|null> $cache */
        $cache = [];

        foreach ($parentResources as $parentResource) {
            $uuid = $parentResource->uuid ?? null;
            $idCartPermissionGroup = $parentResource->idCartPermissionGroup ?? null;

            if ($uuid === null || $idCartPermissionGroup === null) {
                continue;
            }

            $resource = $cache[$idCartPermissionGroup] ??= $this->findCartPermissionGroup($idCartPermissionGroup);

            $result[$uuid] = $resource !== null ? [$resource] : [];
        }

        return $result;
    }

    protected function findCartPermissionGroup(int $idQuotePermissionGroup): ?CartPermissionGroupsStorefrontResource
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->findQuotePermissionGroupById(
            (new QuotePermissionGroupTransfer())->setIdQuotePermissionGroup($idQuotePermissionGroup),
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return null;
        }

        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            return $this->serializer->denormalize(
                $quotePermissionGroupTransfer->toArray(true, true),
                CartPermissionGroupsStorefrontResource::class,
            );
        }

        return null;
    }
}
