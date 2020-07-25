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



namespace Magenuts\Core\Plugin\Backend\Block\Menu;

use Magento\Backend\Model\Menu;

class UpdateMarkupPlugin
{
    /**
     * @param mixed $subject
     * @param Menu $menu
     * @param int $level
     * @param int $limit
     * @param array $colBrakes
     * @return array
     */
    public function beforeRenderNavigation($subject, Menu $menu, $level = 0, $limit = 0, $colBrakes = [])
    {
        if ($menu->get('Magenuts_Core::marketplace') && $level != 0) {
            if (is_array($colBrakes)) {
                foreach ($colBrakes as $key => $colBrake) {
                    if (isset($colBrake['colbrake'])) {
                        if ($colBrake['colbrake']) {
                            $colBrakes[$key]['colbrake'] = false;
                        }

                        if (($key - 1) % 12 == 0) {
                            $colBrakes[$key]['colbrake'] = true;
                        }
                    }
                }
            }

            return [$menu, 0, 12, $colBrakes];
        }

        return [$menu, $level, $limit, $colBrakes];
    }
}