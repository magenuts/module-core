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



namespace Magenuts\Core\Service\Manual;

use Magento\Framework\View\TemplateEngineInterface;
use Magento\Framework\View\Element\BlockInterface;

class AddInTemplate implements TemplateEngineInterface
{
    /**
     * @var TemplateEngineInterface
     */
    private $subject;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $helpBlockHtml;

    /**
     * AddInTemplate constructor.
     * @param TemplateEngineInterface $subject
     * @param string $template
     * @param string $helpBlockHtml
     */
    public function __construct(TemplateEngineInterface $subject, $template, $helpBlockHtml)
    {
        $this->subject = $subject;
        $this->template = $template;
        $this->helpBlockHtml = $helpBlockHtml;
    }

    /**
     * Insert help into the rendered block contents
     *
     * {@inheritdoc}
     */
    public function render(BlockInterface $block, $templateFile, array $dictionary = [])
    {
        $result = $this->subject->render($block, $templateFile, $dictionary);

        $isTemplateUsed = (strpos($templateFile, $this->template) !== false) ? true : false;

        if ($isTemplateUsed) {
            $result = $this->addHelpBlock($result);
        }

        return $result;
    }

    /**
     * @param string $result
     * @return string
     */
    public function addHelpBlock($result)
    {
        if ($this->helpBlockHtml) {
            $result = $result . $this->helpBlockHtml;
        }

        return $result;
    }
}
