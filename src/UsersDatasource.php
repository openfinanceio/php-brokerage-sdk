<?php
namespace CFX\SDK\Brokerage;

/**
 * A mostly stub datasource used to prevent frivolous calls to the API for OAuth token objects that
 * we won't have permissions to retrieve.
 */
class UsersDatasource extends \CFX\Persistence\Rest\GenericDatasource
{
    public function getRelated($type, $id)
    {
        if ($type === 'oAuthTokens') {
            return new \CFX\JsonApi\ResourceCollection();
        }
        return parent::getRelated($type, $id);
    }
}

