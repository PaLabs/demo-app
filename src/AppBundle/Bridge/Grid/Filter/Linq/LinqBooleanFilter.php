<?php


namespace AppBundle\Bridge\Grid\Filter\Linq;


use PaLabs\DatagridBundle\DataSource\Filter\FilterFormProvider;
use PaLabs\DatagridBundle\DataSource\Filter\Form\Boolean\BooleanFilterData;
use PaLabs\DatagridBundle\DataSource\Filter\Form\Boolean\BooleanFilterForm;
use YaLinqo\Enumerable;

class LinqBooleanFilter implements FilterFormProvider
{

    public function formType(): string
    {
        return BooleanFilterForm::class;
    }

    public function formOptions(): array
    {
        return [];
    }

    public function apply($linq, string $name, $criteria, array $options = [])
    {
        if (!$linq instanceof Enumerable) {
            throw new \LogicException();
        }
        if(!$criteria instanceof BooleanFilterData) {
            throw new \Exception();
        }
        if(!$criteria->isEnabled()) {
            return $linq;
        }

        $selector = $options['selector'];
        if (!is_callable($selector)) {
            throw new \Exception('Selector must be callable');
        }

        return $linq->where(function ($item) use ($selector, $criteria) {
            $value = $selector($item);
            return $value === $criteria->getValue();
        });
    }

}