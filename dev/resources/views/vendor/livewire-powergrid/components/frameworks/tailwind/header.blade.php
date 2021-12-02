<div class="md:flex md:flex-row w-full justify-between">

    <div class="md:flex md:flex-row w-full">

        <div>
            <x-livewire-powergrid::actions-header
                :theme="$theme"
                :actions="$this->headers"/>
        </div>

        <div class="flex flex-row">

            @if($exportOption)
                <div class="mr-2 mt-2 sm:mt-0">
                    @include(powerGridThemeRoot().'.export')
                </div>
            @endif

            <div wire:loading wire:target="search , filters.input_text.nombre , clearFilter ,sortBy , filterSelect , perPage, action-1">
                <img src='{{ asset('images/loader.gif') }}' class="h-10 w-10" />
            </div>

            @includeIf(powerGridThemeRoot().'.toggle-columns')

        </div>

        @includeIf(!$batchExporting, powerGridThemeRoot().'.loading')

    </div>

    @include(powerGridThemeRoot().'.search')

</div>

@include(powerGridThemeRoot().'.batch-exporting')

@include(powerGridThemeRoot().'.enabled-filters')

