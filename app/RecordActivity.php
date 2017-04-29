<?php


namespace App;


trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if(auth()-> guest()) return;
        foreach (static::getActivitiesRecord() as $event) {
            static::created(function ($model) use ($event) {
                $model->recordActivity($event);
            });

            static::deleting(function ($model) {
                $model->activities()->delete();
            });
        }
    }

    function recordActivity($event)
    {
        $this->activities()->create([
            'user_id' => auth()->id(),
            'type' => $this->getActivityType($event)
        ]);
    }

    protected static function getActivitiesRecord()
    {
        return ['created'];
    }

    protected function getActivityType($event)
    {
        $type = strtolower((new \ReflectionClass($this))->getShortName());
        return "{$event}_{$type}";
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }
}