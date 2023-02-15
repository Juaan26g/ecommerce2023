<?php

namespace App\Http\Livewire\Admin;

use App\Models\{Category, Subcategory};
use Illuminate\Support\Str;
use Livewire\Component;

class ShowCategory extends Component
{
    public $category, $subcategories;
    public $createForm = [
        'name' => null,
        'slug' => null,
        'color' => false,
        'size' => false,
    ];

    protected $rules = [
        'createForm.name' => 'required',
        'createForm.slug' => 'required|unique:subcategories,slug',
        'createForm.color' => 'required',
        'createForm.size' => 'required',
    ];

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',
        'createForm.color' => 'color',
        'createForm.size' => 'talla',
        ];

    public function save()
    {
        $this->validate();
    }
    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }
    public function mount(Category $category)
    {
        $this->category = $category;
        $this->getSubcategories();
    }

    public function getSubcategories()
    {
        $this->subcategories = Subcategory::where('category_id', $this->category->id)->get();
    }
    public function render()
    {
        return view('livewire.admin.show-category')->layout('layouts.admin');;
    }
}
