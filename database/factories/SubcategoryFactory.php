<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubcategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

       $category = Category::all()->random();
       $name = $this->faker->word();
        $slug = Str::slug($name);
        $color = Collect([true, false])->random();
        $size = Collect([true, false])->random();

        return [
            'category_id' => $category->id,
            'name' => $name,
            'slug' => $slug,
            'color' => $color,
            'size' => $color ? $size : false,
        /*'image' => 'subcategories/' . $this->faker->image(storage_path('app/public/subcategories'), 640, 480, null, false)*/
        ];
    }
}
