<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} - TapEats</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('menu') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white w-12 h-12 rounded-full flex items-center justify-center text-xl font-bold">
                        TE
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">TapEats</h1>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Order Status -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Order #{{ $order->id }}</h2>
                    <p class="text-gray-600">Placed on {{ $order->created_at->format('M d, Y - h:i A') }}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-semibold 
                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status === 'accepted') bg-blue-100 text-blue-800
                    @elseif($order->status === 'preparing') bg-purple-100 text-purple-800
                    @elseif($order->status === 'ready') bg-indigo-100 text-indigo-800
                    @elseif($order->status === 'out_for_delivery') bg-orange-100 text-orange-800
                    @elseif($order->status === 'completed') bg-green-100 text-green-800
                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            <!-- Progress Timeline -->
            <div class="relative pt-8 pb-4">
                <div class="absolute left-0 right-0 top-12 h-1 bg-gray-200">
                    <div class="h-full bg-orange-500 transition-all duration-500" 
                         style="width: {{ match($order->status) {
                             'pending' => '0%',
                             'accepted' => '20%',
                             'preparing' => '40%',
                             'ready' => '60%',
                             'out_for_delivery' => '80%',
                             'completed' => '100%',
                             default => '0%'
                         } }}"></div>
                </div>
                
                <div class="relative flex justify-between">
                    @foreach(['pending', 'accepted', 'preparing', 'ready', 'out_for_delivery', 'completed'] as $status)
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center mb-2
                            {{ $order->status === $status || 
                               (array_search($order->status, ['pending', 'accepted', 'preparing', 'ready', 'out_for_delivery', 'completed']) >= 
                                array_search($status, ['pending', 'accepted', 'preparing', 'ready', 'out_for_delivery', 'completed'])) 
                               ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($status === 'pending') 📝
                            @elseif($status === 'accepted') ✅
                            @elseif($status === 'preparing') 👨‍🍳
                            @elseif($status === 'ready') 🍽️
                            @elseif($status === 'out_for_delivery') 🚗
                            @elseif($status === 'completed') ✨
                            @endif
                        </div>
                        <span class="text-xs text-center text-gray-600">{{ ucfirst($status) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Supplier Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="font-bold text-lg mb-4">Supplier Information</h3>
            <div class="flex items-center space-x-4">
                @if($order->supplier->logo)
                <img src="{{ asset('storage/' . $order->supplier->logo) }}" alt="{{ $order->supplier->business_name }}" class="w-16 h-16 rounded-lg object-cover">
                @else
                <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-red-500 rounded-lg flex items-center justify-center">
                    <span class="text-white text-xl font-bold">{{ substr($order->supplier->business_name, 0, 2) }}</span>
                </div>
                @endif
                <div>
                    <h4 class="font-semibold text-lg">{{ $order->supplier->business_name }}</h4>
                    <p class="text-gray-600 text-sm">📍 {{ $order->supplier->region ?? 'N/A' }}</p>
                    @if($order->supplier->phone_number)
                    <p class="text-gray-600 text-sm">📞 {{ $order->supplier->phone_number }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer & Delivery Info -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="font-bold text-lg mb-4">Delivery Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Customer Name</p>
                    <p class="font-semibold">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Phone Number</p>
                    <p class="font-semibold">{{ $order->customer_phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Delivery Address</p>
                    <p class="font-semibold">{{ $order->delivery_address }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Region</p>
                    <p class="font-semibold">{{ $order->delivery_region }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Payment Method</p>
                    <p class="font-semibold">
                        @if($order->payment_method === 'cash')
                        💵 Cash on Delivery
                        @else
                        📱 Mobile Money
                        @endif
                    </p>
                </div>
                @if($order->notes)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Special Instructions</p>
                    <p class="font-semibold">{{ $order->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h3 class="font-bold text-lg mb-4">Order Items</h3>
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 pb-4 border-b last:border-b-0">
                    @if($item->food->image)
                    <img src="{{ asset('storage/' . $item->food->image) }}" alt="{{ $item->food->food_name }}" class="w-20 h-20 rounded-lg object-cover">
                    @else
                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                        <span class="text-3xl">🍽️</span>
                    </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="font-semibold">{{ $item->food->food_name }}</h4>
                        <p class="text-gray-600 text-sm">TSh {{ number_format($item->price, 0) }} × {{ $item->quantity }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-orange-500">TSh {{ number_format($item->subtotal, 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="font-bold text-lg mb-4">Order Summary</h3>
            <div class="space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span>TSh {{ number_format($order->total_amount, 0) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Delivery Fee</span>
                    <span>TSh 0</span>
                </div>
                <div class="border-t pt-2 mt-2"></div>
                <div class="flex justify-between font-bold text-xl">
                    <span>Total</span>
                    <span class="text-orange-500">TSh {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            @if($order->canBeCancelled())
            <div class="mt-6">
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition font-semibold">
                        Cancel Order
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Back to Menu -->
        <div class="mt-6 text-center">
            <a href="{{ route('menu') }}" class="inline-block bg-orange-500 text-white px-8 py-3 rounded-lg hover:bg-orange-600 transition font-semibold">
                Back to Menu
            </a>
        </div>
    </main>
</body>
</html>