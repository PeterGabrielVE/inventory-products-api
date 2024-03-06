<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function all()
    {
        return Product::all();
    }

    public function find(int $id): ?Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->find($id);
        $product->update($data);
        return $product;
    }

    public function delete(int $id): void
    {
        $product = $this->find($id);
        $product->delete();
    }

}