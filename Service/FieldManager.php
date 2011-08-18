<?php

namespace winzou\JQueryAutocompleteBundle\Service;

use winzou\CacheBundle\Cache\AbstractCache;

/**
 * Base service
 * @author winzou
 */
class FieldManager
{
    /** @var winzou\CacheBundle\Cache\AbstractCache $cache */
    protected $cache;

    /** @var array */
    protected $arrayCache;

    public function __construct(AbstractCache $cache)
    {
        $this->cache = $cache;
    }

    public function setField(FieldInterface $field)
    {
        $this->_save($field);
    }

    /**
     * Fetch the information for the $id field
     * @param string $id
     * @return winzou\JQueryAutocompleteBundle\Service\FieldInterface
     */
    public function getField($id)
    {
        return $this->_fetch($id);
    }

    /**
     * Return an available id
     * @return string
     */
    public function getAvailableId()
    {
        while($this->_contains($id = 'jqueryautocomplete_'.sha1(uniqid())));

        return $id;
    }


    /**
     * Save the information for the $id field
     * @param winzou\JQueryAutocompleteBundle\Service\FieldInterface $field
     */
    protected function _save(FieldInterface $field)
    {
        $this->arrayCache[$field->getId()] = $field;

        return $this->cache->save($field->getId(), $field);
    }

    /**
     * Fetch the information for the $id field
     * @param string $id
     * @return winzou\JQueryAutocompleteBundle\Service\FieldInterface
     * @throws \Exception
     */
    protected function _fetch($id)
    {
        if (isset($this->arrayCache[$id])) {
            return $this->arrayCache[$id];
        }

        if (!$this->_contains($id)) {
            throw new \Exception('The field "'.$id.'" must be already saved before fetching it.');
        }

        return $this->cache->fetch($id);
    }

    /**
     * Check if the $id field is stored
     * @param string $id
     * @return bool
     */
    protected function _contains($id)
    {
        if (isset($this->arrayCache[$id])) {
            return true;
        }

        return $this->cache->contains($id);
    }
}