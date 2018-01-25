<?php


namespace AppBundle\Bridge\Grid\Order;


use PaLabs\DatagridBundle\DataSource\Order\OrderItem;
use PaLabs\DatagridBundle\DataSource\Order\Sorter;
use YaLinqo\Enumerable;

class LinqSortApplier
{

    private $sorters = [];

    public function __construct()
    {
        $this->sorters[LinqSorter::class] = new LinqSorter();
    }

    public function apply(Enumerable $linq, array $orderConfig, array $orderItems)
    {
        /** @var OrderItem[] $orderItems */

        foreach ($orderItems as $orderItem) {
            $orderItemConfig = $orderConfig[$orderItem->getField()];
            $sorterClass = $orderItemConfig['sorter'];
            if($sorterClass === null) {
                continue;
            }
            if(!isset($this->sorters[$sorterClass])) {
                continue;
            }

            /** @var Sorter $sorter */
            $sorter = $this->sorters[$sorterClass];
            $linq = $sorter->apply($linq, $orderItem, $orderItemConfig);
        }

        return $linq;

    }
}