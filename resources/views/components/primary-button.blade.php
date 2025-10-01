<button 
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => '
            inline-flex items-center justify-center
            px-6 py-2.5
            rounded-lg font-semibold text-sm uppercase tracking-wide
            bg-gradient-to-r from-[#3F1A2B] via-[#B2183A] to-[#ED4A69]
            text-white shadow-md
            hover:from-[#B2183A] hover:to-[#FBB3C8]
            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#ED4A69]
            active:scale-95
            transition duration-200
        '
    ]) }}
>
    {{ $slot }}
</button>

