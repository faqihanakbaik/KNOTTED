@extends('layouts.admin')

@section('title', 'Custom Orders Management')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-serif text-espresso mb-1">Custom Orders</h1>
            <p class="text-mocha">Manage customer custom order requests and negotiations</p>
        </div>
    </div>

    <!-- Status Filter -->
    <div class="bg-white rounded-lg border border-oat p-4 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <a href="{{ route('admin.custom-orders.index') }}" 
               class="px-4 py-2 rounded-lg {{ !request('status') ? 'bg-espresso text-cream' : 'bg-cream text-mocha hover:bg-oat' }} transition">
                All ({{ $statusCounts['all'] }})
            </a>
            <a href="{{ route('admin.custom-orders.index', ['status' => 'pending']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') === 'pending' ? 'bg-espresso text-cream' : 'bg-cream text-mocha hover:bg-oat' }} transition">
                Pending ({{ $statusCounts['pending'] }})
            </a>
            <a href="{{ route('admin.custom-orders.index', ['status' => 'negotiating']) }}" 
               class="px-4 py-2 rounded-lg {{ in_array(request('status'), ['quoted', 'negotiating']) ? 'bg-espresso text-cream' : 'bg-cream text-mocha hover:bg-oat' }} transition">
                Negotiating ({{ $statusCounts['negotiating'] }})
            </a>
            <a href="{{ route('admin.custom-orders.index', ['status' => 'accepted']) }}" 
               class="px-4 py-2 rounded-lg {{ request('status') === 'accepted' ? 'bg-espresso text-cream' : 'bg-cream text-mocha hover:bg-oat' }} transition">
                Accepted ({{ $statusCounts['accepted'] }})
            </a>

            <!-- Search -->
            <form action="{{ route('admin.custom-orders.index') }}" method="GET" class="ml-auto flex gap-2">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                    class="px-4 py-2 border border-oat rounded-lg focus:ring-2 focus:ring-mocha focus:border-transparent">
                <button type="submit" class="px-4 py-2 bg-espresso text-cream rounded-lg hover:bg-mocha transition">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    <!-- Custom Orders List -->
    @if($customOrders->count() > 0)
    <div class="bg-white rounded-lg border border-oat overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cream border-b border-oat">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-mocha uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-mocha uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-oat">
                    @foreach($customOrders as $order)
                    <tr class="hover:bg-cream transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-espresso">{{ $order->user->name }}</div>
                            <div class="text-sm text-mocha">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-espresso">{{ $order->product_name }}</div>
                            <div class="text-sm text-mocha">Qty: {{ $order->quantity }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-mocha">
                            {{ ucfirst(str_replace('_', ' ', $order->request_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->final_agreed_price)
                            <div class="text-sm font-medium text-green-700">Rp {{ number_format($order->final_agreed_price, 0, ',', '.') }}</div>
                            @elseif($order->admin_quoted_price)
                            <div class="text-sm font-medium text-espresso">Rp {{ number_format($order->admin_quoted_price, 0, ',', '.') }}</div>
                            @elseif($order->customer_expected_price)
                            <div class="text-sm text-mocha">Rp {{ number_format($order->customer_expected_price, 0, ',', '.') }}</div>
                            @else
                            <div class="text-sm text-mocha">-</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status === 'pending') bg-gray-100 text-gray-800
                                @elseif($order->status === 'quoted') bg-blue-100 text-blue-800
                                @elseif($order->status === 'negotiating') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'accepted') bg-green-100 text-green-800
                                @elseif($order->status === 'rejected') bg-red-100 text-red-800
                                @elseif($order->status === 'cancelled') bg-gray-100 text-gray-600
                                @elseif($order->status === 'converted') bg-purple-100 text-purple-800
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-mocha">
                            {{ $order->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.custom-orders.show', $order) }}" 
                               class="text-mocha hover:text-espresso transition">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-oat">
            {{ $customOrders->links() }}
        </div>
    </div>
    @else
    <div class="bg-white rounded-lg border border-oat p-12 text-center">
        <i data-lucide="inbox" class="w-16 h-16 text-oat mx-auto mb-4"></i>
        <h3 class="text-lg font-medium text-espresso mb-2">No custom orders found</h3>
        <p class="text-mocha">
            @if(request('search'))
                Try adjusting your search terms
            @else
                Custom order requests will appear here
            @endif
        </p>
    </div>
    @endif
</div>

<script>
    lucide.createIcons();
</script>
@endsection
