<?php

namespace Farzad\Subscription\Models;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Farzad\Subscription\Models\Plan;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'plan_items';

    protected $fillable = [
        'plan_id',
        'slug',
        'name',
        'description',
        'value',
        'item_duration',
        'item_duration_type',
        'stock'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    // plan
    public function plan()
    {
        return $this->belongsToMany(Plan::class, 'plan_has_items');
    }

    // subscription usage
    public function subscriptionItems()
    {
        return $this->hasMany(PlanSubscriptionItem::class);
    }

    // decrement stock-ability
    public function subscribe()
    {
        if ($this->stock > 0)
            $this->decrement('stock');
    }

    // return stock-ability
    public function stockLeft()
    {
        return $this->stock;
    }
}
