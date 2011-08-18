<?php

namespace winzou\JQueryAutocompleteBundle\Service;

use winzou\CacheBundle\Cache\AbstractCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;


interface FieldInterface
{
    public function __construct(array $options);

    public function getId();
	
    public function getClass();
	
    public function getProperty();

    public function getParameters();

    /**
     * Return an array of entities in the list of choices.
     * Only the selected property, not the complete entities.
     * @param array $params
     * @return array
     */
    public function getEntities(array $params = array());

    /**
     * Return the entity corresponding to $value.
     * @param array $params
     * @return array
     */
    public function getEntity($value, array $params = array());

    /**
     * Return if the Entity which property equals $value exists in the list of choices
     * @param mixed $value
     * @param string $id
     * @param array $params
     * @return bool
     */
    public function hasEntity($value, array $params = array());
}