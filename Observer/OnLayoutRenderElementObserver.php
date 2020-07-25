<?php
/**
 * Magenuts
 *
 * This source file is subject to the Magenuts Software License, which is available at https://magenuts.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magenuts.com for more information.
 *
 * @category  Magenuts
 * @package   magenuts/module-core
 * @version   1.0.0
 * @copyright Copyright (C) 2020 Magenuts (https://magenuts.com/)
 */



namespace Magenuts\Core\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magenuts\Core\Model\LicenseFactory;

class OnLayoutRenderElementObserver implements ObserverInterface
{
    /**
     * @var LicenseFactory
     */
    protected $licenseFactory;

    /**
     * @param LicenseFactory $licenseFactory
     */
    public function __construct(
        LicenseFactory $licenseFactory
    ) {
        $this->licenseFactory = $licenseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(EventObserver $observer)
    {
        $event = $observer->getEvent();
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $event->getData('layout');
        $name = $event->getData('element_name');

        if ($name) {
            /** @var \Magento\Framework\View\Element\AbstractBlock $block */
            $block = $layout->getBlock($name);

            if (is_object($block) && substr(get_class($block), 0, 9) == 'Magenuts\\') {
                if ($block instanceof \Magenuts\Core\Block\Adminhtml\Menu) {
                    return;
                }

//                if ($block instanceof \Magenuts\Core\Block\Adminhtml\License) {
//                    return;
//                }

                $status = $this->licenseFactory->create()->getStatus(get_class($block));

                if ($status === true) {
                    return;
                }

                $transport = $event->getData('transport');

                if (!OnActionPredispatchObserver::$notified) {
                    $transport->setData('output', "<div class='message message-warning warning'>$status</div>");
                    OnActionPredispatchObserver::$notified = true;
                } else {
                    $transport->setData('output', "");
                }
            }
        }
    }
}
