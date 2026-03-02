<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;

class CartPermissionGroupMapper implements CartPermissionGroupMapperInterface
{
    public function mapQuotePermissionGroupTransferToRestCartPermissionGroupsAttributesTransfer(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer,
        RestCartPermissionGroupsAttributesTransfer $restCartPermissionGroupsAttributesTransfer
    ): RestCartPermissionGroupsAttributesTransfer {
        return $restCartPermissionGroupsAttributesTransfer->fromArray($quotePermissionGroupTransfer->toArray(), true);
    }
}
