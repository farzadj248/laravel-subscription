<?php

namespace Farzad\Subscription\Traits;

use stdClass;
use Carbon\Carbon;
use Farzad\Subscription\Models\Plan;
use Farzad\Subscription\Models\PlanSubscription;
use Farzad\Subscription\Models\PlanSubscriptionItem;

trait SubscriptionsTrait
{
    public function planSubscriptions()
    {
        return $this->morphMany(PlanSubscription::class, 'subscriber_id','subscriber', 'subscriber_type');
    }

    // get date intervals
    private function intervalDates($interval, $period, $start){
        $date = new stdClass();
        $date->start_date = $start;
        switch($period){
            case "days":
                $date->end_date = $start->addDays($interval);
                break;
            case "month":
                $date->end_date = $start->addMonths($interval);
                break;
            case "year":
                $date->end_date = $start->addYears($interval);
                break;
            default:
                $date->end_date = $start->addMonths($interval);
                break;
        }
        return $date;
    }

    // check user is subscribed to this plan or not
    public function subscribedTo($plan_id): bool
    {
        $subscription = $this->planSubscriptions()->where('plan_id', $plan_id)->first();
        return $subscription && $subscription->isActive();
    }

    // subscribe to a new plan with items
    public function subscribe(Plan $plan): PlanSubscription
    {
        $trial = $this->intervalDates($plan->trial_duration, $plan->trial_duration_type, Carbon::now());
        $issue = $this->intervalDates($plan->package_duration, $plan->package_duration_type, $trial->end_date);

        // get all plan items
        $plan_items = $plan->items()->get();

        // create subscription
        $new_subscription = $this->planSubscriptions()->create([
            'plan_id' => $plan->id,
            'plan_slug' => $plan->slug,
            'plan_name' => $plan->name,
            'plan_description' => $plan->description,
            'trial_started_at' => $trial->start_date,
            'trial_expire_at' => $trial->end_date,
            'started_at' => $issue->start_date,
            'expire_at' => $issue->end_date
        ]);

        // add plan items to subscription items
        $subscription_items = [];
        foreach ($plan_items as $item){
            $item->subscribe();

            $item_duration = $this->intervalDates($item->pivot->item_duration, $item->pivot->item_duration_type, Carbon::now());
            $subscription_items[] = new PlanSubscriptionItem([
                'plan_subscription_id' => $new_subscription->id,
                'plan_item_id' => $item->id,
                'item_slug' => $item->slug,
                'item_name' => $item->name,
                'item_description' => $item->description,
                'used' => 0,
                'value' => $item->pivot->value,
                'valid_until' => $item_duration->end_date
            ]);
        }
        
        $new_subscription->subscriptionItems()->saveMany($subscription_items);

        return $new_subscription;
    }
}
