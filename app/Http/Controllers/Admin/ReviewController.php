<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewStatusMail;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        // Get the 'tab' query param; default to 'pending'
        $tab = $request->query('tab', 'pending');

        // Set the status based on the tab.
        if ($tab === 'approved') {
            $status = 'Approved';
        } elseif ($tab === 'rejected') {
            $status = 'Rejected';
        } else {
            // For pending, use 'Pending Approval'
            $status = 'Pending Approval';
        }

        $reviews = Rate::where('status', $status)->paginate(10);

        return view('admin.reviews.index', compact('reviews', 'tab'));
    }

    public function approve($id)
    {
        $review = Rate::findOrFail($id);
        $review->status = 'Approved';
        $review->save();

        // Get the user who submitted the review.
        $user = User::find($review->user_id);

        // Send mail to the user.
        Mail::to($user->email)->send(new ReviewStatusMail($review, 'approved'));

        return redirect()->back()->with('success', 'Review approved and message sent.');
    }

    public function reject($id)
    {
        $review = Rate::findOrFail($id);
        $review->status = 'Rejected';
        $review->save();

        $user = User::find($review->user_id);

        Mail::to($user->email)->send(new ReviewStatusMail($review, 'rejected'));

        return redirect()->back()->with('success', 'Review rejected and message sent.');
    }
}
