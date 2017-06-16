<?php

namespace Magestore\FulfilReport\Model\ResourceModel\PackRequest\FulfilWarehouse;

use Magestore\FulfilSuccess\Api\Data\PackRequestInterface;

class Collection extends \Magestore\FulfilSuccess\Model\ResourceModel\PackRequest\PackRequest\Collection
{
    protected function _initSelect()
    {
        $this->getSelect()->from(['main_table' => $this->getMainTable()]);
        $this->getSelect()->joinLeft(
            ['os_warehouse' => $this->getTable('os_warehouse')],
            'main_table.warehouse_id = os_warehouse.warehouse_id',
            [
                'warehouse_name' => 'os_warehouse.warehouse_name'
            ]
        );

        $this->getSelect()->columns([
            'warehouse_name' => 'os_warehouse.warehouse_name',
            'total_packed_requests' => new \Zend_Db_Expr("COUNT('pack_request_id')"),
        ]);
        $this->getSelect()->group('main_table.warehouse_id');

        $this->addCondition();

        return $this;
    }

    /**
     * add condition.
     */
    public function addCondition()
    {
        $this->addFieldToFilter('main_table.warehouse_id', ['notnull' => true]);

        $this->addFieldToFilter('main_table.status', PackRequestInterface::STATUS_PACKED);
    }
}