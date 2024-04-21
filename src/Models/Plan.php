<?php

namespace Farzad\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Farzad\Subscription\Models\PlanItem;
use Illuminate\Database\Eloquent\SoftDeletes;
use Farzad\Subscription\Models\PlanSubscription;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];
    protected $table = 'plans';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'price',
        'trial_duration',
        'trial_duration_type',
        'package_duration',
        'package_duration_type',
        'subscriptions_limit',
        'issue_date',
        'sort_order',
        'is_active'
    ];

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($plan) {
            $plan->items()->delete();
        });
    }

    // plan items
    public function items()
    {
        return $this->belongsToMany(PlanItem::class, 'plan_has_items')->withPivot('value', 'item_duration', 'item_duration_type')->withTimestamps();
    }

    // subscriptions
    public function subscriptions()
    {
        return $this->hasMany(PlanSubscription::class);
    }

    // check plan is free or not
    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    // check plan is active or not
    public function isActive(): bool
    {
        return $this->is_active;
    }

    // add items to plan
    public function assignItems($items)
    {
        $assign_items = [];
        foreach($items as $item){
            $assign_items[$item->item_id] = [
                'value' => $item->value ?? null,
                'item_duration' => $item->item_duration ?? null,
                'item_duration_type' => $item->item_duration_type ?? null,
            ];
        }
        return $this->items()->sync($assign_items, false);
    }
}
