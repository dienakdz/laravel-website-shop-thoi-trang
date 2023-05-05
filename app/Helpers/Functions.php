<?php

use App\Models\clients\Categories;
use App\Models\clients\Brands;

function getAllCategories()
{
    $categories = new Categories;
    return $categories->getAll();
}

function getAllBrands()
{
    $brand = new Brands;
    return $brand->getAll();
}