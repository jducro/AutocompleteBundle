<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace winzou\AutocompleteBundle\DataTransformer;


use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

use winzou\AutocompleteBundle\Service\FieldInterface;

class EntityToPropertyTransformer implements DataTransformerInterface
{
    /** @var winzou\AutocompleteBundle\Service\FieldInterface */
    protected $field;

    public function __construct(FieldInterface $field)
    {
        $this->field = $field;
    }

    /**
     * Transforms entities into choice keys
     *
     * @param object $entity A single entity or
     * @return mixed A single key
     */
    public function transform($entity)
    {
        /** @todo wtf ? */
        if (is_array($entity)) {
            $entity = $entity[0];
        }

        if (null === $entity || '' === $entity) {
            return '';
        }

        if (!is_object($entity)) {
            throw new UnexpectedTypeException($entity, 'object');
        }

        return call_user_func(array($entity, 'get'.ucfirst($this->field->getProperty())));
    }

    /**
     * Transforms choice keys into entities
     *
     * @param  mixed $key A single key or NULL
     * @return object A single entity
     */
    public function reverseTransform($key)
    {
        if ('' === $key || null === $key) {
            return null;
        }

        try {
            $entity = $this->field->getEntity($key);
        } catch (NoResultException $e) {
            // do nothing
        }

        if (!$entity) {
            throw new TransformationFailedException(sprintf('The entity with key "%s" could not be found', $key));
        }

        return $entity;
    }
}
