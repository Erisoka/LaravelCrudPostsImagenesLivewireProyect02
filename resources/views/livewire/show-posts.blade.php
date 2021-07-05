<div wire:init="loadPosts">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel Principal') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <x-table>

            {{-- Cantidad Reultados, Input de Búsqueda --}}
            <div class="px-6 py-4 flex items-center">
                <div class="flex items-center">
                    <span>Mostrar</span>
                    <select class="mx-2 form-control" {{$search != '' ? 'disabled' : ''}} wire:model="cantidad">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span>entradas</span>
                </div>
                <x-jet-input class="flex-1 ml-4 mr-4" type="text" placeholder="Ingrese búsqueda..."
                    wire:model="search" />

                @if ($search != '')
                    <span class="float-right mr-2">
                        <i class="fas fa-times" wire:click="$set('search','')"></i>
                    </span>
                @endif

                @livewire('create-post')

            </div>

            @if (count($posts))
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="w-24 cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('id')">
                                Id
                                {{-- Sort --}}
                                @if ($sort == 'id')
                                    @if ($direction == 'asc')
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-up-alt"></i>
                                        </span>
                                    @else
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-down-alt"></i>
                                        </span>
                                    @endif
                                @else
                                    <span class="float-right">
                                        <i class="fas fa-sort"></i>
                                    </span>
                                @endif
                            </th>
                            <th scope="col"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('title')">
                                Título
                                {{-- Sort --}}
                                @if ($sort == 'title')
                                    @if ($direction == 'asc')
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-up-alt mt-1"></i>
                                        </span>
                                    @else
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-down-alt mt-1"></i>
                                        </span>
                                    @endif
                                @else
                                    <span class="float-right">
                                        <i class="fas fa-sort mt-1"></i>
                                    </span>
                                @endif
                            </th>
                            <th scope="col"
                                class="cursor-pointer px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                wire:click="order('content')">
                                Contenido
                                {{-- Sort --}}
                                @if ($sort == 'content')
                                    @if ($direction == 'asc')
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-up-alt mt-1"></i>
                                        </span>
                                    @else
                                        <span class="float-right">
                                            <i class="fas fa-sort-alpha-down-alt mt-1"></i>
                                        </span>
                                    @endif
                                @else
                                    <span class="float-right">
                                        <i class="fas fa-sort mt-1"></i>
                                    </span>
                                @endif
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Editar</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($posts as $item)
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->id }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->title }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        {{ $item->content }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm font-medium flex float-right">
                                    {{-- Ya no se llamaran a los componentes individuales edit-post --}}
                                    {{-- @livewire('edit-post', ['post' => $post], key($post->id)) --}}
                                    <a class="btn btn-blue" wire:click="edit({{ $item }})">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a class="btn btn-red ml-2" wire:click="$emit('deletePost',{{ $item->id }})">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($posts->hasPages())
                    <div class="px-6 py-3">
                        {{ $posts->links() }}
                    </div>
                @endif
            @else
                <div wire:loading.remove class="px-6 py-4">
                    No hay coincidencias!
                </div>
            @endif

        </x-table>
        <div class="flex justify-center">
            <img wire:loading src="{{ asset('storage/img/loader01.gif') }}" class="w-60 p-10">
        </div>
    </div>

    {{-- Modal Editar Post --}}
    <x-jet-dialog-modal wire:model="open_edit">
        <x-slot name="title">
            Editar el post con Id: {{ $post->id }}
        </x-slot>

        <x-slot name="content">

            <div wire:loading wire:target="image"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Imagen cargando!</strong>
                <span class="block sm:inline">Espere un momento hasta que la imagen se haya procesado.</span>
            </div>

            @if ($image)
                <img class="mb-4" src="{{ $image->temporaryUrl() }}" width="640" height="480">
            @else
                <img class="mb-4" src="{{ Storage::url($post->image) }}" width="640" height="480">
            @endif

            <div class="mb-4">
                <x-jet-label value="Título del post" />
                <x-jet-input wire:model="post.title" type="text" class="w-full" />
                <x-jet-input-error for="post.title" />
            </div>

            <div>
                <div class="mb-4">
                    <x-jet-label value="Contenido del post" />
                    <textarea wire:model="post.content" class="form-control w-full" rows="6"></textarea>
                    <x-jet-input-error for="post.content" />
                </div>
            </div>

            <div>
                <input type="file" wire:model="image" id="{{ $identificador }}">
                <x-jet-input-error for="image" />
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$set('open_edit',false)">
                Cancelar
            </x-jet-secondary-button>

            <x-jet-danger-button wire:click="update" wire:loading.attr="disabled" wire:target="update, image"
                class="disabled:opacity-25">
                Actualizar
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    {{-- Modal SweetAlert2 Confirmación Eliminar Post --}}
    @push('js')
        <script src="sweetalert2.all.min.js"></script>

        <script>
            Livewire.on('deletePost', postId => {
                Swal.fire({
                    title: 'Está usted seguro de eliminar el registro?',
                    text: "Esta acción es irreversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, bórralo!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emitTo('show-posts','delete',postId)
                        Swal.fire(
                            'Eliminado!',
                            'El registro ha sido borrado.',
                            'success'
                        )
                    }
                })
            })
        </script>
    @endpush

</div>
