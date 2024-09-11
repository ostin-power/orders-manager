<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'products';

    /**
     * Table fillable
     */
    protected $fillable = ['name', 'price'];

    //Many to many with orders table
    public function orders() {
        return $this->belongsToMany(Order::class)->withPivot('quantity')->withTimestamps();
    }
}
