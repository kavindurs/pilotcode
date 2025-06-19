@extends('layouts.admin')

@section('active-email-templates', 'menu-item-active')
@section('page-title', 'Edit Email Template')
@section('page-subtitle', 'Customize the email template content and styling.')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Edit Email Template</h1>

        <div class="mb-4">
            <p class="text-gray-600 dark:text-gray-400">Template: {{ $template->slug }}</p>
            <p class="text-gray-600 dark:text-gray-400">ID: {{ $template->id }}</p>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.email_templates.update', $template->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    Subject
                </label>
                <input type="text"
                       name="subject"
                       value="{{ $template->subject }}"
                       class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
                    Body
                </label>
                <textarea name="body"
                          rows="20"
                          class="w-full px-3 py-2 border rounded focus:outline-none focus:border-blue-500 dark:bg-gray-700 dark:text-white">{{ $template->body }}</textarea>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('admin.email_templates.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancel
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Update Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
