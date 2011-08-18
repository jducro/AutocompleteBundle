<?php

namespace winzou\JQueryAutocompleteBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

use winzou\JQueryAutocompleteBundle\Service\FieldManager;
use winzou\JQueryAutocompleteBundle\DataTransformer\EntityToPropertyTransformer;


class JQueryAutocompleteType extends AbstractType
{
    /** @var winzou\JQueryAutocompleteBundle\Service\FieldManager */
    protected $fieldManager;

    public function __construct(FieldManager $fieldManager)
    {
        $this->fieldManager = $fieldManager;
    }

    public function buildForm(FormBuilder $builder, array $options)
    {
        $fieldClass = $options['field'];

        $field = new $fieldClass($options);

        $this->fieldManager->setField($field);

        if ($options['multiple']) {
            throw new \Exception('Not yet implemented');
            $builder
                ->prependClientTransformer(new EntitiesToArrayTransformer($options['choice_list']))
            ;
        } else {
            $builder->prependClientTransformer(new EntityToPropertyTransformer($field));
        }

        $builder
            ->setAttribute('multiple', $options['multiple'])
            ->setAttribute('required', $options['required'])
            ->setAttribute('field_id', $options['field_id'])
            ->setAttribute('ajax',     $options['ajax'])
            ->setAttribute('property', $options['property']);
    }

    public function getDefaultOptions(array $options)
    {
        $defaultOptions = array(
            'multiple' => false,
            'ajax'     => false,
            'id'       => $this->fieldManager->getAvailableId(),
            'field'    => 'winzou\JQueryAutocompleteBundle\Service\EntityAutocompleteField',
        );

        return array_replace($defaultOptions, $options);
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
            $view->set('choices', $this->fieldManager->getField($form->getAttribute('field_id'))->getEntities());
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