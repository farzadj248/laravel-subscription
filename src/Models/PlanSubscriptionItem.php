<?php

namespace Farzad\Subscription\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlanSubscriptionItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'plan_subscription_id',
        'plan_item_id',
        'item_slug',
        'item_name',
        'item_description',
        'used',
        'value',
        'valid_until'
    ];

    // get subscription item of plan-item
    public function item()
    {
        return $this->belongsTo(PlanItem::class);
    }

    // get subscription of plan
    public function subscription()
    {
        return $this->belongsTo(PlanSubscription::class);
    }


    // check subscription item has expired or not
    public function isExpired(): bool
    {
        return is_null($this->valid_until);
    }

    // increment used counter after use ability
    public function used()
    {
        if ($this->used <= $this->value)
            $this->increment('used');
    }

    // ability usage status
    public function canBeUse()
    {
        if ($this->used < $this->value)
            return true;
        return false;
    }
}
