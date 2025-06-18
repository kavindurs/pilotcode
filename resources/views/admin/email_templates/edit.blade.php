{{-- filepath: c:\xampp\htdocs\pilot\resources\views\admin\email_templates\edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'Edit Email Template')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-semibold mb-4">Edit Template: {{ $template->slug }}</h2>
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('admin.email_templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700">Subject</label>
            <input type="text" name="subject" value="{{ old('subject', $template->subject) }}" class="w-full px-3 py-2 border rounded">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700">Body</label>
            <textarea name="body" rows="10" class="w-full px-3 py-2 border rounded">{{ old('body', $template->body) }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Update Template</button>
    </form>
</div>
@endsection
