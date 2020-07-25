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



namespace Magenuts\Core\Plugin;

use Magento\Framework\View\TemplateEngineFactory;
use Magento\Framework\View\TemplateEngineInterface;
use Magenuts\Core\Api\Service\ManualServiceInterface;
use Magenuts\Core\Service\Manual\AddInTemplateFactory;
use Magento\Framework\View\Element\BlockFactory;
use Magento\Framework\View\TemplateEngine\Php as TemplateEnginePhp;
use Magento\Framework\View\LayoutInterface;

class ManualLinkPlugin
{
    /**
     * @var ManualServiceInterface
     */
    private $manualService;

    /**
     * @var AddInTemplateFactory
     */
    private $addInTemplate;
    /**
     * @var TemplateEnginePhp
     */
    private $templateEnginePhp;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * ManualLinkPlugin constructor.
     * @param ManualServiceInterface $manualService
     * @param AddInTemplateFactory $addInTemplate
     * @param LayoutInterface $layout
     * @param TemplateEnginePhp $templateEnginePhp
     */
    public function __construct(
        ManualServiceInterface $manualService,
        AddInTemplateFactory $addInTemplate,
        LayoutInterface $layout,
        TemplateEnginePhp $templateEnginePhp
    ) {
        $this->manualService = $manualService;
        $this->layout = $layout;
        $this->addInTemplate = $addInTemplate;
        $this->templateEnginePhp = $templateEnginePhp;
    }

    /**
     * Add help block in template
     *
     * @param TemplateEngineFactory $subject
     * @param TemplateEngineInterface $invocationResult
     *
     * @return TemplateEngineInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCreate($subject, TemplateEngineInterface $invocationResult)
    {
        $manualLink = $this->manualService->getManualLink();

        if (!$manualLink || !isset($manualLink['url']) || !isset($manualLink['template'])) {
            return $invocationResult;
        }

        $url = $manualLink['url'];
        $title = $manualLink['title'];
        $template = $manualLink['template'];
        $position = $manualLink['position'];

        /** @var \Magenuts\Core\Block\Adminhtml\Manual $manualBlock */
        $manualBlock = $this->layout->createBlock('Magenuts\Core\Block\Adminhtml\Manual');

        $manualBlock->setTitle($title)
            ->setManualUrl($url)
            ->setPosition($position);

        //we can't render block here using standard way
        $html = $this->templateEnginePhp->render($manualBlock, $manualBlock->getTemplateFile(), []);

        if ($html) {
            return $this->addInTemplate->create([
                'subject'       => $invocationResult,
                'template'      => $template,
                'helpBlockHtml' => $html,
            ]);
        }

        return $invocationResult;
    }
}
