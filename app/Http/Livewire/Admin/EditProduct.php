<?php

namespace App\Http\Livewire\Admin;

use App\Models\{Product, Category,Brand, Subcategory};
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\{Str,Facades\Storage};
use Livewire\Component;

class EditProduct extends Component
{
    public $product, $categories, $subcategories, $brands;
    public $category_id;
    protected $listeners = ['refreshProduct', 'delete'];

    protected $rules = [
        'category_id' => 'required',
        'product.subcategory_id' => 'required',
        'product.name' => 'required',
        'product.slug' => 'required|unique:products',
        'product.description' => 'required',
        'product.brand_id' => 'required',
        'product.price' => 'required',
        'product.quantity' => 'numeric',
    ];
    public function mount(Product $product)
    {
        $this->product = $product;

        $this->categories = Category::all();
        $this->category_id = $product->subcategory->category->id;
        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();
        $this->brands = Brand::whereHas('categories', function (Builder $query) {
            $query->where('category_id', $this->category_id);
        })->get();
    }
    public function delete()
    {
        $images = $this->product->images;
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->url);
            $image->delete();
        }
        $this->product->delete();
        return redirect()->route('admin.index');
    }

    public function refreshProduct()
    {
        $this->product = $this->product->fresh();
    }

    public function updatedProductName($value)
    {
        $this->product->slug = Str::slug($value);
    }
    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();
        $this->product->subcategory_id = '';
        $this->product->brand_id = '';
    }
    public function getSubcategoryProperty()
    {
        return Subcategory::find($this->product->subcategory_id);
    }
    public function save()
    {
        $this->rules['product.slug'] = 'required|unique:products,slug,' . $this->product->id;
        if ($this->product->subcategory_id) {
            if (!$this->subcategory->color && !$this->subcategory->size) {
                $this->rules['product.quantity'] = 'required|numeric';
            }
        }
        $this->validate();
        $this->product->save();
    }
    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
