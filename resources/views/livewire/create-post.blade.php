<div>
    {{-- <x-jet-button wire:click="$set('open',true)">
        Crear nuevo post
    </x-jet-button> --}}
  
    {{-- <button type="submit" class="btn2 btn2-green" wire:click="$set('open',true)">
        Crear nuevo post
    </button> --}}
    
    <button type="submit" class="btn btn-green ml-4" wire:click="$set('open',true)">
        Crear nuevo post
    </button>

    {{-- Componente que muestra un modal --}}
    <x-jet-dialog-modal wire:model="open">
        <x-slot name="title">
            Crear nuevo post
        </x-slot>

        <x-slot name="content">

            <div wire:loading wire:target="image"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado.</span>
            </div>

            @if ($image)
                <img class="mb-4" src="{{ $image->temporaryUrl() }}">
            @endif

            <div class="mb-4">
                <x-jet-label value="Título del post" />
                <x-jet-input type="text" class="w-full" wire:model="title" />
                <x-jet-input-error for="title" />
            </div>

            {{-- Inpunt Text Area con CKEditor --}}
            {{-- <div class="mb-4" wire:ignore>  --}}
            <div class="mb-4"> 
                <x-jet-label value="Contenido del post" />
                <textarea id="editor" wire:model.defer="content" class="form-control w-full" rows="6"></textarea>
                <x-jet-input-error for="content" />
            </div>

            <div>
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-jet-input-error for="image" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open',false)">
                Cancelar
            </x-jet-secondary-button>

            {{-- <x-jet-danger-button wire:click="save" wire:loading.remove wire:target="save"> --}}
            {{-- <x-jet-danger-button wire:click="save" wire:loading.class="bg-blue-500" wire:target="save"> --}}
            <x-jet-danger-button wire:click="save" wire:loading.attr="disabled" wire:target="save, image" class="disabled:opacity-25">
                Crear Post
            </x-jet-danger-button>

            {{-- <span wire:loading>Cargando...</span> --}}
            {{-- <span wire:loading wire:target="save">Cargando...</span> --}}
        </x-slot>
    </x-jet-dialog-modal>
</div>

@push('js')
    <script>
        Livewire.on('alert', function(message) {
            Swal.fire(
                'Excelente!',
                message,
                'success'
            )
        })
    </script>
@endpush
