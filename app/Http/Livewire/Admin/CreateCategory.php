<?php

namespace App\Http\Livewire\Admin;

use App\Models\{Brand, Category};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateCategory extends Component
{
    use WithFileUploads;

    protected $validationAttributes = [
        'createForm.name' => 'nombre',
        'createForm.slug' => 'slug',
        'createForm.icon' => 'icono',
        'createForm.image' => 'imagen',
        'createForm.brands' => 'marcas',
    ];
    protected $rules = [
        'createForm.name' => 'required',
        'createForm.slug' => 'required|unique:categories,slug',
        'createForm.icon' => 'required',
        'createForm.image' => 'required|image|max:1024',
        'createForm.brands' => 'required',
    ];

    public $createForm = [
        'name' => null,
        'slug' => null,
        'icon' => null,
        'image' => null,
        'brands' => [],
    ];
    public $brands, $categories, $image;
    public function mount()
    {
        $this->getBrands();
        $this->getCategories();
        $this->image = 1;
    }
    public function getCategories()
    {
        $this->categories = Category::all();
    }
    public function updatedCreateFormName($value)
    {
        $this->createForm['slug'] = Str::slug($value);
    }
    public function getBrands()
    {
        $this->brands = Brand::all();
    }
    public function save()
    {
        $this->validate();

        $image = $this->createForm['image']->store('categories', 'public');
        $category = Category::create([
            'name' => $this->createForm['name'],
            'slug' => $this->createForm['slug'],
            'icon' => $this->createForm['icon'],
            'image' => $image
        ]);
        $category->brands()->attach($this->createForm['brands']);

        $this->image = 2;
        $this->reset('createForm');
        $this->getCategories();
        $this->emit('saved');
    }
    public function render()
    {
        return view('livewire.admin.create-category');
    }
}
