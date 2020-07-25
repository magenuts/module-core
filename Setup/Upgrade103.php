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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class Upgrade103 implements UpgradeSchemaInterface
{
    const NEW_PREFIX = 'mst_';

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $tableName       = $installer->getTable('core_config_data');
        $configsToUpdate = [
            'core/css/include_font_awesome' => 1,
            'core/logger/developer_ip'      => '80.78.40.163',
        ];

        foreach ($configsToUpdate as $path => $default) {
            $select = $installer->getConnection()->select();
            $select->from($tableName, ['value'])
                ->where('path = ?', $path)
                ->where('scope_id = 0')
                ->where('scope = ?', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);

            $value = $installer->getConnection()->fetchOne($select);

            $installer->getConnection()->insertOnDuplicate($tableName, [
                'path'     => self::NEW_PREFIX . $path,
                'value'    => $value !== false ? $value : $default,
                'scope'    => ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
                'scope_id' => 0,
            ]);
        }
    }
}
