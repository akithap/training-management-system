<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Amex Training Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|outfit:400,600,800&display=swap" rel="stylesheet" />

        <!-- Styles / Tailwind -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            h1, h2, h3, .heading-font { font-family: 'Outfit', sans-serif; }
            
            /* Subtle animated background gradient */
            .bg-animated-gradient {
                background: linear-gradient(-45deg, #0ea5e9, #3b82f6, #1e3a8a, #0f172a);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
            }
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }
        </style>
    </head>
    <body class="antialiased text-gray-800 selection:bg-blue-500 selection:text-white">
        
        <div class="min-h-screen bg-animated-gradient flex flex-col justify-between">
            
            <!-- Navbar -->
            <nav class="p-6">
                <div class="max-w-7xl mx-auto flex justify-between items-center text-white">
                    <div class="flex items-center gap-3">
                        <!-- Generic abstract corporate logo -->
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span class="font-bold text-xl tracking-wide heading-font">Amex Corp</span>
                    </div>
                    
                    @if (Route::has('login'))
                        <div class="flex space-x-4 font-medium">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="hover:text-blue-200 transition-colors duration-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="hover:text-blue-200 transition-colors duration-200">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="hover:text-blue-200 transition-colors duration-200">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </nav>

            <!-- Main Hero Section -->
            <main class="flex-grow flex items-center justify-center p-6">
                <div class="max-w-4xl w-full mx-auto text-center">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 p-10 md:p-16 rounded-3xl shadow-2xl">
                        
                        <div class="inline-block px-4 py-1.5 rounded-full border border-blue-300/30 bg-blue-500/20 text-blue-100 text-sm font-semibold mb-6 tracking-wide uppercase">
                            Next-Generation Learning
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight heading-font">
                            Empower Your Team With <br/>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">Intelligent Training</span>
                        </h1>
                        
                        <p class="text-lg md:text-xl text-blue-100 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                            Welcome to the Amex Training Management System. Seamlessly schedule sessions, access core materials, and track professional growth to drive our corporate excellence.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="group relative px-8 py-4 bg-white text-blue-900 font-bold rounded-xl overflow-hidden shadow-lg hover:shadow-cyan-500/20 transition-all">
                                    <span class="relative z-10 flex items-center gap-2">
                                        Enter Dashboard
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="group relative px-8 py-4 bg-white text-blue-900 font-bold rounded-xl overflow-hidden shadow-lg hover:shadow-cyan-500/20 transition-all hover:-translate-y-1">
                                    <span class="relative z-10 flex items-center gap-2">
                                        Sign In to Portal
                                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                    </span>
                                </a>
                            @endauth
                        </div>
                        
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="p-6 text-center text-blue-200/60 text-sm">
                <p>&copy; {{ date('Y') }} Amex Corporation. All rights reserved.</p>
            </footer>
        </div>
    </body>
</html>
