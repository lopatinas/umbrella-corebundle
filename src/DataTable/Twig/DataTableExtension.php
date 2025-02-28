<?php

namespace Umbrella\CoreBundle\DataTable\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Umbrella\CoreBundle\DataTable\DataTableRenderer;

class DataTableExtension extends AbstractExtension
{
    protected DataTableRenderer $renderer;

    /**
     * DataTableTwigExtension constructor.
     */
    public function __construct(DataTableRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_table', [$this->renderer, 'render'], [
                'is_safe' => ['html'],
            ]),
            new TwigFunction('render_toolbar', [$this->renderer, 'renderToolbar'], [
                'is_safe' => ['html'],
            ])
        ];
    }
}
