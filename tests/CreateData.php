<?php

namespace Tests;

use App\Models\{Brand,Category,Color,Image,Product,Size,Subcategory,User};


trait CreateData
{

    public function createCategory()
    {
        return Category::factory()->create();
    }

    public function createSubcategory($category, $color = false, $size = false)
    {
        return Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => $color,
            'size' => $size
        ]);
    }

    public function createBrand($category)
    {
        $brand = Brand::factory()->create();
        $category->brands()->attach($brand->id);
        return $brand;
    }

    public function createColor()
    {
        $color = Color::create(['name' => 'cyan']);
        return $color;
    }

    public function createSize($product)
    {
        $size = Size::factory(['name' => 'Talla X', 'product_id' => $product->id])->create();
        return $size;
    }

    public function createUser()
    {
       $user = User::factory()->create();
        return $user;
    }

    public function createProduct( $color = false, $size = false, $quantity = 5, $price = 20, $category_id = null,  $numImages = 1)
    {
        $category = $this->createCategory();

        if($category_id){
            $category->id = $category_id;
        }

        $subcategory = $this->createSubcategory($category, $color, $size);

        $brand = $this->createBrand($category);

        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $quantity,
            'price' => $price
        ]);

        Image::factory($numImages)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        if ($size && $color) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $productSize = $this->createSize($product);
            $productSize->colors()->attach($productColor->id, ['quantity' => $quantity]);
        } elseif ($color && !$size) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $product->colors()->attach($productColor->id, ['quantity' => $quantity]);
        }
        return $product;
    }








}
