<x-guest-layout>
    <div class="max-w-5xl mx-auto py-8">
        <h1 class="text-2xl font-semibold mb-6">Tienda</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $p)
                <a href="{{ route('shop.show',$p->slug) }}" class="border rounded overflow-hidden hover:shadow">
                    <img src="{{ $p->image_url ?? 'https://picsum.photos/seed/'.$p->id.'/600/400' }}" alt="{{ $p->name }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <div class="font-medium">{{ $p->name }}</div>
                        <div class="text-sm text-gray-500">${{ number_format($p->price,2,',','.') }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-guest-layout>

