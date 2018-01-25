<?php


namespace AppBundle\Bridge\Grid\DataSource;


use PaLabs\DatagridBundle\DataSource\Order\OrderItem;

class ArraySortApplier
{
    public function apply(array &$data, array $orderItems, array $orderConfig) {
        /** @var OrderItem[] $orderItems */

        $sortOptions = [];
        foreach($orderItems as $orderItem) {
            $orderItemConfig = $orderConfig[$orderItem->getField()];
            if(!isset($orderItemConfig['options']['type'])) {
                continue;
            }
            if($orderItemConfig['options']['type'] !== ArraySortApplier::class) {
                continue;
            }
            $sortOptions[] = [
                $orderItemConfig['options']['comparator'],
                $orderItem->getDirection()
            ];
        }

        if(count($sortOptions) == 0) {
            return;
        }

        /** @var OrderItem[] $order */
        uasort($data, function ($rowA, $rowB) use ($sortOptions) {
            foreach ($sortOptions as list($comparator, $direction)) {
                $compareResult = $comparator($rowA, $rowB);
                if ($compareResult != 0) {
                    switch ($direction) {
                        case OrderItem::ASC:
                            return $compareResult;
                        case OrderItem::DESC:
                            return $compareResult * -1;
                    }
                }
            }
            return 0;
        });

    }

}