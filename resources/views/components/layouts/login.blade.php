<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - OptiX POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-white min-h-screen flex">

{{-- Left Panel --}}
{{-- Left Panel --}}
<div class="hidden lg:flex w-2/5 bg-[#004080] flex-col justify-between p-12 relative overflow-hidden min-h-screen">
    
    {{-- Top: Logo --}}
    <div>

    </div>

    {{-- Middle: Image --}}
    <div class="flex items-center justify-center">
        <img src="{{ asset('assets/img/pageImg/78678687687.png') }}" alt="OptiX POS" class="w-72 object-contain drop-shadow-xl">
    </div>

    {{-- Bottom: Features --}}
    <div class="space-y-6">
        @foreach(['Complete Point of Sale — Barcode scanning, cart management, and multi-payment support', 'Repair Job Management — Track repairs from intake to delivery with technician assignments', 'Multi-Branch Inventory — Real-time stock across all branches with transfer management'] as $feature)
        <div class="flex items-start gap-3">
            <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-white font-medium text-sm">{{ explode(' — ', $feature)[0] }}</p>
                <p class="text-blue-200 text-xs mt-0.5">{{ explode(' — ', $feature)[1] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="absolute -bottom-10 -right-10 w-48 h-48 rounded-full border border-white/10"></div>
    <div class="absolute -bottom-5 right-10 w-24 h-24 rounded-full border border-white/10"></div>
</div>

{{-- Right Panel --}}
<div class="flex-1 flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 bg-white">
    <div class="max-w-md w-full mx-auto">
        <div class="lg:hidden mb-8 flex justify-center">
            <img src="{{ asset('assets/img/pageImg/56245445.png') }}" alt="OptiX" class="h-16 object-contain">
        </div>

        <h1 class="text-3xl font-semibold font-heading text-[#1A202C] mb-1">Welcome back</h1>
        <p class="text-[#64748B] text-sm mb-8">Sign in to your OptiX account</p>

        {{ $slot }}

        <p class="mt-8 text-center text-xs text-[#64748B]">
            Copyright © {{ date('Y') }} OptiX. All rights reserved.
            <br>
            <a href="#" class="hover:text-[#004080]">Design & Developed by plexCode</a>
        </p>
    </div>
</div>

</body>
</html>
