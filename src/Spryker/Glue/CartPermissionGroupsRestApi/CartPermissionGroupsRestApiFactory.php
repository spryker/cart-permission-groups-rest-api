<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi;

use Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\CartPermissionGroupReader;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\CartPermissionGroupReaderInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship\CartPermissionGroupByQuoteResourceRelationshipExpander;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship\CartPermissionGroupByShareDetailResourceRelationshipExpander;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship\CartPermissionGroupResourceRelationshipExpanderInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapper;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilder;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CartPermissionGroupsRestApiFactory extends AbstractFactory
{
    public function createCartPermissionGroupByQuoteResourceRelationshipExpander(): CartPermissionGroupResourceRelationshipExpanderInterface
    {
        return new CartPermissionGroupByQuoteResourceRelationshipExpander(
            $this->createCartPermissionGroupResponseBuilder(),
        );
    }

    public function createCartPermissionGroupMapper(): CartPermissionGroupMapperInterface
    {
        return new CartPermissionGroupMapper();
    }

    public function createCartPermissionGroupResponseBuilder(): CartPermissionGroupResponseBuilderInterface
    {
        return new CartPermissionGroupResponseBuilder(
            $this->createCartPermissionGroupMapper(),
            $this->getResourceBuilder(),
        );
    }

    public function createCartPermissionGroupReader(): CartPermissionGroupReaderInterface
    {
        return new CartPermissionGroupReader(
            $this->getSharedCartClient(),
            $this->createCartPermissionGroupResponseBuilder(),
        );
    }

    public function createCartPermissionGroupByShareDetailResourceRelationshipExpander(): CartPermissionGroupResourceRelationshipExpanderInterface
    {
        return new CartPermissionGroupByShareDetailResourceRelationshipExpander(
            $this->createCartPermissionGroupResponseBuilder(),
        );
    }

    public function getSharedCartClient(): CartPermissionGroupsRestApiToSharedCartClientInterface
    {
        return $this->getProvidedDependency(CartPermissionGroupsRestApiDependencyProvider::CLIENT_SHARED_CART);
    }
}
