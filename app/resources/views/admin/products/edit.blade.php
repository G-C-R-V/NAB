<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl">Editar producto</h2></x-slot>
    <div class="p-6 max-w-2xl">
        <form method="POST" action="{{ route('products.update',$product) }}" class="space-y-3">
            @csrf @method('PUT')
            <label class="block">Nombre<input name="name" class="w-full border rounded p-2" value="{{ $product->name }}" /></label>
            <label class="block">Slug<input name="slug" class="w-full border rounded p-2" value="{{ $product->slug }}" /></label>
            <label class="block">Precio<input name="price" type="number" step="0.01" class="w-full border rounded p-2" value="{{ $product->price }}" /></label>
            <label class="block">Descripción<textarea name="description" class="w-full border rounded p-2">{{ $product->description }}</textarea></label>
            <label class="block">Imagen URL<input name="image_url" class="w-full border rounded p-2" value="{{ $product->image_url }}" /></label>
            <label class="block">Stock<input name="stock" type="number" class="w-full border rounded p-2" value="{{ $product->stock }}" /></label>
            <label><input type="checkbox" name="is_made_to_order" value="1" @checked($product->is_made_to_order)> A pedido</label>
            <label class="block">Lead time (horas)<input name="lead_time_hours" type="number" class="w-full border rounded p-2" value="{{ $product->lead_time_hours }}" /></label>
            <label><input type="checkbox" name="active" value="1" @checked($product->active)> Activo</label>
            <x-primary-button>Guardar</x-primary-button>
        </form>
    </div>
</x-app-layout>

