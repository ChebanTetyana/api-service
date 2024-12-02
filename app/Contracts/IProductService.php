<?php

namespace App\Contracts;

use App\Models\Order;
use App\Models\User;

interface IProductService
{
    /**
     * Purchase a product.
     *
     * @param int $product_id The ID of the product to be purchased.
     * @param int $quantity The number of products to be purchased.
     * @param User $user The user who is making the purchase.
     *
     * @return array
     */
    public function purchase(int $product_id, int $quantity, User $user): array;

    /**
     * Rent a product for a specified number of hours.
     *
     * @param int $product_id The ID of the product to be rented.
     * @param int $hours The number of hours the product is being rented for.
     * @param User $user The user who is renting the product.
     *
     * @return array
     */

    public function rent(int $product_id, int $hours, User $user): array;

    /**
     * Extend an existing rental for a specified number of hours.
     *
     * @param Order $order The order related to the rental.
     * @param int $hours The number of hours to extend the rental by.
     * @param User $user The user who is requesting the extension.
     *
     * @return array
     */

    public function extendRental(Order $order, int $hours, User $user): array;
}
