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



namespace Magenuts\Core\Service;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\App\ObjectManager;

class CompatibilityService
{
    /**
     * @return bool
     */
    public static function is20()
    {
        list($a, $b,) = explode('.', self::getVersion());

        return $a == 2 && $b == 0;
    }

    /**
     * @return bool
     */
    public static function is21()
    {
        list($a, $b,) = explode('.', self::getVersion());

        return $a == 2 && $b == 1;
    }

    /**
     * @return bool
     */
    public static function is22()
    {
        list($a, $b,) = explode('.', self::getVersion());

        return $a == 2 && $b == 2;
    }

    /**
     * @return bool
     */
    public static function is23()
    {
        list($a, $b,) = explode('.', self::getVersion());

        return $a == 2 && $b == 3;
    }

    /**
     * @return bool
     */
    public static function isEnterprise()
    {
        return self::getEdition() === 'Enterprise';
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        /** @var CacheInterface $cache */
        $cache   = self::getObjectManager()->get(CacheInterface::class);
        $version = $cache->load(__CLASS__);

        if (!$version) {
            /** @var \Magento\Framework\App\ProductMetadata $metadata */
            $metadata = self::getObjectManager()->get('Magento\Framework\App\ProductMetadata');

            $version = $metadata->getVersion();
            $cache->save($version, __CLASS__);
        }

        return $version;
    }

    /**
     * @return string
     */
    public static function getEdition()
    {
        /** @var \Magento\Framework\App\ProductMetadata $metadata */
        $metadata = self::getObjectManager()->get('Magento\Framework\App\ProductMetadata');

        if (self::hasModule('Magento_Enterprise')) {
            return 'Enterprise';
        }

        return $metadata->getEdition();
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public static function hasModule($moduleName)
    {
        /** @var \Magento\Framework\Module\FullModuleList $list */
        $list = self::getObjectManager()->get('Magento\Framework\Module\FullModuleList');

        return $list->has($moduleName);
    }

    /**
     * @return ObjectManager
     */
    public static function getObjectManager()
    {
        return ObjectManager::getInstance();
    }
}
