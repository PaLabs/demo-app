<?php


namespace App\Bridge\Grid\Filter\Entity;


use App\Bridge\Form\Select2EntityForm;
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