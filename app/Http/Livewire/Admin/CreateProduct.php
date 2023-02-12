<?php

namespace App\Http\Livewire\Admin;

use App\Models\{Category, Subcategory};
use Illuminate\Support\Str;
use Livewire\Component;

class CreateProduct extends Component
{
    public $name, $slug, $description;
    public $categories, $subcategories = [];
    public $category_id = '', $subcategory_id = '';
    public function mount()
    {
        $this->categories = Category::all();
    }

    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();
        $this->reset('subcategory_id');
    }
    public function render()
    {
        return view('livewire.admin.create-product')->layout('layouts.admin');
    }
    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }
}
