@extends('layouts.app')

@section('title', 'About Us - KNOTTED')

@section('content')
<!-- Hero -->
<section class="bg-gradient-to-br from-cream via-oat/30 to-cream py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="emoji-decor-large" aria-hidden="true">🧶</span>
        <h1 class="hero-title mb-6">Made by hand,<br>tied with love</h1>
        <p class="text-xl text-mocha leading-relaxed">
            Every stitch tells a story of craftsmanship, care, and creativity
        </p>
    </div>
</section>

<!-- Our Story -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-lg max-w-none">
            <h2 class="font-serif text-3xl font-bold text-espresso mb-6">Our Story</h2>
            <p class="text-espresso leading-relaxed mb-4">
                KNOTTED was born from a simple love for crochet and the joy it brings. What started as a hobby 
                in a small corner of our home has blossomed into a community of handmade enthusiasts who believe 
                in the beauty of slow, intentional creation.
            </p>
            <p class="text-espresso leading-relaxed mb-4">
                Each piece in our collection is meticulously handcrafted using premium cotton and acrylic yarns. 
                We take pride in every knot, every stitch, and every finished product that leaves our hands. 
                Our creations aren't mass-produced—they're made with patience, precision, and most importantly, love.
            </p>
            <p class="text-espresso leading-relaxed">
                Whether it's a cuddly amigurumi, a timeless bouquet of crochet flowers, or a stylish handmade bag, 
                every KNOTTED piece is designed to bring warmth, joy, and a touch of handmade charm to your life.
            </p>
        </div>
    </div>
</section>

<!-- Our Values -->
<section class="py-20 bg-cream">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-serif text-3xl font-bold text-espresso text-center mb-12">Our Values</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="feature-item bg-white rounded-lg p-8 border border-oat">
                <iconify-icon icon="solar:hand-heart-bold" class="text-mocha" style="font-size: 3rem;"></iconify-icon>
                <h3 class="font-serif text-xl font-bold text-espresso mb-3">Handmade with Love</h3>
                <p class="text-mocha">Every piece is crafted by hand with care and attention to detail</p>
            </div>
            <div class="feature-item bg-white rounded-lg p-8 border border-oat">
                <iconify-icon icon="solar:leaf-bold" class="text-mocha" style="font-size: 3rem;"></iconify-icon>
                <h3 class="font-serif text-xl font-bold text-espresso mb-3">Sustainable Choice</h3>
                <p class="text-mocha">Supporting slow fashion and eco-friendly alternatives to fast fashion</p>
            </div>
            <div class="feature-item bg-white rounded-lg p-8 border border-oat">
                <iconify-icon icon="solar:star-bold" class="text-mocha" style="font-size: 3rem;"></iconify-icon>
                <h3 class="font-serif text-xl font-bold text-espresso mb-3">Unique & Special</h3>
                <p class="text-mocha">Each piece is one-of-a-kind, making your purchase truly special</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-serif text-3xl font-bold text-espresso mb-6">Start Your Collection Today</h2>
        <p class="text-lg text-mocha mb-8">
            Discover unique handmade pieces that tell a story
        </p>
        <a href="{{ route('shop.index') }}" class="btn-primary text-lg px-8 py-4 inline-block">
            Shop Collection
        </a>
    </div>
</section>
@endsection
