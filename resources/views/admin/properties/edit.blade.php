@extends('layouts.popup')

@section('title', 'Edit Property')

@section('content')
<form action="{{ route('admin.properties.update', $property->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="block text-gray-700">Business Name</label>
        <input type="text" name="business_name" value="{{ old('business_name', $property->business_name) }}" class="w-full p-2 border border-gray-300 rounded">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">City</label>
        <input type="text" name="city" value="{{ old('city', $property->city) }}" class="w-full p-2 border border-gray-300 rounded">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Country</label>
        <input type="text" name="country" value="{{ old('country', $property->country) }}" class="w-full p-2 border border-gray-300 rounded">
    </div>
    <div class="mb-4">
        <label class="block text-gray-700">Business Email</label>
        <input type="email" name="business_email" value="{{ old('business_email', $property->business_email) }}" class="w-full p-2 border border-gray-300 rounded">
    </div>
    <!-- Add other property fields as needed -->
    <div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
            Update Property
        </button>
    </div>
</form>
@endsection
