@extends('layouts.app')

@section('title', 'Contact Us - KNOTTED')

@section('content')
<div class="bg-cream py-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h1 class="font-serif text-4xl font-bold text-espresso mb-4">Get in Touch</h1>
            <p class="text-mocha text-lg">We'd love to hear from you!</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-6 border border-oat">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Contact Information</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-mocha mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-espresso">Email</p>
                                <a href="mailto:faqihmuqoddam3007@gmail.com" class="text-mocha hover:text-espresso transition">faqihmuqoddam3007@gmail.com</a>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-mocha mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-espresso">Phone / WhatsApp</p>
                                <a href="https://wa.me/6285892992913" target="_blank" class="text-mocha hover:text-espresso transition">+62 858-9299-2913</a>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-mocha mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-espresso">Address</p>
                                <p class="text-mocha">Jakarta, Indonesia</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-mocha mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-espresso">Business Hours</p>
                                <p class="text-mocha">Monday - Saturday: 9AM - 6PM</p>
                                <p class="text-mocha text-sm">Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 border border-oat">
                    <h3 class="font-serif text-xl font-bold text-espresso mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://instagram.com/faqiiihhhhhh" target="_blank" class="w-10 h-10 bg-espresso text-cream rounded-full flex items-center justify-center hover:bg-mocha transition" title="Instagram">
                            <iconify-icon icon="mdi:instagram" class="text-cream" style="font-size: 1.25rem;"></iconify-icon>
                        </a>
                        <a href="https://github.com/faqihanakbaik" target="_blank" class="w-10 h-10 bg-espresso text-cream rounded-full flex items-center justify-center hover:bg-mocha transition" title="GitHub">
                            <iconify-icon icon="mdi:github" class="text-cream" style="font-size: 1.25rem;"></iconify-icon>
                        </a>
                        <a href="https://wa.me/6285892992913" target="_blank" class="w-10 h-10 bg-espresso text-cream rounded-full flex items-center justify-center hover:bg-mocha transition" title="WhatsApp">
                            <iconify-icon icon="mdi:whatsapp" class="text-cream" style="font-size: 1.25rem;"></iconify-icon>
                        </a>
                        <a href="https://www.linkedin.com/in/muhammad-faqih-muqoddam" target="_blank" class="w-10 h-10 bg-espresso text-cream rounded-full flex items-center justify-center hover:bg-mocha transition" title="LinkedIn">
                            <iconify-icon icon="mdi:linkedin" class="text-cream" style="font-size: 1.25rem;"></iconify-icon>
                        </a>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="bg-white rounded-lg p-6 border border-oat">
                <h3 class="font-serif text-xl font-bold text-espresso mb-6">Frequently Asked Questions</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold text-espresso mb-2">How long does shipping take?</h4>
                        <p class="text-mocha text-sm">We process orders within 1-2 business days. Shipping typically takes 3-5 business days depending on your location.</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-espresso mb-2">Can I request custom orders?</h4>
                        <p class="text-mocha text-sm">Yes! We love creating custom pieces. Please contact us via email or phone to discuss your ideas.</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-espresso mb-2">What are your products made of?</h4>
                        <p class="text-mocha text-sm">We use premium quality cotton and acrylic yarns that are safe, durable, and easy to care for.</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-espresso mb-2">Do you accept returns?</h4>
                        <p class="text-mocha text-sm">Yes, we accept returns within 7 days of delivery if the product is unused and in original condition.</p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-espresso mb-2">How do I care for my crochet items?</h4>
                        <p class="text-mocha text-sm">Hand wash with mild detergent in cool water. Lay flat to dry. Avoid wringing or harsh chemicals.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="mt-12 text-center bg-[#F5F0E8] rounded-lg p-8">
            <h3 class="font-serif text-2xl font-bold text-espresso mb-4">Have questions about an order?</h3>
            <p class="text-mocha mb-6">Check your order status in your account dashboard</p>
            @auth
                <a href="{{ route('customer.orders.index') }}" class="btn-primary inline-block">View My Orders</a>
            @else
                <a href="{{ route('login') }}" class="btn-primary inline-block">Login to Track Orders</a>
            @endauth
        </div>
    </div>
</div>
@endsection
