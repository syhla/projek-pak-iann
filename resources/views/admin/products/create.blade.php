@extends('layouts.app')

@section('content')
<x-app-layout>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-4">Add New Product</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="name" class="block">Product Name</label>
                <input type="text" name="name" id="name" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label for="description" class="block">Description</label>
                <textarea name="description" id="description" class="w-full border rounded p-2" required></textarea>
            </div>

            <div class="mb-4">
                <label for="price" class="block">Price</label>
                <input type="number" name="price" id="price" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-4">
                <label for="image" class="block">Image</label>
                <input type="file" name="image" id="image" class="w-full border rounded p-2">
            </div>

            <div class="mb-4">
                <label for="is_best_seller" class="inline-flex items-center">
                    <input type="checkbox" name="is_best_seller" id="is_best_seller" class="mr-2"> Best Seller
                </label>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        </form>
    </div>
</x-app-layout>
@endsection
