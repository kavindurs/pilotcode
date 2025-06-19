{{-- filepath: c:\xampp\htdocs\pilot\resources\views\admin\email_templates\index.blade.php --}}
@extends('layouts.admin')

@section('active-email-templates', 'menu-item-active')
@section('page-title', 'Email Templates')
@section('page-subtitle', 'Manage and customize email templates used throughout the platform.')

@section('content')
<div class="bg-gray-800 border border-gray-700 shadow-xl rounded-xl p-6">
    <h2 class="text-2xl font-semibold mb-6 text-white flex items-center">
        <i class="fas fa-envelope text-red-400 mr-3"></i>
        Email Templates
    </h2>
    
    @if(session('success'))
        <div class="bg-green-900 border border-green-700 text-green-300 p-4 rounded-lg mb-6 flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead class="bg-gray-900">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Template</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Subject</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Last Modified</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-800 divide-y divide-gray-700">
                @foreach($templates as $template)
                <tr class="hover:bg-gray-750 transition-colors duration-200">
                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-md bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-white text-sm"></i>
                            </div>
                            <div>
                                <div class="font-medium text-white">{{ ucfirst(str_replace('_', ' ', $template->slug)) }}</div>
                                <div class="text-gray-400 text-xs">{{ $template->slug }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-200">
                        <div class="max-w-xs">
                            <div class="truncate">{{ $template->subject }}</div>
                            @if(strlen($template->subject) > 50)
                                <div class="text-gray-400 text-xs mt-1">{{ Str::limit($template->subject, 50) }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-200">
                        <div class="text-xs">{{ $template->updated_at ? $template->updated_at->format('M d, Y') : 'N/A' }}</div>
                        <div class="text-gray-400 text-xs">{{ $template->updated_at ? $template->updated_at->format('H:i') : '' }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.email_templates.edit', $template->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white text-xs rounded-md transition-colors shadow-sm">
                            <i class="fas fa-edit mr-1.5"></i>
                            Edit Template
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($templates->isEmpty())
        <div class="text-center py-12">
            <div class="w-20 h-20 rounded-full bg-gray-700 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-envelope text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">No Email Templates</h3>
            <p class="text-gray-400">No email templates have been created yet.</p>
        </div>
    @endif
</div>
@endsection
