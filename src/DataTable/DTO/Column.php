<?php

namespace Umbrella\CoreBundle\DataTable\DTO;

use Umbrella\CoreBundle\DataTable\Column\ColumnType;
use Umbrella\CoreBundle\Utils\HtmlUtils;

class Column
{
    protected ColumnType $type;

    protected array $options;

    /**
     * Column constructor.
     */
    public function __construct(ColumnType $type, array $options)
    {
        $this->type = $type;
        $this->options = $options;
    }

    public function isOrderable(): bool
    {
        return false !== $this->options['order'];
    }

    public function getDefaultOrder(): ?string
    {
        return is_string($this->options['order']) ? $this->options['order'] : null;
    }

    public function getOrderBy(): array
    {
        return null === $this->options['order_by'] ? [] : (array) $this->options['order_by'];
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getOption(string $name)
    {
        return $this->options[$name];
    }

    public function render($rowData): string
    {
        if (is_callable($this->options['render'])) {
            $value = (string) call_user_func($this->options['render'], $rowData, $this->options);
        } else {
            $value = $this->type->render($rowData, $this->options);
        }

        return $this->options['is_safe_html'] ? $value : HtmlUtils::escape($value);
    }
}
