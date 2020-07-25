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



namespace Magenuts\Core\Controller\Lc;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magenuts\Core\Model\LicenseFactory;
use Magenuts\Core\Model\ModuleFactory;

class Index extends Action
{
    /**
     * @var ModuleFactory
     */
    private $moduleFactory;

    /**
     * @var LicenseFactory
     */
    private $licenseFactory;

    /**
     * Index constructor.
     * @param ModuleFactory $moduleFactory
     * @param LicenseFactory $licenseFactory
     * @param Context $context
     */
    public function __construct(
        ModuleFactory $moduleFactory,
        LicenseFactory $licenseFactory,
        Context $context
    ) {
        $this->moduleFactory  = $moduleFactory;
        $this->licenseFactory = $licenseFactory;

        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD)
     */
    public function execute()
    {
        echo '<pre>';

        $module = $this->moduleFactory->create();
        foreach ($module->getInstalledModules() as $moduleName) {
            $moduleName = str_replace('Magenuts_', '', $moduleName);

            echo $moduleName;

            $info = $module->getComposerInformation('Magenuts_' . $moduleName);
            if ($info) {
                echo ' ' . $info['version'];
            }

            $license = $this->licenseFactory->create();

            echo ' ' . $license->load('\\' . $moduleName);

            $license->clear();

            echo ' = ' . $license->getStatus('\\' . $moduleName);

            echo PHP_EOL;
        }

        exit;
    }
}
