@extends('layouts.app')

@section('title', 'Frequently Asked Questions')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif text-espresso mb-4">Frequently Asked Questions</h1>
        <p class="text-lg text-mocha">Everything you need to know about ordering from KNOTTED</p>
    </div>

    <!-- FAQ Sections -->
    <div class="space-y-8">
        <!-- General -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="help-circle" class="w-6 h-6 mr-3 text-mocha"></i>
                General Questions
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">What is KNOTTED?</h3>
                    <p class="text-mocha">KNOTTED is a handmade crochet brand specializing in unique, handcrafted items made with love. Each piece is carefully crafted by hand, ensuring quality and attention to detail.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Do you offer custom orders?</h3>
                    <p class="text-mocha">Yes! We love creating custom pieces. You can submit a custom order request through our Custom Order page, and we'll work with you to bring your vision to life.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">How long does it take to make a custom order?</h3>
                    <p class="text-mocha">Production time varies depending on the complexity of the design and our current workload. Typically, custom orders take 3-14 days. We'll provide an estimated timeline when we quote your custom order.</p>
                </div>
            </div>
        </div>

        <!-- Ordering Process -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="shopping-cart" class="w-6 h-6 mr-3 text-mocha"></i>
                Ordering Process
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">How do I place an order?</h3>
                    <p class="text-mocha">Browse our shop, add items to your cart, and proceed to checkout. You'll need to create an account or log in first. Follow the checkout process to complete your order.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Can I modify my order after placing it?</h3>
                    <p class="text-mocha">Please contact us as soon as possible if you need to modify your order. If production hasn't started yet, we may be able to accommodate changes.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">How does the custom order negotiation work?</h3>
                    <p class="text-mocha">After you submit a custom order request, we'll review it and send you a price quote. You can accept our quote, reject it, or make a counter offer. We'll negotiate until we reach an agreement that works for both of us.</p>
                </div>
            </div>
        </div>

        <!-- Payment -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="credit-card" class="w-6 h-6 mr-3 text-mocha"></i>
                Payment & Pricing
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">What payment methods do you accept?</h3>
                    <p class="text-mocha">We accept bank transfers and Cash on Delivery (COD) for local orders.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">How do I pay via bank transfer?</h3>
                    <p class="text-mocha">After placing your order, you'll see our bank account details. Transfer the exact amount and upload your payment proof in your order details page. We'll verify your payment and process your order.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Is COD available?</h3>
                    <p class="text-mocha">Yes, COD is available for eligible orders. Select COD during checkout, and you'll pay when you receive your order.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Why do custom orders have different prices?</h3>
                    <p class="text-mocha">Each custom order is unique, with varying complexity, materials, and time requirements. Prices are determined based on these factors and negotiated with each customer.</p>
                </div>
            </div>
        </div>

        <!-- Shipping & Delivery -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="truck" class="w-6 h-6 mr-3 text-mocha"></i>
                Shipping & Delivery
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">How long does shipping take?</h3>
                    <p class="text-mocha">Shipping time depends on your location and the courier service used. Typically, domestic shipping takes 2-5 business days after your order is processed.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Can I track my order?</h3>
                    <p class="text-mocha">Yes! Once your order is shipped, you can track its status in your Orders page. You'll also receive updates on your order status.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Do you ship internationally?</h3>
                    <p class="text-mocha">Currently, we only ship within Indonesia. International shipping may be available in the future.</p>
                </div>
            </div>
        </div>

        <!-- Care & Maintenance -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="heart" class="w-6 h-6 mr-3 text-mocha"></i>
                Care & Maintenance
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">How do I care for my crochet items?</h3>
                    <p class="text-mocha">Hand wash in cold water with mild detergent. Gently squeeze out excess water (don't wring), reshape, and lay flat to dry. Avoid hanging wet items as they may stretch.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Can I machine wash my items?</h3>
                    <p class="text-mocha">We recommend hand washing for best results and longevity. If you must machine wash, use a delicate cycle in a mesh laundry bag with cold water.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">What materials do you use?</h3>
                    <p class="text-mocha">We use high-quality cotton, acrylic, and wool yarns depending on the product. Each product description includes specific material information.</p>
                </div>
            </div>
        </div>

        <!-- Returns & Refunds -->
        <div class="bg-white rounded-lg border border-oat p-6">
            <h2 class="text-2xl font-serif text-espresso mb-6 flex items-center">
                <i data-lucide="rotate-ccw" class="w-6 h-6 mr-3 text-mocha"></i>
                Returns & Refunds
            </h2>
            
            <div class="space-y-6">
                <div>
                    <h3 class="font-medium text-espresso mb-2">Do you accept returns?</h3>
                    <p class="text-mocha">Due to the handmade nature of our products, we generally don't accept returns unless the item is defective or damaged upon arrival. Please contact us within 48 hours of receiving your order if there's an issue.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">What if my item arrives damaged?</h3>
                    <p class="text-mocha">We're sorry if that happens! Please contact us immediately with photos of the damage. We'll work with you to resolve the issue, either through a replacement or refund.</p>
                </div>

                <div>
                    <h3 class="font-medium text-espresso mb-2">Can I cancel my custom order?</h3>
                    <p class="text-mocha">You can cancel your custom order request before production begins. Once we've started working on your custom piece, cancellations may not be possible.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact CTA -->
    <div class="mt-12 bg-cream rounded-lg border border-oat p-8 text-center">
        <i data-lucide="message-circle" class="w-12 h-12 text-mocha mx-auto mb-4"></i>
        <h2 class="text-2xl font-serif text-espresso mb-2">Still have questions?</h2>
        <p class="text-mocha mb-6">We're here to help! Feel free to reach out to us.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center px-6 py-3 bg-espresso text-cream rounded-lg hover:bg-mocha transition">
            Contact Us
            <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
        </a>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
