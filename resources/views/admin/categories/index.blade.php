@extends('layouts.admin')

@section('page-title', 'Categories Management')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-espresso">All Categories</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        + Add New Category
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Products Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($categories as $category)
                <tr>
                    <td class="font-semibold">{{ $category->name }}</td>
                    <td class="text-gray-600">
                        {{ $category->description ? Str::limit($category->description, 60) : '-' }}
                    </td>
                    <td>
                        <span class="badge badge-pending">{{ $category->products_count }} products</span>
                    </td>
                    <td>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Delete this category?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" {{ $category->products_count > 0 ? 'disabled' : '' }}>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-8 text-gray-500">No categories found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
