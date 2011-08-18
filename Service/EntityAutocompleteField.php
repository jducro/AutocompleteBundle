<?php

namespace winzou\AutocompleteBundle\Service;

use winzou\CacheBundle\Cache\AbstractCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;

/**
 * Base service
 * @author winzou
 */
class EntityAutocompleteField implements FieldInterface
{
    /** @var Doctrine\ORM\EntityManager $em */
    protected $em;

    protected $id;
    protected $class;
    protected $property;
    protected $query_builder;
    protected $parameters;

    private $dql;

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

    public function setClass($class)
    {
        $this->class = $class;
    }
    public function getClass()
    {
        return $this->class;
    }

    public function setProperty($property)
    {
        $this->property = $property;
    }
    public function getProperty()
    {
        return $this->property;
    }

    public function setQueryBuilder($query_builder)
    {
        $this->query_builder = $query_builder;
    }
    public function getQueryBuilder()
    {
        return $this->query_builder;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Return an array of entities in the list of choices.
     * Only the selected property, not the complete entities.
     * @param array $params
     * @return array
     */
    public function getEntities(array $params = array())
    {
        return $this->getQueryBuilder()
            ->getQuery()
            ->setParameters(array_merge($this->getParameters(), $params))
            ->getArrayResult();
    }

    /**
     * Return the entity corresponding to $value.
     * @param array $params
     * @return array
     */
    public function getEntity($value, array $params = array())
    {
        $qb = clone $this->getQueryBuilder();

        return $qb
            ->select($qb->getRootAlias())
            ->andWhere($qb->getRootAlias().'.'.$this->getProperty().' = :winzouautocompletevalue')
                ->setParameter('winzouautocompletevalue', $value)
            ->setParameters(array_merge($this->getParameters(), $params))
            ->getQuery()
            ->getSingleResult();
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
        $qb = clone $this->getQueryBuilder();

        return $qb
            ->select('COUNT('.$qb->getRootAlias().')')
            ->andWhere($qb->getRootAlias().'.'.$this->getProperty().' = :winzouautocompletevalue')
                ->setParameter('winzouautocompletevalue', $value)
            ->setParameters($params)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function loadFromOptions(array $options)
    {
        $id       = $options['field_id'];
        $class    = $options['class'];
        $property = $options['property'];
        $qb       = isset($options['qb']) ?: null;
        $params   = isset($options['params']) ?: array();

        $this->em = $options['em'];

        if ($qb !== null) {
            // If a query builder was passed, it must be a closure or QueryBuilder instance
            if (!($qb instanceof QueryBuilder || $qb instanceof \Closure)) {
                throw new \InvalidArgumentException('Expecting $options[qb] to be an instance of \Doctrine\ORM\QueryBuilder or \Closure');
            }

            if ($qb instanceof \Closure) {
                $qb = $qb($this->em->getRepository($class));

                if (!$qb instanceof QueryBuilder) {
                    throw new \InvalidArgumentException('Expecting $options[qb] to be an instance of \Doctrine\ORM\QueryBuilder');
                }
            }
        } else {
            $qb = $this->em->getRepository($class)->createQueryBuilder('e');
        }

        // We retrieve the params of the queryBuilder, if any, and merge it with our $params
        $params = array_merge($qb->getParameters(), $params);

        // we select only the $property, so that we can access it via ArrayAccess in the view
        $qb->select($qb->getRootAlias().'.'.$property.' '.$property);

        $this->setId($id);
        $this->setClass($class);
        $this->setProperty($property);
        $this->setQueryBuilder($qb);
        $this->setParameters($params);

        return $this;
    }

    public function __sleep()
    {
        $this->dql = $this->query_builder->getDQLParts();

        return array('id', 'class', 'property', 'dql', 'parameters');
    }

    /**
     * Build a new QueryBuilder from the given DQL parts
     * (The QueryBuilder object is not serializable, it's a workaround)
     * @param array $DQLParts
     * @return Doctrine\ORM\QueryBuilder
     */
    public function __wakeup()
    {
        // Rebuild the QueryBuilder from stored DQL parts
        $qb = $this->em->createQueryBuilder();

        foreach ($this->dql as $partName => $part) {
            if ($part) {
                if (is_array($part)) {
                    $part = current($part);
                }
                $qb->add($partName, $part, false);
            }
        }

        $qb->setParameters($this->parameters);

        $this->setQueryBuilder($qb);
    }
}