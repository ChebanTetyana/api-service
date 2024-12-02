<?php

namespace App\Contracts;

interface IProduct
{
    /**
     * Returns the rental price per unit of the item.
     *
     * @return float
     */

    public function getRentalPricePerHour(): float;

    /**
     * Returns the price of the item for purchase.
     *
     * @return float
     */

    public function getPurchasePrice(): float;

    /**
     * Get the product name.
     *
     * @return string
     */

    public function getName(): string;

    /**
     * Sets the price of the item for rent.
     *
     * @param float $price
     * @return void
     */

    public function setRentalPricePerHour(float $price): void;

    /**
     * Sets the price of the item for purchase.
     *
     * @param float $price
     * @return void
     */

    public function setPurchasePrice(float $price): void;
}
