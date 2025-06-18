{{-- filepath: c:\xampp\htdocs\pilot\resources\views\admin\email_templates\index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Email Templates')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Email Templates</h2>
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($templates as $template)
            <tr>
                <td class="px-4 py-2 text-sm text-gray-700">{{ $template->slug }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">{{ $template->subject }}</td>
                <td class="px-4 py-2 text-sm text-gray-700">
                    <a href="{{ route('admin.email_templates.edit', $template->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
