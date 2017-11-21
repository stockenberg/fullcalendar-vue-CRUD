<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('events')->insert([
			'title' => str_random(10),
			'start' => \Illuminate\Support\Carbon::now()->addHour(2),
			'end' => \Illuminate\Support\Carbon::now()->addHour(3),
		]);
    }
}
