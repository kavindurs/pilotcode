@extends('layouts.business')

@section('active-invitations', 'bg-blue-600')

@section('title', 'Review Invitation Details')
@section('page-title', 'Review Invitation Details')
@section('page-subtitle', 'View details for this review invitation')

@section('content')
    <div class="mb-6">
        <a href="{{ route('property.invitations') }}" class="text-blue-600 hover:text-blue-900">
            <i class="fas fa-arrow-left mr-1"></i> Back to Invitations
        </a>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Invitation Header -->
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Invitation to {{ $invitation->customer_name }}</h2>
                    <p class="text-gray-600">{{ $invitation->customer_email }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($invitation->status == 'sent') bg-blue-100 text-blue-800
                        @elseif($invitation->status == 'opened') bg-yellow-100 text-yellow-800
                        @elseif($invitation->status == 'clicked') bg-green-100 text-green-800
                        @elseif($invitation->status == 'failed') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($invitation->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Invitation Timeline -->
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-blue-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">Created</div>
                        <div class="text-sm text-gray-500">{{ $invitation->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>

                @if($invitation->sent_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-blue-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">Sent</div>
                        <div class="text-sm text-gray-500">{{ date('Y-m-d H:i:s', strtotime($invitation->sent_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->opened_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-yellow-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">Opened</div>
                        <div class="text-sm text-gray-500">{{ date('Y-m-d H:i:s', strtotime($invitation->opened_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->clicked_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-green-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">Clicked</div>
                        <div class="text-sm text-gray-500">{{ date('Y-m-d H:i:s', strtotime($invitation->clicked_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->expires_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full {{ strtotime($invitation->expires_at) < time() ? 'bg-red-500' : 'bg-gray-300' }} mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">Expires</div>
                        <div class="text-sm text-gray-500">
                            {{ date('Y-m-d H:i:s', strtotime($invitation->expires_at)) }}
                            @if(strtotime($invitation->expires_at) < time())
                                <span class="text-red-600 ml-2">(Expired)</span>
                            @else
                                <span class="text-gray-600 ml-2">({{ now()->diffInDays($invitation->expires_at) }} days left)</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Invitation Content -->
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Email Content</h3>

            <div class="mb-4">
                <div class="text-sm font-medium text-gray-700">Subject:</div>
                <div class="text-sm text-gray-900 border-b border-gray-200 pb-2">{{ $invitation->subject }}</div>
            </div>

            <div>
                <div class="text-sm font-medium text-gray-700">Message:</div>
                <div class="text-sm text-gray-900 whitespace-pre-line p-3 bg-gray-50 rounded-md mt-1">{{ $invitation->message }}</div>
            </div>

            @if($invitation->invitation_token)
            <div class="mt-4">
                <div class="text-sm font-medium text-gray-700">Invitation Link:</div>
                <div class="text-sm text-blue-600 break-all p-3 bg-gray-50 rounded-md mt-1">
                    {{ url('/review/' . $invitation->invitation_token) }}
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 flex justify-between">
            <div>
                @if($invitation->status == 'pending' || $invitation->status == 'failed')
                <form action="{{ route('property.invitations.resend', $invitation->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-paper-plane mr-1"></i> Resend Invitation
                    </button>
                </form>
                @endif
            </div>
            <div>
                <form action="{{ route('property.invitations.destroy', $invitation->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-red-300 text-red-700 rounded-md hover:bg-red-50"
                            onclick="return confirm('Are you sure you want to delete this invitation?')">
                        <i class="fas fa-trash mr-1"></i> Delete Invitation
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
