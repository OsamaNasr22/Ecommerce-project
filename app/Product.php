<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;


class Product extends Model
{
    use SearchableTrait;
    //



    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 8,
            'products.description' => 2,
        ],
    ];

    public function presentPrice(){
        return '$'.number_format($this->price / 100, 2);
    }
    public function scopeSuggestions($query){
        return $query->inRandomOrder()->take(4);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class,'category_product');
    }
}
