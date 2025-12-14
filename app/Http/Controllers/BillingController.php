<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BillingController extends Controller
{
    /**
     * Display billing history
     */
    public function index(Request $request)
    {
        $payments = Payment::where('user_id', $request->user()->id)
            ->with(['collection. serviceType', 'subscription. subscriptionPlan', 'invoice'])
            ->orderByDesc('created_at')
            ->paginate(10);

        // Calculate totals
        $totalSpent = Payment::where('user_id', $request->user()->id)
            ->completed()
            ->sum('total_amount');

        $thisMonthSpent = Payment::where('user_id', $request->user()->id)
            ->completed()
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        return view('billing.index', compact('payments', 'totalSpent', 'thisMonthSpent'));
    }

    /**
     * Download invoice as PDF
     */
    public function downloadInvoice(Invoice $invoice)
    {
        // Check authorization
        if ($invoice->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $invoice->load(['payment', 'user']);

        $pdf = Pdf::loadView('billing.invoice-pdf', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}