<?php

namespace App\Models;

use App\Contracts\IProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements IProduct
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'rental_price_per_hour',
        'quantity'
    ];

    /**
     * Returns the rental price per unit of the item.
     *
     * @return float
     */

    public function getRentalPricePerHour(): float
    {
        return $this->rental_price_per_hour;
    }

    /**
     * Returns the price of the item for purchase.
     *
     * @return float
     */

    public function getPurchasePrice(): float
    {
        return $this->price;
    }

    /**
     * Get the product name.
     *
     * @return string
     */

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the price of the item for rent.
     *
     * @param float $price
     * @return void
     */

    public function setRentalPricePerHour(float $price): void
    {
        $this->rental_price_per_hour = $price;
        $this->save();
    }

    public function setPurchasePrice(float $price): void
    {
        $this->price = $price;
        $this->save();
    }
}
