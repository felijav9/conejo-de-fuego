<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <flux:heading size="xl">
            Dashboard de Ventas
        </flux:heading>

        <flux:subheading>
            Control de ganancias y facturación
        </flux:subheading>
    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <flux:card>
            <div class="text-sm text-zinc-500">Ventas de hoy</div>
            <div class="text-2xl font-bold">
                Q {{ number_format($hoyTotal, 2) }}
            </div>
        </flux:card>

        <flux:card>
            <div class="text-sm text-zinc-500">Órdenes facturadas</div>
            <div class="text-2xl font-bold">
                {{ $hoyOrdenes }}
            </div>
        </flux:card>

    </div>

    {{-- GRAFICA --}}
    <flux:card>

        <div class="font-bold mb-4">
            Ventas últimos 7 días
        </div>

        <div id="chart"></div>

    </flux:card>

    {{-- LISTA DE FACTURAS --}}
    <flux:card>

        <div class="font-bold mb-4">
            Facturas del día
        </div>

        <div class="grid md:grid-cols-3 gap-3">

            @foreach($ordenes as $orden)

                <div
                    class="border rounded-xl p-3 cursor-pointer hover:bg-zinc-50"
                    wire:click="verFactura({{ $orden->id }})"
                >

                    <div class="font-bold">
                        {{ $orden->numero }}
                    </div>

                    <div class="text-sm text-zinc-500">
                        {{ $orden->mesa?->numero ?? 'Para llevar' }}
                    </div>

                    <div class="font-bold text-green-600">
                        Q {{ number_format($orden->total,2) }}
                    </div>

                </div>

            @endforeach

        </div>

    </flux:card>

    {{-- FACTURA DETALLE --}}
    @if($ordenSeleccionada)

        <flux:card>

            <div class="flex justify-between items-center mb-3">
                <div class="font-bold">
                    FACTURA {{ $ordenSeleccionada->numero }}
                </div>

                <flux:button wire:click="imprimir">
                    Imprimir / Descargar
                </flux:button>
            </div>

            <div class="text-sm text-zinc-500">
                Mesa: {{ $ordenSeleccionada->mesa?->numero ?? 'Para llevar' }}
            </div>

            <hr class="my-3">

            @foreach($ordenSeleccionada->items as $item)

                <div class="flex justify-between text-sm">
                    <span>
                        {{ $item->producto?->nombre }} x{{ $item->cantidad }}
                    </span>

                    <span>
                        Q {{ number_format($item->subtotal,2) }}
                    </span>
                </div>

            @endforeach

            <hr class="my-3">

            <div class="font-bold text-right">
                TOTAL: Q {{ number_format($ordenSeleccionada->total,2) }}
            </div>

        </flux:card>

    @endif

</div>

{{-- APEXCHARTS (ya sin CDN, asumiendo instalado por Vite) --}}
<script>
document.addEventListener('livewire:init', function () {

    const options = {
        chart: {
            type: 'bar',
            height: 350,
            toolbar: { show: false }
        },
        series: [{
            name: 'Ventas',
            data: @json($data)
        }],
        xaxis: {
            categories: @json($labels)
        },
        colors: ['#22c55e']
    };

    const chart = new ApexCharts(
        document.querySelector("#chart"),
        options
    );

    chart.render();

    Livewire.on('refreshChart', () => {
        chart.updateSeries([{ data: @json($data) }]);
    });

});
</script>