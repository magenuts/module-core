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



namespace Magenuts\Core\Block\Adminhtml;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magenuts\Core\Api\Service\ValidationServiceInterface;
use Magenuts\Core\Api\Service\ValidatorInterface;

class Validator extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Magenuts_Core::validator.phtml';

    /**
     * @var ValidationServiceInterface
     */
    private $validationService;

    /**
     * @var array
     */
    private $results = [];

    /**
     * Validator constructor.
     * @param ValidationServiceInterface $validationService
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ValidationServiceInterface $validationService,
        Context $context,
        array $data = []
    ) {
        $this->validationService = $validationService;

        parent::__construct($context, $data);
    }

    /**
     * Get validation result.
     *
     * @return array[]
     */
    public function getResult()
    {
        if (!$this->results) {
            $modules = [];

            $module = $this->getRequest()->getParam('module');
            if ($module) {
                $modules[] = $module;
            }

            $this->results = $this->validationService->runValidation($modules);
        }

        return $this->results;
    }

    /**
     * Whether a validation is passed or some tests fail.
     *
     * @return bool
     */
    public function isPassed()
    {
        foreach ($this->getResult() as $result) {
            if ($result[ValidatorInterface::STATUS_CODE] == ValidatorInterface::FAILED) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get label for status.
     *
     * @param int $status
     *
     * @return string
     */
    public function getStatusLabel($status)
    {
        $statusLabels = [
            ValidatorInterface::FAILED  => 'error',
            ValidatorInterface::WARNING => 'warning',
            ValidatorInterface::INFO    => 'info',
            ValidatorInterface::SUCCESS => 'success',
        ];

        return $statusLabels[$status];
    }
}
