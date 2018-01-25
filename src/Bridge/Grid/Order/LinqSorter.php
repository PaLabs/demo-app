<?php


namespace App\Bridge\Grid\Order;


use PaLabs\DatagridBundle\DataSource\Order\OrderItem;
use PaLabs\DatagridBundle\DataSource\Order\Sorter;
use YaLinqo\Enumerable;
use YaLinqo\OrderedEnumerable;

class LinqSorter implements Sorter
{

    public function apply($linq, OrderItem $orderItem, array $config)
    {
        if (!$linq instanceof Enumerable) {
            throw new \LogicException();
        }

        $direction = $orderItem->getDirection() === OrderItem::ASC ? true : false;
        if ($linq instanceof OrderedEnumerable) {
            return $linq->thenByDir($direction, null, $config['options']['comparator']);
        } else {
            return $linq->orderByDir($direction, null, $config['options']['comparator']);
        }
    }
}