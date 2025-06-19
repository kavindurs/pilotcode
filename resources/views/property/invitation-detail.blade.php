@extends('layouts.business')

@section('active-invitations', 'bg-blue-600')

@section('title', 'Review Invitation Details')
@section('page-title', 'Review Invitation Details')
@section('page-subtitle', 'View details for this review invitation')

@section('content')
    <div class="mb-6">
        <a href="{{ route('property.invitations') }}" class="text-blue-400 hover:text-blue-300">
            <i class="fas fa-arrow-left mr-1"></i> Back to Invitations
        </a>
    </div>

    <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden border border-gray-700">
        <!-- Invitation Header -->
        <div class="bg-gray-900 px-6 py-4 border-b border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-semibold text-white">Invitation to {{ $invitation->customer_name }}</h2>
                    <p class="text-gray-300">{{ $invitation->customer_email }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                        @if($invitation->status == 'sent') bg-blue-900 text-blue-300
                        @elseif($invitation->status == 'opened') bg-yellow-900 text-yellow-300
                        @elseif($invitation->status == 'clicked') bg-green-900 text-green-300
                        @elseif($invitation->status == 'failed') bg-red-900 text-red-300
                        @else bg-gray-700 text-gray-300 @endif">
                        {{ ucfirst($invitation->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Invitation Timeline -->
        <div class="px-6 py-4 border-b border-gray-700">
            <h3 class="text-lg font-medium text-white mb-3">Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-blue-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-white">Created</div>
                        <div class="text-sm text-gray-400">{{ $invitation->created_at->format('Y-m-d H:i:s') }}</div>
                    </div>
                </div>

                @if($invitation->sent_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-blue-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-white">Sent</div>
                        <div class="text-sm text-gray-400">{{ date('Y-m-d H:i:s', strtotime($invitation->sent_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->opened_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-yellow-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-white">Opened</div>
                        <div class="text-sm text-gray-400">{{ date('Y-m-d H:i:s', strtotime($invitation->opened_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->clicked_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full bg-green-500 mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-white">Clicked</div>
                        <div class="text-sm text-gray-400">{{ date('Y-m-d H:i:s', strtotime($invitation->clicked_at)) }}</div>
                    </div>
                </div>
                @endif

                @if($invitation->expires_at)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-5 w-5 rounded-full {{ strtotime($invitation->expires_at) < time() ? 'bg-red-500' : 'bg-gray-500' }} mt-1"></div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-white">Expires</div>
                        <div class="text-sm text-gray-400">
                            {{ date('Y-m-d H:i:s', strtotime($invitation->expires_at)) }}
                            @if(strtotime($invitation->expires_at) < time())
                                <span class="text-red-400 ml-2">(Expired)</span>
                            @else
                                <span class="text-gray-300 ml-2">({{ now()->diffInDays($invitation->expires_at) }} days left)</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Invitation Content -->
        <div class="px-6 py-4">
            <h3 class="text-lg font-medium text-white mb-3">Email Content</h3>

            <div class="mb-4">
                <div class="text-sm font-medium text-gray-300">Subject:</div>
                <div class="text-sm text-white border-b border-gray-600 pb-2">{{ $invitation->subject }}</div>
            </div>

            <div>
                <div class="text-sm font-medium text-gray-300">Message:</div>
                <div class="text-sm text-gray-200 whitespace-pre-line p-3 bg-gray-700 rounded-md mt-1 border border-gray-600">{{ $invitation->message }}</div>
            </div>

            @if($invitation->invitation_token)
            <div class="mt-4">
                <div class="text-sm font-medium text-gray-300">Invitation Link:</div>
                <div class="text-sm text-blue-400 break-all p-3 bg-gray-700 rounded-md mt-1 border border-gray-600">
                    {{ url('/property/' . $invitation->property_id) }}
                </div>
            </div>
            @endif
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-900 flex justify-between border-t border-gray-700">
            <div>
                @if($invitation->status == 'pending' || $invitation->status == 'failed')
                <form action="{{ route('property.invitations.resend', $invitation->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-1"></i> Resend Invitation
                    </button>
                </form>
                @endif
            </div>
            <div>
                <form action="{{ route('property.invitations.destroy', $invitation->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-red-500 text-red-400 rounded-md hover:bg-red-900 hover:bg-opacity-20 transition-colors"
                            onclick="return confirm('Are you sure you want to delete this invitation?')">
                        <i class="fas fa-trash mr-1"></i> Delete Invitation
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
