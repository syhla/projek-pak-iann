@extends('layouts.app')

@section('content')
<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <a href="{{ route('admin.products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Product</a>
        </div>

        @foreach ($products as $product)
            <div class="mb-4 bg-white shadow rounded-lg p-4">
                <h3 class="font-bold">{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p class="text-gray-500">Price: ${{ $product->price }}</p>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-500">Edit</a> |
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500">Delete</button>
                </form>
            </div>
        @endforeach
    </div>
</x-app-layout>
@endsection
