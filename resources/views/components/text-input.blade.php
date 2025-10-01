@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => '
            w-full px-4 py-2
            rounded-lg border border-[#FBB3C8]
            bg-[#FBF8FB] text-[#3F1A2B]
            placeholder-gray-400
            focus:border-[#B2183A] focus:ring-2 focus:ring-[#ED4A69] focus:ring-offset-1
            disabled:opacity-50 disabled:cursor-not-allowed
            shadow-sm transition duration-200
        '
    ]) }}
>
