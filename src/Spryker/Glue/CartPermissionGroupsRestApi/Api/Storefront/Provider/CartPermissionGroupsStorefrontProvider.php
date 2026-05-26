<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace Spryker\Glue\CartPermissionGroupsRestApi\Api\Storefront\Provider;

use Generated\Api\Storefront\CartPermissionGroupsStorefrontResource;
use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\ApiPlatform\State\Provider\AbstractStorefrontProvider;
use Spryker\Client\SharedCart\SharedCartClientInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Api\Storefront\Exception\CartPermissionGroupsExceptionFactory;
use Spryker\Service\Serializer\SerializerServiceInterface;

class CartPermissionGroupsStorefrontProvider extends AbstractStorefrontProvider
{
    protected const string KEY_ID_QUOTE_PERMISSION_GROUP = 'idQuotePermissionGroup';

    public function __construct(
        protected SharedCartClientInterface $sharedCartClient,
        protected SerializerServiceInterface $serializer,
        protected CartPermissionGroupsExceptionFactory $exceptionFactory,
    ) {
    }

    /**
     * @return list<\Generated\Api\Storefront\CartPermissionGroupsStorefrontResource>
     */
    protected function provideCollection(): array
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->getQuotePermissionGroupList(
            new QuotePermissionGroupCriteriaFilterTransfer(),
        );

        $resources = [];

        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            $resources[] = $this->serializer->denormalize(
                $this->prepareResourceData($quotePermissionGroupTransfer),
                CartPermissionGroupsStorefrontResource::class,
            );
        }

        return $resources;
    }

    /**
     * @throws \Spryker\ApiPlatform\Exception\GlueApiException
     *
     * @return \Generated\Api\Storefront\CartPermissionGroupsStorefrontResource|null
     */
    protected function provideItem(): ?object
    {
        $idQuotePermissionGroup = (int)($this->getUriVariables()[static::KEY_ID_QUOTE_PERMISSION_GROUP] ?? 0);

        if ($idQuotePermissionGroup === 0) {
            throw $this->exceptionFactory->createCartPermissionGroupNotFoundException();
        }

        $quotePermissionGroupTransfer = (new QuotePermissionGroupTransfer())
            ->setIdQuotePermissionGroup($idQuotePermissionGroup);

        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->findQuotePermissionGroupById(
            $quotePermissionGroupTransfer,
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            throw $this->exceptionFactory->createCartPermissionGroupNotFoundException();
        }

        foreach ($quotePermissionGroupResponseTransfer->getQuotePermissionGroups() as $quotePermissionGroupTransfer) {
            return $this->serializer->denormalize(
                $this->prepareResourceData($quotePermissionGroupTransfer),
                CartPermissionGroupsStorefrontResource::class,
            );
        }

        throw $this->exceptionFactory->createCartPermissionGroupNotFoundException();
    }

    /**
     * @return array<string, mixed>
     */
    protected function prepareResourceData(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): array
    {
        return $quotePermissionGroupTransfer->toArray(true, true);
    }
}
