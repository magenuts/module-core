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



namespace Magenuts\Core\Controller\Adminhtml\Validator;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magenuts\Core\Block\Adminhtml\Validator;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session.
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Magenuts_Core::validator';

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        /** @var \Magenuts\Core\Block\Adminhtml\Validator $validator */
        $validator = $this->_view->getLayout()->createBlock(Validator::class);

        $resultJson->setData([
            'content'  => $validator->toHtml(),
            'isPassed' => $validator->isPassed(),
        ]);

        return $resultJson;
    }
}
