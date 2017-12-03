<?php

namespace App\Api\Tests\Request;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use App\Api\Request\CriteriaHandler;

class CriteriaHandlerTest extends WebTestCase
{
    public function test_criteria_handler(): void
    {
        $this->assertInstanceOf(
            NameConverterInterface::class,
            (new CriteriaHandler())->getNormalizer()
        );
    }

    public function test_create_criteria_request_empty()
    {
        $request = new Request();
        $criteria = (new CriteriaHandler())->createCriteria($request);

        $this->assertEmpty($criteria->getFirstResult());
        $this->assertEmpty($criteria->getMaxResults());
        $this->assertEmpty($criteria->getOrderings());
        $this->assertEmpty($criteria->getWhereExpression());
    }

    public function test_create_criteria_request_order()
    {
        $request = new Request();
        $request->query->set('order', [
            ($field = 'test') => ($dir = Criteria::ASC),
        ]);

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertEquals([$field => $dir], $criteria->getOrderings());
    }

    public function test_create_criteria_request_limit()
    {
        $request = new Request();
        $request->query->set('limit', $limit = rand(1, 10));

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertEquals($limit, $criteria->getMaxResults());
    }

    public function test_create_criteria_request_offset()
    {
        $request = new Request();
        $request->query->set('offset', $offset = rand(1, 10));

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertEquals($offset, $criteria->getFirstResult());
    }

    public function test_create_criteria_request_filter_default(): void
    {
        $request = new Request();
        $request->query->set('filter', [
            ($field = 'test') => ($value = 'test2'),
        ]);

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertNotEmpty($criteria->getWhereExpression());

        $expr = $criteria->getWhereExpression();
        $this->assertEquals($field, $expr->getField());
        $this->assertEquals($value, $expr->getValue()->getValue());
        $this->assertEquals(Comparison::EQ, $expr->getOperator());
    }

    public function test_create_criteria_request_filter_eq(): void
    {
        $request = new Request();
        $request->query->set('filter', [
            'test1:eq' => 'test2',
        ]);

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertNotEmpty($criteria->getWhereExpression());

        $expr = $criteria->getWhereExpression();
        $this->assertEquals(Comparison::EQ, $expr->getOperator());
    }

    public function test_create_criteria_request_filter_neq(): void
    {
        $request = new Request();
        $request->query->set('filter', [
            'test1:neq' => 'test2',
        ]);

        $criteria = (new CriteriaHandler())->createCriteria($request);
        $this->assertNotEmpty($criteria->getWhereExpression());

        $expr = $criteria->getWhereExpression();
        $this->assertEquals(Comparison::NEQ, $expr->getOperator());
    }
}
