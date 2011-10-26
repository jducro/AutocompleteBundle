<?php

namespace Interlex\Common\AutocompleteBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Form\ChoiceList\EntityChoiceList;

use Symfony\Bridge\Doctrine\Form\DataTransformer\EntityToIdTransformer;


class JQueryAutocompleteType extends AbstractType
{
    /** @var winzou\AutocompleteBundle\Service\FieldManager */
    protected $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        if (empty($options['field_id'])) $options['field_id'] = $builder->getName();
        

        if ($options['multiple']) {
            throw new \Exception('Not yet implemented');
            $builder
                ->prependClientTransformer(new EntityToIdTransformer($options['choice_list']))
            ;
        } else {
            $builder->prependClientTransformer(new EntityToIdTransformer($options['choice_list']));
        }

        $builder
            ->setAttribute('multiple',  $options['multiple'])
            ->setAttribute('required',  $options['required'])
            ->setAttribute('ajax',      $options['ajax'])
            ->setAttribute('choice_list',$options['choice_list'])
            ->setAttribute('property',  $options['property']);
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'em'            => null,
            'class'         => null,
            'property'      => null,
            'query_builder' => null,
            'choices'       => array(),
            'multiple'      => false,
            'ajax'          => false,
            'field'         => 'Interlex\Common\AutocompleteBundle\Service\EntityAutocompleteField',
        );

        $options = array_replace($defaultOptions, $options);
        
        if (!isset($options['choice_list'])) {
            $defaultOptions['choice_list'] = new EntityChoiceList(
                $this->registry->getEntityManager($options['em']),
                $options['class'],
                $options['property'],
                $options['query_builder'],
                $options['choices']
            );
        }

        return $defaultOptions;
    }

    public function getParent(array $options)
    {
        return 'field';
    }

    /**
     * @see Symfony\Component\Form.AbstractType::buildView()
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        // If we autocomplete by ajax, we don't pass the choices here
        if (!$form->getAttribute('ajax')) {
            $choices = $form->getAttribute('choice_list')->getChoices();
            $view->set('choices', $choices);
        }

        if ($form->getAttribute('multiple')) {
            // Add "[]" to the name in case a select tag with multiple options is
            // displayed. Otherwise only one of the selected options is sent in the
            // POST request.
            $view->set('full_name', $view->get('full_name').'[]');
        }

        $view->set('multiple', $form->getAttribute('multiple'));
        $view->set('property', $form->getAttribute('property'));
    }

    /**
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'autocomplete_entity';
    }
}