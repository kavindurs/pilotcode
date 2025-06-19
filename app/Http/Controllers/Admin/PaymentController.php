<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use App\Models\Property;
use App\Models\Plan;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $plan = $request->input('plan');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $payments = Payment::query()
            ->with(['user', 'property', 'plan'])
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('business_email', 'like', "%{$search}%")
                      ->orWhere('order_id', 'like', "%{$search}%")
                      ->orWhere('transaction_id', 'like', "%{$search}%");
                });
            })
            ->when($status, function($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($plan, function($query) use ($plan) {
                $query->where('plan_id', $plan);
            })
            ->when($dateFrom, function($query) use ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($query) use ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $plans = Plan::all();
        $statuses = ['pending', 'confirmed', 'failed', 'cancelled'];

        return view('admin.payments.index', compact('payments', 'search', 'status', 'plan', 'dateFrom', 'dateTo', 'plans', 'statuses'));
    }

    public function show($id)
    {
        $payment = Payment::with(['user', 'property', 'plan'])->findOrFail($id);
        return view('admin.payments.show', compact('payment'));
    }

    public function edit($id)
    {
        $payment = Payment::with(['user', 'property', 'plan'])->findOrFail($id);
        $plans = Plan::all();
        $statuses = ['pending', 'confirmed', 'failed', 'cancelled'];

        return view('admin.payments.edit', compact('payment', 'plans', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $validatedData = $request->validate([
            'status' => 'required|in:pending,confirmed,failed,cancelled',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'business_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'payment_method' => 'nullable|string|max:255',
            'transaction_id' => 'nullable|string|max:255',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        // Set completed_at timestamp if status is confirmed
        if ($validatedData['status'] === 'confirmed' && $payment->status !== 'confirmed') {
            $validatedData['completed_at'] = now();
        } elseif ($validatedData['status'] !== 'confirmed') {
            $validatedData['completed_at'] = null;
        }

        $payment->update($validatedData);

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);

        // Only allow deletion of pending or failed payments
        if (!in_array($payment->status, ['pending', 'failed', 'cancelled'])) {
            return redirect()->route('admin.payments.index')->with('error', 'Cannot delete confirmed payments.');
        }

        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function confirm($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'confirmed',
            'completed_at' => now()
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment confirmed successfully.');
    }

    public function cancel($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'cancelled',
            'completed_at' => null
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment cancelled successfully.');
    }

    public function refund($id)
    {
        $payment = Payment::findOrFail($id);

        // Only allow refund of confirmed payments
        if ($payment->status !== 'confirmed') {
            return redirect()->route('admin.payments.index')->with('error', 'Can only refund confirmed payments.');
        }

        $payment->update([
            'status' => 'refunded',
            'completed_at' => null
        ]);

        return redirect()->route('admin.payments.index')->with('success', 'Payment refunded successfully.');
    }
}
