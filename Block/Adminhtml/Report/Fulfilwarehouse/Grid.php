<?php
/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\FulfilReport\Block\Adminhtml\Report\Fulfilwarehouse;

use Magento\Framework\DataObject;
use Magestore\FulfilSuccess\Api\Data\PickRequestInterface;
use Magestore\FulfilSuccess\Api\Data\PackRequestInterface;

/**
 * Report grid container.
 * @category Magestore
 * @package  Magestore_Webpos
 * @module   Webpos
 * @author   Magestore Developer
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Data\CollectionFactory
     */
    protected $dataCollection;

    /**
     * @var \Magestore\FulfilReport\Model\ResourceModel\PickRequest\FulfilWarehouse\CollectionFactory
     */
    protected $pickRequestCollectionFactory;

    /**
     * @var \Magestore\FulfilReport\Model\ResourceModel\PackRequest\FulfilWarehouse\CollectionFactory
     */
    protected $packRequestCollectionFactory;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magestore\FulfilReport\Model\ResourceModel\PickRequest\FulfilWarehouse\CollectionFactory $pickRequestCollectionFactory
     * @param \Magestore\FulfilReport\Model\ResourceModel\PackRequest\FulfilWarehouse\CollectionFactory $packRequestCollectionFactory
     * @param \Magento\Framework\Data\CollectionFactory $dataCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magestore\FulfilReport\Model\ResourceModel\PickRequest\FulfilWarehouse\CollectionFactory $pickRequestCollectionFactory,
        \Magestore\FulfilReport\Model\ResourceModel\PackRequest\FulfilWarehouse\CollectionFactory $packRequestCollectionFactory,
        \Magento\Framework\Data\CollectionFactory $dataCollection,
        array $data = [])
    {
        parent::__construct($context, $backendHelper, $data);
        $this->pickRequestCollectionFactory = $pickRequestCollectionFactory;
        $this->packRequestCollectionFactory = $packRequestCollectionFactory;
        $this->dataCollection = $dataCollection;
        $this->_filterVisibility = false;
    }

    protected function _prepareCollection()
    {
        $resultArray = [];
        $pickRequestsCollection = $this->getPickRequestsByWarehouse();
        $packRequestsCollection = $this->getPackRequestsByWarehouse();
        /** @var \Magento\Framework\Data\Collection $collection */
        $collection = $this->dataCollection->create();

        /** @var \Magestore\FulfilSuccess\Api\Data\PickRequestInterface $pickRequest */
        foreach ($pickRequestsCollection as $pickRequest) {
            $resultArray[$pickRequest->getWarehouseName()]['pick'] = $pickRequest->getData('total_picked_requests');
        }

        /** @var \Magestore\FulfilSuccess\Api\Data\PackRequestInterface $packRequest */
        foreach ($packRequestsCollection as $packRequest) {
            $resultArray[$packRequest->getWarehouseName()]['pack'] = $packRequest->getData('total_packed_requests');
        }

        foreach ($resultArray as $warehouseName => $warehouseData) {
            $item = new DataObject;
            $item->setWarehouseName($warehouseName);
            $item->setData('total_picked_requests', $warehouseData['pick']);
            $item->setData('total_packed_requests', $warehouseData['pack']);
            $collection->addItem($item);
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'warehouse_name',
            [
                'header' => __('Warehouse Name'),
                'index' => 'warehouse_name',
                'filter' => false,
                'sortable' => false,
                'totals_label' => __(''),
                'header_css_class' => 'col-warehouse-name',
                'column_css_class' => 'col-warehouse-name'
            ]
        );

        $this->addColumn(
            'total_picked_requests',
            [
                'header' => __('Total Picked Requests'),
                'index' => 'total_picked_requests',
                'filter' => false,
                'sortable' => false,
                'totals_label' => __(''),
                'header_css_class' => 'col-total-picked-requests',
                'column_css_class' => 'col-total-picked-requests'
            ]
        );

        $this->addColumn(
            'total_packed_requests',
            [
                'header' => __('Total Packed Requests'),
                'index' => 'total_packed_requests',
                'filter' => false,
                'sortable' => false,
                'totals_label' => __(''),
                'header_css_class' => 'col-total-packed-requests',
                'column_css_class' => 'col-total-packed-requests'
            ]
        );

        return parent::_prepareColumns();
    }

    protected function getPickRequestsByWarehouse()
    {
        /** @var \Magestore\FulfilReport\Model\ResourceModel\PickRequest\FulfilWarehouse\Collection $collection */
        $collection = $this->pickRequestCollectionFactory->create();

        /** @var \Magento\Framework\Stdlib\DateTime\DateTime $dateTime */
        $dateTime = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Framework\Stdlib\DateTime\DateTime'
        );

        if ($this->getFilterData()->getData('from')) {
            $fromDate = $this->getFilterData()->getData('from');
            $fromDate = $dateTime->date('Y-m-d 00:00:00', $fromDate);
        }

        if ($this->getFilterData()->getData('to')) {
            $toDate = $this->getFilterData()->getData('to');
            $toDate = $dateTime->date('Y-m-d 23:59:59', $toDate);
        }

        $collection->addFieldToFilter('main_table.updated_at', [
            'from' => $fromDate,
            'to' => $toDate
        ]);

        return $collection;
    }

    protected function getPackRequestsByWarehouse()
    {
        /** @var \Magestore\FulfilReport\Model\ResourceModel\PackRequest\FulfilWarehouse\Collection $collection */
        $collection = $this->packRequestCollectionFactory->create();

        /** @var \Magento\Framework\Stdlib\DateTime\DateTime $dateTime */
        $dateTime = \Magento\Framework\App\ObjectManager::getInstance()->create(
            '\Magento\Framework\Stdlib\DateTime\DateTime'
        );

        if ($this->getFilterData()->getData('from')) {
            $fromDate = $this->getFilterData()->getData('from');
            $fromDate = $dateTime->date('Y-m-d 00:00:00', $fromDate);
        }

        if ($this->getFilterData()->getData('to')) {
            $toDate = $this->getFilterData()->getData('to');
            $toDate = $dateTime->date('Y-m-d 23:59:59', $toDate);
        }

        $collection->addFieldToFilter('main_table.updated_at', [
            'from' => $fromDate,
            'to' => $toDate
        ]);

        return $collection;
    }
}
