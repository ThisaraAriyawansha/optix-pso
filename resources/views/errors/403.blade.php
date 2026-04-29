<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - OptiX POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F7FA] font-body min-h-screen flex items-center justify-center">

    <div class="w-full max-w-sm px-6">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-[#E2E8F0]">

            {{-- Red accent top bar --}}
            <div class="h-1 bg-[#DC2626]"></div>

            <div style="padding:48px 48px 44px;text-align:center;">
                {{-- Icon badge --}}
                <span style="display:inline-flex;align-items:center;justify-content:center;width:60px;height:60px;border-radius:16px;background:#FEF2F2;margin-bottom:28px;">
                    <svg width="28" height="28" fill="none" stroke="#DC2626" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </span>

                <p style="font-size:10px;font-weight:600;color:#DC2626;letter-spacing:.12em;text-transform:uppercase;margin-bottom:8px;">
                    403 — Access Denied
                </p>
                <h1 style="font-size:1.25rem;font-weight:600;color:#1A202C;margin-bottom:12px;">
                    Feature Not Enabled
                </h1>
                <p style="font-size:.875rem;color:#64748B;line-height:1.7;margin-bottom:36px;">
                    Your account doesn't have permission to access this section.
                    Contact your administrator to request access.
                </p>

                <div style="display:flex;gap:8px;">
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('dashboard') }}"
                    style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:4px;padding:8px 16px;border-radius:8px;border:1px solid #E2E8F0;font-size:.75rem;font-weight:500;color:#1A202C;text-decoration:none;background:#fff;transition:background .15s;"
                    onmouseover="this.style.background='#F5F7FA'" onmouseout="this.style.background='#fff'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </a>
                    <a href="{{ route('dashboard') }}"
                    style="flex:1;display:inline-flex;align-items:center;justify-content:center;gap:4px;padding:8px 16px;border-radius:8px;font-size:.75rem;font-weight:500;color:#fff;text-decoration:none;background:#004080;transition:background .15s;"
                    onmouseover="this.style.background='#003060'" onmouseout="this.style.background='#004080'">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>

        <p style="text-align:center;font-size:.75rem;color:#94A3B8;margin-top:20px;">OptiX POS &mdash; {{ date('Y') }}</p>
    </div>

</body>
</html>
