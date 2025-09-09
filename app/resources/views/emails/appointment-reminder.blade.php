<p>Hola {{ $appointment->customer->name }},</p>
<p>Te recordamos tu turno #{{ $appointment->id }} para {{ $appointment->service->name }} con {{ $appointment->staff->display_name }} el {{ $appointment->start_at->format('d/m/Y H:i') }}.</p>
<p>Gracias!</p>

