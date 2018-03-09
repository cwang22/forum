<?php

use App\Channel;
use App\Thread;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        $this->channels()->content();
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Seed channels.
     *
     * @return $this
     */
    protected function channels()
    {
        collect(['PHP', 'Vue', 'Laravel Mix', 'Eloquent', 'Vuex'])
            ->each(function ($name) {
            factory(Channel::class)->create([
                'name' => $name,
                'slug' => strtolower($name)
            ]);
        });

        return $this;
    }

    /**
     * Seed content.
     *
     * @return $this
     */
    protected function content()
    {
        factory(Thread::class, 50)->states('from_existing_channels_and_users')->create()->each(function ($thread) {
            $this->recordActivity($thread, 'created', $thread->owner()->first()->id);
        });

        return $this;
    }

    /**
     * @param $model
     * @param $event_type
     * @param $user_id
     *
     * @throws ReflectionException
     */
    public function recordActivity($model, $event_type, $user_id)
    {
        $type = strtolower((new \ReflectionClass($model))->getShortName());
        $model->morphMany(\App\Activity::class, 'subject')->create([
            'user_id' => $user_id,
            'type' => "{$event_type}_{$type}"
        ]);
    }
}
