<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

use App\Models\Order;
use App\Models\CrudMeta;

use Auth;

class OrderHelper {
    public static function syncCalculations($order)
    {
        $orderProducts = $order->productItems ? $order->productItems->toArray() : [];
        $orderPayments = $order->payments ? $order->payments->toArray() : [];

        $calculations = self::calculations($orderProducts, $orderPayments);

        // update db
        $order->tax = $calculations['tax'];
        $order->convenience_fee = $calculations['convenience_fee'];
        $order->discount = $calculations['discount'];

        $order->base_total = $calculations['base_total'];
        $order->list_total = $calculations['list_total'];        
        $order->grand_total = $calculations['grand_total'];

        $order->paid_amount = $calculations['paid_amount'];
        $order->due_amount = $calculations['due_amount'];
        $order->save();
        // update db

        return true;
    }

    public static function calculations($products, $payments, $others = [])
    {
        $base_total = $list_total = $grand_total = $paid_amount = $due_amount = 0;

        $tax = isset($others['tax']) ? floatval($others['tax']) : 0;
        $convenience_fee = isset($others['convenience_fee']) ? floatval($others['convenience_fee']) : 0;
        $discount = isset($others['discount']) ? floatval($others['discount']) : 0;

        if (isset($products)) {
            foreach ($products as $item) {
                $quantity = isset($item['quantity']) ? intval($item['quantity']) : 1;

                if (isset($item['list_price']) && $item['list_price'] > 0) { 
                    $list_total += ($quantity * floatval($item['list_price']));
                } else {
                    $list_total += ($quantity * floatval($item['base_price']));
                }

                if (isset($item['base_price']) && $item['base_price'] > 0) { 
                    $base_total += ($quantity * floatval($item['base_price']));
                } else {
                    $base_total += ($quantity * 0);
                }
            }
        }

        if (isset($payments)) {
            foreach ($payments as $item) { $paid_amount += isset($item['amount']) ? floatval($item['amount']) : 0; }
        }

        if ($tax > 0) { $grand_total += (($tax / 100) * $list_total) + $list_total; }
        else { $grand_total = $list_total; }

        $grand_total = ($grand_total + $convenience_fee) - $discount;
        $due_amount = $grand_total - $paid_amount;

        return [
            'tax' => $tax,
            'convenience_fee' => $convenience_fee,
            'discount' => $discount,

            'base_total' => $base_total,
            'list_total' => $list_total,
            'grand_total' => $grand_total,
            'paid_amount' => $paid_amount,
            'due_amount' => $due_amount,
        ];
    }
}