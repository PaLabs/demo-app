<?php


namespace AppBundle\Bridge\Grid\Filter\Entity;


use AppBundle\Bridge\Form\Select2EntityForm;
use PaLabs\DatagridBundle\DataSource\Doctrine\Filter\Type\EntityFilter;

class Select2EntityFilter extends EntityFilter
{
    public function formOptions(): array
    {
        return [
            'entity_form' => Select2EntityForm::class,
        ];
    }
}