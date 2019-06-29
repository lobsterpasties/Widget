<?php

class Widget
{
    public $product;
    public $code;
    public $price;

    public function __construct(String $product, String $code, Float $price)
    {
        $this->product = $product;
        $this->code = $code;
        $this->price = $price;
    }
}

class Basket
{
    public $items;
    public $total;

    // Total is public so we don't need to pass it to the calculateDelivery method

    public function __construct()
    {
        $this->items = [];
        $this->total = 0;
    }

    public function addWidget(Widget $widget)
    {
        array_push($this->items, $widget);
    }

    public function getTotal($discounts = null, $deliveryCharges = null)
    {
        $discount = 0;
        $delivery = 0;

        if (!empty($this->items)) {
            foreach ($this->items as $item) {
                $this->total += $item->price;
            }

            if (!is_null($discounts)) {
                $discount = $this->calculateDiscounts($discounts);
                $this->total -= $discount;
            }

            if (!is_null($deliveryCharges)) {
                $delivery = $this->calculateDelivery($deliveryCharges);
                $this->total += $delivery;
            }
            // this allows for delivery on "free" items in future
        }

        return ["total" => $this->total, "discount" => $discount, "deliveryCharges" => $delivery];
    }

    private function calculateDiscounts($discounts)
    {
        $totalDiscount = 0;
        foreach ($discounts as $discount) {
            if ($discount['type'] === "BOGOHP") {
                $count = 0;
                $price = 0;
                $myDiscount = $discount['percentage'];
                // What is the amount we need to discount (in percentage)
                foreach ($this->items as $item) {
                    if ($item->code === "R01") {
                        $count++;
                        if ($price === 0) {
                            $price = $item->price;
                        }
                    }
                }
                $bOGOHPCount = floor($count / 2);
                // How many items have an applicable discount
                $priceToDiscount = ($myDiscount / 100) * $price;
                $thisDiscount = $priceToDiscount * $bOGOHPCount;

                if (strlen(substr(strrchr($thisDiscount, "."), 1)) > 2) {
                    $thisDiscount = (int)((($thisDiscount) * 100) + 1) / 100;
                }
                // Using mathematics to round up to the nearest decimal because I couldn't find a ceiling function

                $totalDiscount += $thisDiscount;

            }
        }
        return $totalDiscount;
    }

    private function calculateDelivery($deliveryCharges)
    {
        foreach ($deliveryCharges as $delivery) {
            if ($this->total < $delivery['total']) {
                return $delivery['price'];
            }
        }
        return 0;
    }
}

$red = new Widget('Red Widget', 'R01', 32.95);
$green = new Widget('Green Widget', 'G01', 24.95);
$blue = new Widget('Blue Widget', 'B01', 7.95);

// Widget class set up to hold the data. Normally this would be read from a db.
// Use CamelCase for classname and lcase for variables and function names

$deliveryCharges = [
    ["total" => 50, "price" => 4.95],
    ["total" => 90, "price" => 2.95]
];

// Array set up to hold the data. 

$discounts = [
    ["code" => "R01", "percentage" => 50, "type" => "BOGOHP"]
];

// Test code change x to equal the basket contents you wish to test

$x = 1;

switch ($x) {
    case 1:
        $basket = new Basket();
        $basket->addWidget($blue);
        $basket->addWidget($green);
        var_dump($basket->getTotal($discounts, $deliveryCharges));
        break;
    case 2:
        $basket = new Basket();
        $basket->addWidget($red);
        $basket->addWidget($red);
        var_dump($basket->getTotal($discounts, $deliveryCharges));
        break;
    case 3:
        $basket = new Basket();
        $basket->addWidget($red);
        $basket->addWidget($green);
        var_dump($basket->getTotal($discounts, $deliveryCharges));
        break;
    case 4:
        $basket = new Basket();
        $basket->addWidget($blue);
        $basket->addWidget($blue);
        $basket->addWidget($red);
        $basket->addWidget($red);
        $basket->addWidget($red);
        var_dump($basket->getTotal($discounts, $deliveryCharges));
        break;
}


