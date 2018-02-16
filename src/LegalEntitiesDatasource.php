<?php
namespace CFX\SDK\Brokerage;

class LegalEntitiesDatasource extends \CFX\Persistence\Rest\GenericDatasource
{
    public function getRelated($type, $id)
    {
        if ($type === 'idDocs') {
            return $this->context->documents->get('legalEntityId='.$id);
        //} elseif ($type === 'executors') {
        //    return $this->context->executors->get('legalEntityId='.$id);
        }
        return parent::getRelated($type, $id);
    }
}



