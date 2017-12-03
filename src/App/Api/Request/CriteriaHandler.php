<?php

namespace App\Api\Request;

use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class CriteriaHandler
{
    /** @var NameConverterInterface  */
    protected $normalizer;

    public function __construct(NameConverterInterface $normalizer = null)
    {
        $this->normalizer = $normalizer ?? new CamelCaseToSnakeCaseNameConverter();
    }

    public function createCriteria(Request $request): Criteria
    {
        $criteria = Criteria::create();

        // Pagination: offset
        $offset = $request->query->get('offset');
        if (is_numeric($offset)) {
            $criteria->setFirstResult($offset);
        }

        // Pagination: limit
        $limit = $request->query->get('limit'); 
        if (is_numeric($limit)) {
            $criteria->setMaxResults($limit);
        }

        // Order
        $order = $request->query->get('order'); 
        if (is_array($order)) {
            $this->handleOrderBy($criteria, $order);
        }

        // Filter
        $filter = $request->query->get('filter'); 
        if (is_array($filter)) {
            $this->handleFilter($criteria, $filter);
        }

        return $criteria;
    }

    public function handleFilter(Criteria $criteria, array $map): Criteria
    {
        $normalizer = new CamelCaseToSnakeCaseNameConverter();
        $normalizedMap = [];

        foreach ($map as $key => $value) {
            $key = $this->normalize($key);
            list($property, $expr) = strpos($key, ':') ? explode(':', $key) : [$key, 'eq'];
            $criteria->andWhere(Criteria::expr()->{$expr}($property, $value));    
        }

        return $criteria;
    }

    public function handleOrderBy(Criteria $criteria, array $map): Criteria
    {
        $normalizer = new CamelCaseToSnakeCaseNameConverter();
        $normalizedMap = [];

        foreach ($map as $key => $value) {
            $key = $this->normalize($key);
            $normalizedMap[$key] = $value;
        }

        return $criteria->orderBy($normalizedMap);
    }

    public function normalize(string $name): string
    {
        return $this->normalizer->denormalize($name);
    }
}
