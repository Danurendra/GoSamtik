<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\ServiceType;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $collections = Collection::where('user_id', $request->user()->id)
            ->with(['serviceType', 'driver. user'])
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->when($request->service_type, fn($q, $type) => $q->where('service_type_id', $type))
            ->orderByDesc('scheduled_date')
            ->paginate(10);

        $serviceTypes = ServiceType::active()->ordered()->get();

        return view('collections.index', compact('collections', 'serviceTypes'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::active()->ordered()->get();
        
        return view('collections.create', compact('serviceTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'scheduled_date' => 'required|date|after: today',
            'scheduled_time_start' => 'required|date_format:H:i',
            'scheduled_time_end' => 'required|date_format: H:i|after:scheduled_time_start',
            'service_address' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if date is a holiday
        if (Holiday::isHoliday($validated['scheduled_date'])) {
            return back()->withErrors(['scheduled_date' => 'Selected date is a holiday.  Please choose another date.']);
        }

        $serviceType = ServiceType::findOrFail($validated['service_type_id']);

        $collection = Collection::create([
            'user_id' => $request->user()->id,
            'service_type_id' => $validated['service_type_id'],
            'collection_type' => 'one_time',
            'scheduled_date' => $validated['scheduled_date'],
            'scheduled_time_start' => $validated['scheduled_time_start'],
            'scheduled_time_end' => $validated['scheduled_time_end'],
            'service_address' => $validated['service_address'],
            'notes' => $validated['notes'],
            'total_amount' => $serviceType->base_price,
            'status' => 'pending',
        ]);

        return redirect()->route('payments.checkout', $collection)
            ->with('success', 'Collection scheduled!  Please complete payment.');
    }

    public function show(Collection $collection)
    {
        $this->authorize('view', $collection);

        $collection->load(['serviceType', 'driver.user', 'rating', 'payment']);

        return view('collections.show', compact('collection'));
    }

    public function cancel(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        if (! $collection->canBeCancelled()) {
            return back()->with('error', 'This collection cannot be cancelled.');
        }

        $validated = $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $collection->update([
            'status' => 'cancelled',
            'cancellation_reason' => $validated['cancellation_reason'],
        ]);

        // TODO: Process refund if payment was made

        return redirect()->route('collections.index')
            ->with('success', 'Collection cancelled successfully.');
    }

    public function reschedule(Request $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        if (!$collection->canBeCancelled()) {
            return back()->with('error', 'This collection cannot be rescheduled.');
        }

        $validated = $request->validate([
            'scheduled_date' => 'required|date|after:today',
            'scheduled_time_start' => 'required|date_format:H:i',
            'scheduled_time_end' => 'required|date_format:H:i|after:scheduled_time_start',
        ]);

        if (Holiday::isHoliday($validated['scheduled_date'])) {
            return back()->withErrors(['scheduled_date' => 'Selected date is a holiday. ']);
        }

        $collection->update($validated);

        return back()->with('success', 'Collection rescheduled successfully.');
    }
}