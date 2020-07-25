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



namespace Magenuts\Core\Plugin;

use Magento\Framework\Event\ManagerInterface as EventManagerInterface;

class UrlRewritePlugin
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;

    /**
     * UrlRewritePlugin constructor.
     * @param EventManagerInterface $eventManager
     */
    public function __construct(
        EventManagerInterface $eventManager
    ) {
        $this->eventManager = $eventManager;
    }

    /**
     * Dispatch our event before dispatch Frontend Controller
     * @param mixed $subject
     * @param mixed $request
     */
    public function beforeDispatch($subject, $request)
    {
        $this->eventManager->dispatch('core_register_urlrewrite');
    }
}
