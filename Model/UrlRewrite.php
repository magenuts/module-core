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



namespace Magenuts\Core\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * @method string getUrlKey()
 * @method $this setUrlKey($key)
 *
 * @method string getType()
 * @method $this setType($type)
 *
 * @method string getModule()
 * @method $this setModule($module)
 *
 * @method int getEntityId()
 */
class UrlRewrite extends AbstractModel
{
    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Magenuts\Core\Model\ResourceModel\UrlRewrite');
    }
}
