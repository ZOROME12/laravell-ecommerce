@extends('layouts.app')

@php
    $hideHero = true; // Make variable available to layout
@endphp

@section('contents')
<!-- AOS CSS -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<div class="bg-[#FBF8FB]">

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-r from-[#3F1A2B] to-[#B2183A] text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-20 text-center" data-aos="fade-down">
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-wide">About Us</h1>
            <p class="mt-4 text-lg md:text-xl text-pink-200 max-w-2xl mx-auto">
                Ease Print & Ease Flow – Creativity, Technology, and Care in Every Print
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-16 space-y-16">

        <!-- Who We Are -->
        <article data-aos="fade-up" class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition border-t-4 border-[#B2183A]">
            <h2 class="text-3xl font-bold text-[#3F1A2B] mb-4 border-b border-gray-200 pb-2">Who We Are</h2>
            <p class="text-gray-700 leading-relaxed">
                Ease Print is a trusted custom printing business based in Montalban, Rizal, dedicated to delivering
                high-quality, personalized print products that inspire creativity and meet the unique needs of every
                customer. From custom jerseys and aesthetic apparel to promotional materials and corporate
                branding, we combine craftsmanship, innovation, and customer service to turn ideas into reality.
            </p>
        </article>

        <!-- Our Story -->
        <article data-aos="fade-up" data-aos-delay="100" class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition border-t-4 border-[#ED4A69]">
            <h2 class="text-3xl font-bold text-[#3F1A2B] mb-4 border-b border-gray-200 pb-2">Our Story</h2>
            <p class="text-gray-700 leading-relaxed">
                Founded with the vision of making custom printing more accessible and enjoyable, Ease Print has
                been a creative partner for individuals, teams, and businesses alike. Over the years, we've built a
                reputation for reliability, attention to detail, and the ability to bring even the most complex designs to
                life. Proudly rooted in Montalban, Rizal, we have grown from a local service provider to a trusted
                name in the printing industry.
            </p>
        </article>

        <!-- Ease Flow -->
        <section data-aos="fade-left" class="bg-gradient-to-r from-[#FBB3C8] to-[#FBF8FB] border border-[#ED4A69] rounded-xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-[#B2183A] mb-4">Introducing Ease Flow</h2>
            <p class="text-gray-700 leading-relaxed mb-4">
                To further improve the way we serve our customers, we developed Ease Flow – an Online
                Reservation and Order Management System tailored specifically for Ease Print. This system
                simplifies the ordering process, allowing customers to:
            </p>
            <ul class="list-disc list-inside text-gray-700 space-y-1">
                <li>Place orders online for custom print products.</li>
                <li>Upload designs and choose materials with ease.</li>
                <li>Track order status in real time.</li>
                <li>Receive notifications for updates and completions.</li>
                <li>Schedule pick-ups or deliveries through an integrated calendar system.</li>
            </ul>
            <p class="text-gray-700 mt-4">
                With Ease Flow, we aim to make the customer experience faster, more convenient, and more
                transparent while helping our team work efficiently and error-free.
            </p>
        </section>

        <!-- Mission & Vision -->
        <section data-aos="zoom-in" class="grid md:grid-cols-2 gap-8">
            <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#B2183A] hover:scale-[1.02] transition-transform">
                <h3 class="text-2xl font-bold text-[#3F1A2B] mb-3">Our Mission</h3>
                <p class="text-gray-700 leading-relaxed">
                    To provide high-quality custom printing services that combine creativity, technology, and customer
                    care, ensuring every order reflects the vision of our clients.
                </p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-[#ED4A69] hover:scale-[1.02] transition-transform">
                <h3 class="text-2xl font-bold text-[#3F1A2B] mb-3">Our Vision</h3>
                <p class="text-gray-700 leading-relaxed">
                    To be the go-to custom printing brand known for innovation, reliability, and customer satisfaction –
                    both in-store and online.
                </p>
            </div>
        </section>

        <!-- Core Values -->
        <section>
            <h2 class="text-3xl font-bold text-[#3F1A2B] mb-8 text-center" data-aos="fade-up">Our Core Values</h2>
            <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $values = [
                        ['icon' => '🎯', 'title' => 'Quality', 'color' => '#B2183A', 'desc' => 'We ensure every product meets professional standards.'],
                        ['icon' => '💡', 'title' => 'Innovation', 'color' => '#ED4A69', 'desc' => 'We embrace new technologies to improve our services.'],
                        ['icon' => '🤝', 'title' => 'Customer-Centric', 'color' => '#B2183A', 'desc' => "We put our clients' needs first."],
                        ['icon' => '⚖️', 'title' => 'Integrity', 'color' => '#3F1A2B', 'desc' => 'We operate with honesty and transparency in every transaction.'],
                    ];
                @endphp

                @foreach($values as $index => $value)
                    <div data-aos="flip-left" data-aos-delay="{{ $index * 100 }}"
                         class="bg-white p-6 rounded-lg shadow-md text-center hover:shadow-lg transition border-b-4"
                         style="border-color: {{ $value['color'] }};">
                        <div class="text-3xl mb-2" style="color: {{ $value['color'] }}">{{ $value['icon'] }}</div>
                        <h3 class="font-semibold text-[#3F1A2B]">{{ $value['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $value['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </section>

    </div>
</div>

<!-- AOS JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        once: true
    });
</script>
@endsection
