<?php

namespace App\Exceptions;

final class EcommerceErrorException extends \Exception
{
    const PRODUCT_STOCK_EMPTY = "product.stock.empty";
    const ADDRESS_NOT_FOUND = "address.not.found";
}