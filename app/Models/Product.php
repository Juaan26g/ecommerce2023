<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon as SupportCarbon;

class Product extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $fillable = ['name', 'slug', 'description', 'price', 'subcategory_id', 'brand_id', 'quantity','sold'];
    //protected $guarded = ['id', 'created_at', 'updated_at'];
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class)->withPivot('quantity','id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public static function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%");
    }

    public static function scopeCategoryFilter($query, $category)
    {
        return $query->whereHas('subcategory', function (Builder $query) use ($category) {
            $query->whereHas('category', function (Builder $query) use ($category) {
                $query->where('name', 'LIKE', "%{$category}%");
            });
        });
    }

    public static function scopeBrandFilter($query, $brand)
    {
        return $query->whereHas('brand', function (Builder $query) use ($brand) {
            $query->where('name', 'LIKE', "%{$brand}%");
        });
    }

  /*  public static function scopeFromFilter($query, $date){
        $date = Carbon::createFromFormat('m-d-Y', $date);

        $query->whereDate('created_at', '<=', $date);
    } */
    public function getStockAttribute()
    {
        if ($this->subcategory->size) {
            return ColorSize::whereHas('size.product', function (Builder $query) {
                $query->where('id', $this->id);
            })->sum('quantity');
        } elseif ($this->subcategory->color) {
            return ColorProduct::whereHas('product', function (Builder $query) {
                $query->where('id', $this->id);
            })->sum('quantity');
        } else {
            return $this->quantity;
        }
    }
}
