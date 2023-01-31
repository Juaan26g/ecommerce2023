<div class="container-menu py-8 grid grid-cols-5 gap-6">
    <div class="col-span-3">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="mb-4">
                <x-jet-label value="Nombre de contacto" />
                <x-jet-input type="text" placeholder="Introduzca el nombre de la persona que recibirá el pedido"
                    class="w-full" />
            </div>
            <div>
                <x-jet-label value="Teléfono de contacto" />
                <x-jet-input type="text" placeholder="Introduzca el teléfono de contacto" class="w-full" />
            </div>
        </div>
        <div x-data="{ envio_type: @entangle('envio_type') }">
            <p class="mt-6 mb-3 text-lg text-gray-700 font-semibold">Envíos</p>
            <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center mb-4">
                <input x-model="envio_type"  type="radio"  name="envio_type" value="1" class="text-gray-600">
                <span class="ml-2 text-gray-700">Recojo en tienda (Calle Falsa 123)</span>
                <span class="font-semibold text-gray-700 ml-auto">Gratis</span>
            </label>
            <div class="bg-white rounded-lg shadow">
                <label class="px-6 py-4 flex items-center">
                    <input x-model="envio_type"  type="radio" " name="envio_type" value="2" class="text-gray-600">
                    <span class="ml-2 text-gray-700">Envío a domicilio</span>
                </label>
                <div class="px-6 pb-6 grid grid-cols-2 gap-6" :class="{ 'hidden': envio_type != 2 }">
                    <div>
                        <x-jet-label value="Departamento" />
                        <select class="form-control w-full" wire:model="department_id">
                            <option value="" disabled selected>Seleccione un departamento</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>

                    </div>
                    <div>
                        <x-jet-label value="Distrito" />
                        <select class="form-control w-full" wire:model="district_id">
                            <option value="" disabled selected>Seleccione un distrito</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <x-jet-label value="Ciudad" />
                <select class="form-control w-full" wire:model="city_id">
                    <option value="" disabled selected>Seleccione una ciudad</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div>
            <x-jet-label value="Dirección" />
            <x-jet-input class="w-full" wire:model="address" type="text" />
        </div>
        <div class="col-span-2">
            <x-jet-label value="Referencia" />
            <x-jet-input class="w-full" wire:model="reference" type="text" />
        </div>

        <div>
            <x-jet-button class="mt-6 mb-4">
                Continuar con la compra
            </x-jet-button>
            <hr>
            <p class="text-sm text-gray-700 mt-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Commodi,
                maiores,
                porro. Accusantium architecto cum excepturi necessitatibus omnis ratione, rerum sed similique veniam.
                Dolorum iste, omnis
                repudiandae sunt tempora totam unde!
                <a href="" class="font-semibold text-orange-500">Políticas y privacidad</a>
            </p>
        </div>
    </div>
    <div class="col-span-2">
    </div>
</div>
