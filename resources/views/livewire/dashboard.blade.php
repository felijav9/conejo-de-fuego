<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid lg:grid-cols-3 gap-4 h-96">
        <div class="h-96"><x-chart :config="$mixed" /></div>
        <div class="h-96"><x-chart :config="$mixed" /></div>
        <div class="h-96"><x-chart :config="$mixed" /></div>
    </div>
    <div class="h-full">
        <x-chart :config="$chart2" />
    </div>
</div>