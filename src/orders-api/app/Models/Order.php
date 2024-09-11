<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';

    /**
     * Table fillable
     */
    protected $fillable = ['name', 'description', 'date'];

    //Many to many with products table
    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }
}
