<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['config', 'event' => null]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['config', 'event' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div
    x-data="{
        chart: null,
        config: <?php echo \Illuminate\Support\Js::from($config)->toHtml() ?>,

        init() {
            let options = JSON.parse(JSON.stringify(this.config));
            this.setupFunctions(options);

            this.chart = new ApexCharts(this.$refs.canvas, options);
            this.chart.render();

            // Modo oscuro en el render inicial
            const isDark = document.documentElement.classList.contains('dark');
            this.chart.updateOptions({ theme: { mode: isDark ? 'dark' : 'light' } });

            // Reactivo al cambio de tema en caliente (toggle Flux / cualquier clase en <html>)
            const observer = new MutationObserver(() => {
                const dark = document.documentElement.classList.contains('dark');
                if (this.chart) {
                    this.chart.updateOptions({ theme: { mode: dark ? 'dark' : 'light' } });
                }
            });
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class'],
            });

            // Reactividad Livewire 4: actualiza series, labels y categorías
            this.$watch('config', (val) => {
                if (!val || !this.chart) return;
                this.chart.updateOptions({
                    series: val.series ?? [],
                    labels: val.labels ?? [],
                    xaxis: { categories: val.xaxis?.categories ?? [] },
                }, false, true);
            });
        },

        setupFunctions(options) {
            if (options.__formatter) {
                const preset = this.resolveFormatter(options.__formatter);
                this.dotSet(options, 'tooltip.y.formatter', preset);
                this.dotSet(options, 'dataLabels.formatter', preset);
                this.dotSet(options, 'yaxis.labels.formatter', preset);
                delete options.__formatter;
            }

            if (<?php echo \Illuminate\Support\Js::from($event)->toHtml() ?>) {
                this.dotSet(options, 'chart.events.dataPointSelection', (e, c, config) => {
                    $wire.dispatch(<?php echo \Illuminate\Support\Js::from($event)->toHtml() ?>, {
                        value: config.w.config.series[config.seriesIndex]?.data?.[config.dataPointIndex]
                            ?? config.w.config.series[config.dataPointIndex],
                        label: config.w.config.labels?.[config.dataPointIndex] ?? null,
                    });
                });
            }
        },

        /**
         * Resuelve presets seguros o acepta una expresión JS personalizada.
         * Los presets evitan el uso de new Function() con strings arbitrarios.
         *
         * ADVERTENCIA: las expresiones JS personalizadas se evalúan con new Function().
         * Usar solo con valores controlados por el desarrollador, nunca con input del usuario.
         */
        resolveFormatter(value) {
            const presets = {
                currency: (val) => '$ ' + Number(val).toLocaleString('es-GT', { minimumFractionDigits: 2 }),
                percent:  (val) => Number(val).toFixed(1) + ' %',
                compact:  (val) => {
                    if (val >= 1_000_000) return (val / 1_000_000).toFixed(1) + 'M';
                    if (val >= 1_000)     return (val / 1_000).toFixed(1) + 'K';
                    return val;
                },
            };

            if (presets[value]) return presets[value];

            // Expresión JS personalizada — solo para uso del desarrollador
            return new Function('val', 'return (' + value + ')(val)');
        },

        dotSet(obj, path, val) {
            path.split('.').reduce((acc, key, i, arr) =>
                acc[key] = (i === arr.length - 1) ? val : (acc[key] || {}), obj);
        },
    }"
    wire:ignore
    <?php echo e($attributes->class(['relative w-full h-full bg-zinc-100 dark:bg-zinc-700 rounded-xl p-4'])); ?>

>
    <div wire:loading.flex class="absolute inset-0 z-10 items-center justify-center bg-white/50 dark:bg-slate-900/50 backdrop-blur-xs rounded-xl">
        <div class="w-8 h-8 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <div x-ref="canvas"></div>
</div><?php /**PATH C:\laragon\www\proyecto-restaurante\resources\views/components/chart.blade.php ENDPATH**/ ?>