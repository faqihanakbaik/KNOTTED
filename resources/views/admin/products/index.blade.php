@extends('layouts.admin')

@section('page-title', 'Products Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-espresso">All Products</h2>
    <a href="{{ route('admin.products.create') }}" class="btn-primary">
        + Add New Product
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($products as $product)
                <tr>
                    <td>
                        <img src="{{ asset('images/products/' . $product->image) }}" 
                             alt="{{ $product->name }}" 
                             class="w-16 h-16 object-cover rounded"
                             onerror="this.src='https://via.placeholder.com/100x100/F5F0E8/342C28?text=Product'">
                    </td>
                    <td class="font-semibold">{{ $product->name }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="@if($product->stock <= 5) text-red-600 font-bold @endif">
                            {{ $product->stock }}
                        </span>
                    </td>
                    <td>
                        @if($product->is_available && $product->stock > 0)
                            <span class="badge badge-completed">Available</span>
                        @else
                            <span class="badge badge-cancelled">Unavailable</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-8 text-gray-500">No products found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
