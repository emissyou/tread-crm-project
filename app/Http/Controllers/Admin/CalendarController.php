<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Contact;
use App\Models\Lead;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('first_name')->get();
        $leads    = Lead::orderBy('title')->get();
        $deals    = Deal::orderBy('title')->get();

        return view('admin.calendar.index', compact('contacts', 'leads', 'deals'));
    }

    public function events(Request $request)
    {
        $start = $request->get('start');
        $end   = $request->get('end');

        $events = CalendarEvent::with(['contact', 'lead', 'deal'])
            ->when($start, fn($q) => $q->where('end_datetime', '>=', $start))
            ->when($end,   fn($q) => $q->where('start_datetime', '<=', $end))
            ->get()
            ->map(function ($event) {
                return [
                    'id'          => $event->id,
                    'title'       => $event->title,
                    'start'       => $event->start_datetime->toIso8601String(),
                    'end'         => $event->end_datetime->toIso8601String(),
                    'allDay'      => $event->all_day,
                    'color'       => $event->color,
                    'description' => $event->description,
                    'type'        => $event->type,
                    'location'    => $event->location,
                    'contact'     => $event->contact?->full_name,
                    'extendedProps' => [
                        'description' => $event->description,
                        'type'        => $event->type,
                        'location'    => $event->location,
                        'contact'     => $event->contact?->full_name,
                        'lead'        => $event->lead?->title,
                        'deal'        => $event->deal?->title,
                    ],
                ];
            });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'type'           => 'required|in:meeting,call,task,reminder,follow_up,other',
            'color'          => 'nullable|string|max:20',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after_or_equal:start_datetime',
            'all_day'        => 'nullable|boolean',
            'location'       => 'nullable|string|max:255',
            'contact_id'     => 'nullable|exists:contacts,id',
            'lead_id'        => 'nullable|exists:leads,id',
            'deal_id'        => 'nullable|exists:deals,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        $data['created_by'] = auth()->id();
        $data['all_day'] = $request->boolean('all_day');

        $event = CalendarEvent::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully.',
            'event'   => $event,
        ]);
    }

    public function show(CalendarEvent $calendarEvent)
    {
        $calendarEvent->load(['contact', 'lead', 'deal']);
        return response()->json($calendarEvent);
    }

    public function edit(CalendarEvent $calendarEvent)
    {
        return response()->json($calendarEvent);
    }

    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validator = Validator::make($request->all(), [
            'title'          => 'required|string|max:200',
            'description'    => 'nullable|string',
            'type'           => 'required|in:meeting,call,task,reminder,follow_up,other',
            'color'          => 'nullable|string|max:20',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after_or_equal:start_datetime',
            'all_day'        => 'nullable|boolean',
            'location'       => 'nullable|string|max:255',
            'contact_id'     => 'nullable|exists:contacts,id',
            'lead_id'        => 'nullable|exists:leads,id',
            'deal_id'        => 'nullable|exists:deals,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $calendarEvent->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'event'   => $calendarEvent->fresh(),
        ]);
    }

    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();
        return response()->json(['success' => true, 'message' => 'Event deleted successfully.']);
    }
}
