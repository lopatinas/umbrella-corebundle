<?php

namespace Umbrella\CoreBundle\DataTable\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyColumnType extends ColumnType
{
    protected PropertyAccessorInterface $accessor;

    /**
     * PropertyColumn constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function render($rowData, array $options): string
    {
        // FIXME symfony accessor only supports array|object
        if (!\is_array($rowData) && !\is_object($rowData)) {
            throw new \InvalidArgumentException('Argument "$rowData" of PropertyColumnType::render() supports only type "string" or "array".');
        }

        return $this->renderProperty($this->accessor->getValue($rowData, $options['property_path']), $options);
    }

    public function renderProperty($value, array $options): string
    {
        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('property_path', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('property_path', 'string')

            ->setDefault('order', null)

            ->setDefault('order_by', function (Options $options) {
                return $options['property_path'];
            });
    }
}
