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



namespace Magenuts\Core\Plugin\Backend\Model\Menu\Item;

use Magento\Backend\Model\Menu\Item;

class MarketplaceUrlPlugin
{
    /**
     * @param Item $subject
     * @param string $url
     * @return string
     */
    public function afterGetUrl(Item $subject, $url)
    {
        if ($subject->getId() === 'Magenuts_Core::marketplace') {
            return 'https://magenuts.com/magento-2-extensions.html?utm_source=extension&utm_medium=backend&utm_campaign=menu';
        }

        return $url;
    }
}