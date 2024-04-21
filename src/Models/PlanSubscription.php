<?php

namespace Farzad\Subscription\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Farzad\Subscription\Models\PlanSubscriptionItem;

class PlanSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'subscriber_type',
        'plan_id',
        'plan_slug',
        'plan_name',
        'plan_description',
        'trial_started_at',
        'trial_expire_at',
        'started_at',
        'expire_at'
    ];

    // boot
    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($subscription) {
            $subscription->subscriptionItems()->delete();
        });
    }

    // plan
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    // subscriber
    public function subscriber()
    {
        return $this->morphTo('id', 'subscriber_id','subscriber','subscriber_type');
    }

    // susbcription items
    public function subscriptionItems()
    {
        return $this->hasMany(PlanSubscriptionItem::class);
    }

    // check subscription status
    public function isActive(): bool
    {
        return ! $this->isEnded();
    }

    // check if subscription is ended or not
    public function isEnded(): bool
    {
        return $this->expire_at ? Carbon::now()->gte($this->expire_at) : false;
    }
}
