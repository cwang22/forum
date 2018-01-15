<?php

namespace App;

trait RecordActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;
        foreach (static::getActivitiesRecord() as $event) {
            static::created(function ($model) use ($event) {
                $model->recordActivity($event);
            });

            static::deleting(function ($model) {
                $model->activities()->delete();
            });
        }
    }

    /**
     * Fetch all model events that require activity recording.
     *
     * @return array
     */
    protected static function getActivitiesRecord()
    {
        return ['created'];
    }

    /**
     * Record new activity for the model.
     *
     * @param string $event
     */
    protected function recordActivity($event)
    {
        $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    /**
     * Fetch the activity relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Determine the activity type.
     *
     * @param  string $event
     * @return string
     */
    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }
}