<?php


namespace App\Bridge\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Select2EntityForm extends AbstractType
{

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = array_merge($view->vars['attr'], [
            'class' => 'select2',
            'data-multiple' => json_encode($options['multiple']),
            'data-allow-clear' => json_encode(!$options['required']),
        ]);

    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'multiple' => false
            ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }


}