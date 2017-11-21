<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Route::get('/events', function () {
	return \App\Event::select('title', 'start', 'end', 'id', 'allDay')->get();
});

Route::delete('/event/delete/{id}', function ($id) {
	\App\Event::destroy($id);
});

Route::post('/event', function (\Illuminate\Http\Request $request) {
	// Validate

	$event = new \App\Event();

	$event->title = $request->title ?? "(Keine Beschreibung)";
	$event->start = $request->start;
	$event->end = $request->end;
	$event->allDay = $request->allDay;

	$event->save();

});

Route::put('/event', function (\Illuminate\Http\Request $request) {
	$event = \App\Event::find($request->id);

	$event->start = $request->start_time;
	$event->end =  $request->end_time;

	$event->save();

});

Route::put('/event/{id}', function ($id, \Illuminate\Http\Request $request) {
	$event = \App\Event::find($id);

	$event->title = $request->title;
	$event->start = $request->start;
	$event->end =  $request->end;
	$event->allDay =  $request->allDay;

	$event->save();
});