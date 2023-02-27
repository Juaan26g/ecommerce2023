    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-600 leading-right">
                Lista de productos
            </h2>
            <x-button-link class="ml-auto" href="{{ route('admin.products.create') }}">
                Agregar producto
            </x-button-link>
        </div>
    </x-slot>

    <x-table-responsive>
        <div class="px-6 py-4">
            <x-jet-input dusk="adminsearch" class="w-full" wire:model="search" type="text"
                placeholder="Introduzca el nombre del producto a buscar" />
        </div>

        <select wire:model="pagination" class="rounded-lg">
            <option value="5">5</option>
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
        </select>



        <div @click.away="dropdownColumns = false" x-data="{ dropdownColumns: false }" class="relative inline-block">
            <x-button color="orange" @click="dropdownColumns = !dropdownColumns">
                <i class="fa-solid fa-table-columns"></i>
                <span class="ml-1">Columnas</span>
            </x-button>
            <div x-show="dropdownColumns" class="absolute left-0 w-40 mt-2 bg-gray-100 rounded-md shadow-xl">
                <span href="#" class="block px-4 py-2 text-sm">
                    @foreach ($columns as $column)
                        <input type="checkbox" wire:model="shownColumns" value="{{ $column }}">
                        <label>{{ $column }}</label>
                        <br />
                    @endforeach
                </span>
            </div>
        </div>

        <div @click.away="dropdownMenu = false"x-data="{ dropdownMenu: false }" class="inline-block">
            <x-button color="orange" @click="dropdownMenu = ! dropdownMenu"
                class="ml-2 flex items-center p-2 bg-white bg-gray-100 rounded-md">
                <i class="fa-solid fa-filter"></i>
                <span class="ml-4">Mostrar Filtros </span>
            </x-button>
            <div x-show="dropdownMenu" class="absolute left-1 py-2 mt-2 bg-white bg-gray-100 rounded-md shadow-xl">
                <aside>
                    <x-jet-input class="w1/3" wire:model="category" type="text" placeholder="Categoria a buscar" />



                   <!-- <x-jet-input type="date" wire:model="from" class="border border-gray-400 rounded-lg"
                        id="from" placeholder='DD/MM/YYYY'></x-jet-input>
                   -->
                    <x-jet-input class="w1/3" wire:model="brand" type="text" placeholder="Marca" />


                    <x-jet-input class="w1/3" wire:model="price" type="text" placeholder="Precio" />

                    <x-jet-button class="mt-4" wire:click="resetFilter">
                        Eliminar Filtros
                    </x-jet-button>
                </aside>
            </div>
        </div>

        @if ($products->count())
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        @if ($this->Column('Nombre'))
                            <th>
                                <x-button color="orange" wire:click="sortable('name')" scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre
                                </x-button>

                            </th>
                        @endif
                        @if ($this->Column('Categoria'))
                            <th>
                                <x-button color="orange" wire:click="sortable('subcategory_id')" scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoría
                                </x-button>
                            </th>
                        @endif
                        @if ($this->Column('Estado'))
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        @endif
                        @if ($this->Column('Precio'))
                            <th>
                                <x-button color="orange" wire:click="sortable('price')" scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio
                                </x-button>
                            </th>
                        @endif
                        @if ($this->Column('Marca'))
                            <th>
                                <x-button color="orange" wire:click="sortable('brand_id')" scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Marca
                                </x-button>
                            </th>
                        @endif
                        @if ($this->Column('NVendidos'))
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                n vendidos
                            </th>
                        @endif
                        @if ($this->Column('Stock'))
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                stock
                            </th>
                        @endif
                        @if ($this->Column('Fecha de creacion'))
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                fecha de creación
                            </th>
                        @endif
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Editar</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 object-cover">
                                        <img class="h-10 w-10 rounded-full"
                                            src="{{ $product->images->count() ? Storage::url($product->images->first()->url) : 'img/default.jpg' }}"
                                            alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $product->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @if ($this->Column('Nombre'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->subcategory->category->name }}
                                    </div>
                                    <!-- Añadido para que se vea la subcategoría -->
                                    <div class="text-sm text-gray-500">{{ $product->subcategory->name }}</div>
                                    <!-- Añadido para que se vea la subcategoría -->
                                </td>
                            @endif
                            @if ($this->Column('Estado'))
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $product->status == 1 ? 'red' : 'green' }}-100 text-{{ $product->status == 1 ? 'red' : 'green' }}-800">
                                        {{ $product->status == 1 ? 'Borrador' : 'Publicado' }}
                                    </span>
                            @endif
                            @if ($this->Column('Precio'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->price }} &euro;
                                </td>
                            @endif
                            @if ($this->Column('Marca'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->brand->name }}
                                </td>
                            @endif
                            @if ($this->Column('NVendidos'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->sold }}
                                </td>
                            @endif
                            @if ($this->Column('Stock'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->stock }}
                                </td>
                            @endif
                            @if ($this->Column('Fecha de creacion'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $product->created_at }}
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.products.edit', $product) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Editar</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                No existen productos coincidentes
            </div>
        @endif
        @if ($products->hasPages())
            <div class="px-6 py-4">
                {{ $products->links() }}
            </div>
        @endif
    </x-table-responsive>
    </div>
