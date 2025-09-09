<x-guest-layout>
    <div class="max-w-3xl mx-auto py-8" x-data="booking()">
        <h1 class="text-2xl font-semibold mb-6">Reservá tu turno</h1>

        <form method="POST" action="{{ route('appointments.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Servicio</label>
                <select x-model="service_id" name="service_id" class="w-full border rounded p-2" @change="loadSlots()">
                    <option value="">Elegí un servicio</option>
                    @foreach($services as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->duration_minutes }}m)</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Barbero</label>
                <select x-model="staff_id" name="staff_id" class="w-full border rounded p-2" @change="loadSlots()">
                    <option value="">Cualquiera</option>
                    @foreach($staff as $st)
                        <option value="{{ $st->id }}">{{ $st->display_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Día</label>
                <input type="date" x-model="date" class="border rounded p-2" @change="loadSlots()">
            </div>

            <template x-if="slots.length">
                <div>
                    <label class="block text-sm font-medium mb-2">Horarios disponibles</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <template x-for="s in slots" :key="s.start_at">
                            <button type="button" class="px-3 py-2 rounded border" :class="selected_start_at===s.start_at?'bg-indigo-600 text-white':'bg-white'" @click="selected_start_at=s.start_at; staff_id = s.staff_id;">
                                <div x-text="new Date(s.start_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'})"></div>
                            </button>
                        </template>
                    </div>
                    <input type="hidden" name="start_at" x-model="selected_start_at">
                </div>
            </template>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre</label>
                    <input type="text" name="name" class="w-full border rounded p-2" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" class="w-full border rounded p-2" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Notas</label>
                <textarea name="notes" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="flex items-center justify-between">
                <div class="text-xs text-gray-500">
                    Seña: {{ (float)(\App\Models\Setting::get('appointments.deposit_percent',30) ?? 30) }}% · Ventana cancelación: {{ (int)(\App\Models\Setting::get('appointments.cancel_window_hours',48) ?? 48) }}h
                </div>
                <button class="px-5 py-2 bg-indigo-600 text-white rounded" :disabled="!selected_start_at || !service_id">Continuar</button>
            </div>
        </form>
    </div>

    <script>
        function booking(){
            return {
                service_id: '', staff_id: '', date: new Date().toISOString().slice(0,10),
                slots: [], selected_start_at: '',
                async loadSlots(){
                    if(!this.service_id || !this.date) { this.slots = []; return; }
                    const params = new URLSearchParams({service_id: this.service_id, date: this.date});
                    if(this.staff_id) params.append('staff_id', this.staff_id);
                    const r = await fetch(`{{ route('appointments.slots') }}?${params.toString()}`);
                    this.slots = await r.json();
                    this.selected_start_at = '';
                }
            }
        }
    </script>
</x-guest-layout>

