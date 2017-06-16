<?php
/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\FulfilReport\Block\Adminhtml\Report;

class Navigation extends \Magento\Backend\Block\Template
{
    /**
     * report list
     *
     * @var array
     */
    protected $reportList;

    /**
     * get a list of staff report controllers and names
     *
     * @return array
     */
    public function getStaffReportList()
    {
        return array(
            'fulfilstaff' => __('Fulfilment by staff'),
            'fulfilstaffdaily' => __('Fulfilment by staff (Daily)')
        );
    }

    /**
     * get a list of location report controllers and names
     *
     * @return array
     */
    public function getWarehouseReportList()
    {
        return array(
            'fulfilwarehouse' => __('Fulfilment by warehouse'),
            'fulfilwarehousedaily' => __('Fulfilment by warehouse (Daily)')
        );
    }

    public function getReportList()
    {
        if (!$this->reportList) {
            $this->reportList = array_merge(
                $this->getStaffReportList(),
                $this->getWarehouseReportList()
            );
        }
        return $this->reportList;
    }

    /**
     * get report link from name
     *
     * @param string
     * @return string
     */
    public function getReportLink($controller)
    {
        $path = 'fulfilreport/report_' . $controller;
        return $this->getUrl($path, array('_forced_secure' => $this->getRequest()->isSecure()));
    }

    /**
     * get current report name
     *
     * @param
     * @return string
     */
    public function getCurrentReportName()
    {
        $controller = $this->getRequest()->getControllerName();
        $controller = str_replace('report_', '', $controller);
        $reportList = $this->getReportList();
        $reportName = '';
        if (isset($reportList[$controller])) {
            $reportName = $reportList[$controller];
        }
        return $reportName;
    }
}