<?php
namespace CFX\SDK\Brokerage;

class TenderRoomsDatasource extends \CFX\Persistence\Rest\GenericDatasource
{
    public function getRelated($type, $id)
    {
        if ($type === 'tenders') {
            return $this->context->tenders->get('tenderRoomId='.$id);
        }
        return parent::getRelated($type, $id);
    }
}


