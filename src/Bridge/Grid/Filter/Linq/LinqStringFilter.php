<?php


namespace App\Bridge\Grid\Filter\Linq;


use App\Util\StringUtils;
use PaLabs\DatagridBundle\DataSource\Filter\FilterFormProvider;
use PaLabs\DatagridBundle\DataSource\Filter\Form\String\StringFilterData;
use PaLabs\DatagridBundle\DataSource\Filter\Form\String\StringFilterForm;
use YaLinqo\Enumerable;

class LinqStringFilter implements FilterFormProvider
{

    public function formType(): string
    {
        return StringFilterForm::class;
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
        if(!$criteria instanceof StringFilterData) {
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
            return StringUtils::containsIgnoreCase($value, $criteria->getValue());
        });
    }

}