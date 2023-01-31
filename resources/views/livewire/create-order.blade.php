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
        <div>
            <p class="mt-6 mb-3 text-lg text-gray-700 font-semibold">Envíos</p>
            <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center mb-4">
                <input type="radio" name="envio" class="text-gray-600">
                <span class="ml-2 text-gray-700">Recojo en tienda (Calle Falsa 123)</span>
                <span class="font-semibold text-gray-700 ml-auto">Gratis</span>
            </label>
            <label class="bg-white rounded-lg shadow px-6 py-4 flex items-center">
                <input type="radio" name="envio" class="text-gray-600">
                <span class="ml-2 text-gray-700">Envío a domicilio</span>
            </label>
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
