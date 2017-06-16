<?php
/**
 * Copyright © 2016 Magestore. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magestore\FulfilReport\Controller\Adminhtml\Report;


class Perday extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    protected $_papeFactory;

    /**
     * constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_papeFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_papeFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock('Magestore\FulfilReport\Block\Adminhtml\Report\Dashboard')
            ->setTemplate('Magestore_FulfilReport::report/perday.phtml')
            ->toHtml();
        $this->getResponse()->setBody($block);
    }

}
