<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-gray-200 uppercase tracking-widest hover:bg-gray-700 focus:bg-gray600 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-gray-300 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
