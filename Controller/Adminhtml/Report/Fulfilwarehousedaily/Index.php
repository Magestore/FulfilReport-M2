<?php
/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magestore\FulfilReport\Controller\Adminhtml\Report\Fulfilwarehousedaily;

class Index extends \Magestore\FulfilReport\Controller\Adminhtml\Report\AbstractReport
{
    /**
     * Sales report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Magestore_FulfilReport::fulfilreport'
        )->_addBreadcrumb(
            __('Fulfilment by warehouse (Daily)'),
            __('Fulfilment by warehouse (Daily)')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Fulfilment by warehouse (Daily)'));

        $gridBlock = $this->_view->getLayout()->getBlock('adminhtml_report_fulfilwarehousedaily.grid');
        $filterFormBlock = $this->_view->getLayout()->getBlock('grid.filter.form');

        $this->_initReportAction([$gridBlock, $filterFormBlock]);

        $this->_view->renderLayout();
    }

}