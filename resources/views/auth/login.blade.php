<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Vaibhav Embro</title>
    @if(file_exists(public_path('logo.png')))
        <link rel="icon" href="{{ asset('logo.png') }}?v={{ time() }}" type="image/png">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        * {
            border-radius: 0 !important;
        }
    </style>
</head>

<body class="bg-slate-50 h-screen w-full flex overflow-hidden">

    <!-- Left Side: Image Background -->
    <div class="hidden md:flex md:w-1/2 relative bg-indigo-900">
        <!-- Background Image -->
        <div class="absolute inset-0 bg-cover bg-center"
            style="background-image: url('{{ asset('login_bg.png') }}'); opacity: 0.85;"></div>

        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-indigo-900/90 via-indigo-900/40 to-transparent"></div>

        <!-- Text Content -->
        <div class="relative z-10 p-16 flex flex-col justify-between h-full">
            <div></div>

            <div class="mt-auto">
                <h1 class="text-white text-5xl font-extrabold tracking-tight mb-4 leading-tight">VAIBHAV<br>EMBRO</h1>
                <p class="text-indigo-100 text-lg font-medium max-w-md leading-relaxed">
                    Access the administrative dashboard to manage production, chalans, and financials seamlessly.
                </p>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-1/2 bg-white flex items-center justify-center p-8 lg:p-24 overflow-y-auto">
        <div class="w-full max-w-md">

            <div class="mb-10">
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight mb-2">Welcome Back</h2>
                <p class="text-slate-500 font-medium">Please enter your details to sign in.</p>
            </div>

            @if($errors->any())
                <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-4">
                    <p class="text-red-700 font-bold text-sm">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="flex flex-col gap-6">
                @csrf



                <!-- Username -->
                <div>
                    <label
                        class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Username</label>
                    <input type="text" name="username" placeholder="Enter your username" required
                        value="{{ old('username') }}"
                        class="w-full border border-slate-300 p-4 text-[15px] focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 placeholder-slate-400 font-bold bg-slate-50 text-slate-800 shadow-sm transition-all">
                </div>

                <!-- Password -->
                <div>
                    <label
                        class="block text-xs font-bold text-slate-600 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" placeholder="••••••••••••" required
                        class="w-full border border-slate-300 p-4 text-[15px] focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 placeholder-slate-400 font-bold bg-slate-50 text-slate-800 shadow-sm transition-all">
                </div>

                <!-- Button -->
                <div class="mt-4">
                    <button type="submit"
                        class="w-full border border-indigo-700 bg-indigo-600 text-white px-10 py-4 font-bold hover:bg-indigo-700 hover:shadow-md transition-all uppercase tracking-widest text-lg shadow-sm">
                        Login to Dashboard
                    </button>
                </div>

            </form>

            <div class="mt-12 text-center">
                <p class="text-xs text-slate-400 font-semibold tracking-wider">
                    SECURE ADMINISTRATIVE PORTAL
                </p>
            </div>

        </div>
    </div>

</body>

</html>