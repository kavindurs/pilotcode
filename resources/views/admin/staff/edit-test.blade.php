@extends('layouts.admin')

@section('content')
<div class="p-4">
    <h1 class="text-white">Test Edit Page</h1>
    <p class="text-gray-300">Staff Member: {{ $staffMember->name }}</p>
    <p class="text-gray-300">Email: {{ $staffMember->email }}</p>
</div>
@endsection
