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

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class Upgrade102 implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->getConnection()
            ->dropIndex(
                $installer->getTable('mst_core_urlrewrite'),
                $installer->getIdxName(
                    'core_urlrewrite_index1',
                    ['module', 'type', 'entity_id'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                )
            );
        $installer->getConnection()
            ->dropIndex(
                $installer->getTable('mst_core_urlrewrite'),
                $installer->getIdxName(
                    'core_urlrewrite_index2',
                    ['url_key', 'module'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                )
            );
        $installer->getConnection()->addIndex(
            $installer->getTable('mst_core_urlrewrite'),
            $installer->getIdxName(
                'core_urlrewrite_index1',
                ['module', 'type', 'entity_id', 'store_id'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['module', 'type', 'entity_id', 'store_id'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
        $installer->getConnection()->addIndex(
            $installer->getTable('mst_core_urlrewrite'),
            $installer->getIdxName(
                'core_urlrewrite_index2',
                ['url_key', 'module', 'store_id'],
                AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['url_key', 'module', 'store_id'],
            AdapterInterface::INDEX_TYPE_UNIQUE
        );
    }
}
