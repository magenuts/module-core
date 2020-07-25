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
use Magenuts\Core\Model\NotificationFeedFactory;
use Magento\Framework\Message\ManagerInterface;
use Magenuts\Core\Model\LicenseFactory;

class OnActionPredispatchObserver implements ObserverInterface
{
    /**
     * @var bool
     */
    public static $notified = false;
    /**
     * @var NotificationFeedFactory
     */
    private $feedFactory;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @var LicenseFactory
     */
    private $licenseFactory;

    /**
     * OnActionPredispatchObserver constructor.
     * @param NotificationFeedFactory $feedFactory
     * @param ManagerInterface $manager
     * @param LicenseFactory $licenseFactory
     */
    public function __construct(
        NotificationFeedFactory $feedFactory,
        ManagerInterface $manager,
        LicenseFactory $licenseFactory
    ) {
        $this->feedFactory = $feedFactory;
        $this->manager = $manager;
        $this->licenseFactory = $licenseFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(EventObserver $observer)
    {
        $action = $observer->getData('controller_action');

        if (is_object($action) && substr(get_class($action), 0, 9) == 'Magenuts\\') {
            $status = $this->licenseFactory->create()->getStatus(get_class($action));

            if ($status !== true) {
                if (!self::$notified) {
                    $this->manager->addErrorMessage($status);
                    self::$notified = true;
                }
                $observer->getRequest()->setRouteName('no_route');
//                print_r(get_class_methods($observer->getRequest()));
//                die();
            }
        }

        $feedModel = $this->feedFactory->create();
        $feedModel->checkUpdate();
    }
}