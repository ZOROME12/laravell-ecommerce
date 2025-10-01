<button 
    {{ $attributes->merge([
        'type' => 'button',
        'class' => '
            inline-flex items-center justify-center
            px-6 py-2
            bg-gradient-to-r from-easeDark via-easeRed to-easePink
            text-white font-semibold text-sm tracking-wide
            rounded-xl shadow-md
            hover:from-easeRed hover:to-easePink
            focus:outline-none focus:ring-2 focus:ring-easePink focus:ring-offset-2
            disabled:opacity-50 disabled:cursor-not-allowed
            transition-all duration-200
        '
    ]) }}
>
    {{ $slot }}
</button>
