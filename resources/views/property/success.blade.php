@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-4">
    <div class="text-center">
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Registration Complete!</h1>
        <p class="text-gray-600 mb-8">
            Your property has been successfully listed and is pending approval. We'll notify you once it's approved.
        </p>
        <a href="{{route('property.login')}}" class="inline-flex items-center bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
            Return to Home
        </a>
    </div>
</div>
@endsection
