<?php

namespace winzou\JQueryAutocompleteBundle\Service;

use winzou\CacheBundle\Cache\AbstractCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Base service
 * @author winzou
 */
class PlainAutocompleteField implements FieldInterface
{
    protected $id;
    protected $choices;

    public function __construct(array $options)
    {
        $this->loadFromOptions($options);
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setChoices(array $choices)
    {
        $this->choices = $choices;
    }
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * Return an array of entities in the list of choices.
     * Only the selected property, not the complete entities.
     * @param array $params
     * @return array
     */
    public function getEntities()
    {
        return $this->getChoices();
    }

    /**
     * Return the entity corresponding to $value.
     * @param array $params
     * @return array
     */
    public function getEntity($value, array $params = array())
    {
        if ($key = array_search($value, $this->choices)) {
            return $this->choices[$key];
        }

        return null;
    }

    /**
     * Return if the Entity which property equals $value exists in the list of choices
     * @param mixed $value
     * @param string $id
     * @param array $params
     * @return bool
     */
    public function hasEntity($value, array $params = array())
    {
        return (bool) array_search($value, $this->choices);
    }

    public function loadFromOptions(array $options)
    {
        $this->id = $options['field_id'];
        $this->choices = $options['choices'];

        return $this;
    }
}