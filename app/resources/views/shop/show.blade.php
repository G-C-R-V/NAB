<x-guest-layout>
    <div class="max-w-3xl mx-auto py-8">
        <div class="flex flex-col sm:flex-row gap-6">
            <img src="{{ $product->image_url ?? 'https://picsum.photos/seed/'.$product->id.'/600/400' }}" alt="{{ $product->name }}" class="w-full sm:w-1/2 rounded">
            <div class="flex-1">
                <h1 class="text-2xl font-semibold">{{ $product->name }}</h1>
                <div class="text-gray-500 mb-2">${{ number_format($product->price,2,',','.') }}</div>
                <div class="prose dark:prose-invert max-w-none mb-4">{!! nl2br(e($product->description)) !!}</div>
                <form method="POST" action="{{ route('checkout.store') }}" class="flex items-end gap-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <label class="block text-sm font-medium">Cantidad</label>
                        <input type="number" name="qty" value="1" min="1" class="border rounded p-2 w-24">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        <input type="text" name="name" class="border rounded p-2" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Email</label>
                        <input type="email" name="email" class="border rounded p-2" required>
                    </div>
                    <button class="px-5 py-2 bg-emerald-600 text-white rounded">Encargar ahora</button>
                </form>
                <div class="text-xs mt-3 text-gray-500">
                    Seña: {{ (float)(\App\Models\Setting::get('orders.deposit_percent',50) ?? 50) }}% · Entrega estimada: {{ now()->addHours($product->lead_time_hours)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

