<?php


namespace App\Bridge\Grid\Filter\Linq;


use PaLabs\DatagridBundle\Filter\FilterInterface;
use YaLinqo\Enumerable;

class LinqFilterApplier
{
    public function apply(Enumerable $linq, array $filters, array $filterData)
    {
        foreach ($filters as $name => $filterDesc) {
            if (!empty($filterData[$name])) {

                /** @var FilterInterface $filter */
                $filter = $filterDesc['filter'];
                if($filter === null) {
                    continue;
                }

                $linq = $filter->apply($linq, $name, $filterData[$name], $filterDesc['filterOptions']);
            }
        }
        return $linq;
    }
}