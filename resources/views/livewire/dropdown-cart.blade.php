<div>
    <x-jet-dropdown width="96">
        <x-slot name="trigger">
            <span class="relative inline-block cursor-pointer">
            <x-cart size="30" color="white"></x-cart>
            <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2 "></span>
            </span>
            </x-slot>
        <x-slot name="content">
            <div class="py-6 px-4">
                <p class="text-center text-gray-700">
                No tiene agregado ningún item en el carrito
                </p>
                </div>
        </x-slot>
    </x-jet-dropdown>
</div>
