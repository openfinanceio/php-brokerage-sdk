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
        if ($type === "otherEntities") {
            $acls = $this->context->aclEntries->get("actorType=users and actorId=$id and targetType=legal-entities");
            $entities = new \CFX\JsonApi\ResourceCollection();
            foreach($acls as $acl) {
                $entities[] = $acl->getTarget();
            }
            return $entities;
        }
        return parent::getRelated($type, $id);
    }
}

