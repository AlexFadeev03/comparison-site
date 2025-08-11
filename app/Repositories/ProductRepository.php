<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class ProductRepository
{
    public function getFilteredList(array $filters, array $witch = []): Collection
    {
        $query = Product::with($witch);
        if (Arr::get($filters, 'category_id')) {
            $query->filterCategory(Arr::get($filters, 'category_id'));
        }

        if (Arr::get($filters, 'subcategory_id')) {
            $query->filterSubcategory(Arr::get($filters, 'subcategory_id'));
        }

        if (Arr::get($filters, 'min_price') && Arr::get($filters, 'max_price')) {
            $query->filterPrice(Arr::get($filters, 'min_price'), Arr::get($filters, 'max_price'));
        }

        // Сортування
        $sort = Arr::get($filters, 'sort');
        $direction = Arr::get($filters, 'direction', 'asc');
        if ($sort === 'price') {
            $query->sortByPrice($direction);
        } elseif ($sort === 'rating') {
            $query->sortByRating($direction);
        } else {
            $query->sortByName($direction);
        }
        return $query->get();
    }

    public function getListByIds(\Illuminate\Support\Collection $idsList, array $witch = []): Collection
    {
        return Product::with($witch)
            ->whereIn('id', $idsList)
            ->get();
    }

    public function getItemOrFail(int $id, array $witch = []): Product
    {
        return Product::with($witch)->findOrFail($id);
    }

    public function createItem(array $data, $image = null): Product
    {
        if ($image) {
            $path = $image->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        }

        return Product::create($data);
    }

    public function updateItem(int $id, array $data, $image = null): void
    {
        $product = Product::findOrFail($id);
        if ($image) {
            // Видалити стару картинку, якщо є
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }
            $path = $image->store('products', 'public');
            $data['image'] = '/storage/' . $path;
        }
        $product->update($data);
    }

    public function destroyItem(int $id): void
    {
        $product = Product::findOrFail($id);
        // Видалити картинку з диска
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }
        $product->delete();
    }
}
