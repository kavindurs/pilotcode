@extends('layouts.admin')

@section('active-staff', 'menu-item-active')
@section('page-title', 'Edit Staff Member')
@section('page-subtitle', 'Update staff member information, role and permissions')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-white">Basic Edit Form</h1>
    <p class="text-gray-300">Name: {{ $staffMember->name }}</p>
    <p class="text-gray-300">Status: {{ $staffMember->status }}</p>
    <p class="text-gray-300">Role: {{ $staffMember->role }}</p>

    <form action="{{ route('admin.staff.update', $staffMember->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="text-white">Name:</label>
                <input type="text" name="name" value="{{ $staffMember->name }}" class="bg-gray-700 text-white p-2 rounded">
            </div>

            <div>
                <label class="text-white">Status:</label>
                <select name="status" class="bg-gray-700 text-white p-2 rounded">
                    <option value="active" {{ $staffMember->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $staffMember->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="suspended" {{ $staffMember->status == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
        </div>
    </form>
</div>
@endsection
