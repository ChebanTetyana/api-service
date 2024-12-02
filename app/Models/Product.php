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
     * Get rental price per hour.
     *
     * @return float
     */

    public function getRentalPricePerHour(): float
    {
        return $this->attributes['rental_price_per_hour'];
    }

    /**
     * Returns the rental price per unit of the item.
     *
     * @return float
     */
    public function getPurchasePrice(): float
    {
        return $this->attributes['price'];
    }

    /**
     * Get the product name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->attributes['name'];
    }

    /**
     * Set the rental price per hour.
     *
     * @param float $price
     * @return void
     */
    public function setRentalPricePerHour(float $price): void
    {
        $this->attributes['rental_price_per_hour'] = $price;
        $this->save();
    }

    /**
     * Set the purchase price of the product.
     *
     * @param float $price
     * @return void
     */
    public function setPurchasePrice(float $price): void
    {
        $this->attributes['price'] = $price;
        $this->save();
    }
}
