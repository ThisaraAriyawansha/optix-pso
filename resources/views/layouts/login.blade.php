<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — OptiX POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body bg-white min-h-screen flex">

{{-- Left Panel 40% --}}
<div class="hidden lg:flex w-2/5 bg-[#004080] flex-col p-12 relative">
    <div>
        <div class="text-3xl font-bold font-heading text-white mb-2">
            <span>Opti</span><span class="text-blue-300">X</span>
        </div>
        <p class="text-blue-200 text-sm font-medium mb-12">Smart POS for Computer Shops</p>

        <div class="space-y-5">
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-medium text-sm">Complete Point of Sale</p>
                    <p class="text-blue-200 text-xs mt-0.5">Barcode scanning, cart management, and multi-payment support</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-medium text-sm">Repair Job Management</p>
                    <p class="text-blue-200 text-xs mt-0.5">Track repairs from intake to delivery with technician assignments</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-medium text-sm">Multi-Branch Inventory</p>
                    <p class="text-blue-200 text-xs mt-0.5">Real-time stock across all branches with transfer management</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-auto">
        <p class="text-blue-200/60 text-xs">Version 1.0.0</p>
    </div>

    {{-- Decorative circles --}}
    <div class="absolute bottom-20 right-8 w-32 h-32 rounded-full border border-white/10"></div>
    <div class="absolute bottom-10 right-16 w-16 h-16 rounded-full border border-white/10"></div>
</div>

{{-- Right Panel 60% --}}
<div class="flex-1 flex flex-col justify-center px-8 md:px-16 lg:px-24 py-12 bg-white">
    <div class="max-w-md w-full mx-auto">
        {{-- Mobile logo --}}
        <div class="lg:hidden mb-8">
            <div class="text-2xl font-bold font-heading text-[#1A202C]">
                <span>Opti</span><span class="text-[#004080]">X</span>
            </div>
        </div>

        <h1 class="text-3xl font-semibold font-heading text-[#1A202C] mb-1">Welcome back</h1>
        <p class="text-[#64748B] text-sm mb-8">Sign in to your OptiX account</p>

        {{ $slot }}

        <p class="mt-8 text-center text-xs text-[#64748B]">
            © 2025 OptiX &nbsp;·&nbsp;
            <a href="#" class="hover:text-[#004080]">Privacy</a> &nbsp;·&nbsp;
            <a href="#" class="hover:text-[#004080]">Support</a>
        </p>
    </div>
</div>

</body>
</html>
