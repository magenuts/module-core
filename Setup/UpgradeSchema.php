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



namespace Magenuts\Core\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Upgrade101
     */
    private $upgrade101;

    /**
     * @var Upgrade102
     */
    private $upgrade102;

    /**
     * @var Upgrade103
     */
    private $upgrade103;

    /**
     * UpgradeSchema constructor.
     * @param Upgrade101 $upgrade101
     * @param Upgrade102 $upgrade102
     * @param Upgrade103 $upgrade103
     */
    public function __construct(
        Upgrade101 $upgrade101,
        Upgrade102 $upgrade102,
        Upgrade103 $upgrade103
    ) {
        $this->upgrade101 = $upgrade101;
        $this->upgrade102 = $upgrade102;
        $this->upgrade103 = $upgrade103;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $this->upgrade101->upgrade($installer, $context);
        }

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $this->upgrade102->upgrade($installer, $context);
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $this->upgrade103->upgrade($installer, $context);
        }
    }
}
