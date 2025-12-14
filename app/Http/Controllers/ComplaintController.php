<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\Collection;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    /**
     * Display list of user's complaints
     */
    public function index(Request $request)
    {
        $complaints = Complaint::where('user_id', $request->user()->id)
            ->with(['collection. serviceType'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('complaints.index', compact('complaints'));
    }

    /**
     * Show complaint creation form
     */
    public function create(Request $request)
    {
        $collections = Collection::where('user_id', $request->user()->id)
            ->with('serviceType')
            ->orderByDesc('scheduled_date')
            ->take(20)
            ->get();

        $selectedCollectionId = $request->query('collection_id');

        return view('complaints.create', compact('collections', 'selectedCollectionId'));
    }

    /**
     * Store a new complaint
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_id' => 'nullable|exists:collections,id',
            'category' => 'required|in:missed_collection,damaged_property,driver_behavior,billing,service_quality,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'priority' => 'required|in: low,medium,high,urgent',
        ]);

        // Verify collection belongs to user if provided
        if ($validated['collection_id']) {
            $collection = Collection:: findOrFail($validated['collection_id']);
            if ($collection->user_id !== $request->user()->id) {
                abort(403);
            }
        }

        $complaint = Complaint::create([
            'user_id' => $request->user()->id,
            'collection_id' => $validated['collection_id'],
            'ticket_number' => Complaint::generateTicketNumber(),
            'category' => $validated['category'],
            'subject' => $validated['subject'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        return redirect()->route('complaints.show', $complaint)
            ->with('success', 'Your complaint has been submitted.  Ticket number: ' . $complaint->ticket_number);
    }

    /**
     * Display a specific complaint
     */
    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $complaint->load(['collection.serviceType', 'assignee']);

        return view('complaints. show', compact('complaint'));
    }
}