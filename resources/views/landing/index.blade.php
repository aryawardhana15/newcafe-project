<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAFAZ coffee</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .masthead {
            background: url('/images/coffee-bg.jpg') no-repeat center center;
            background-size: cover;
            height: 100vh;
            position: relative;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 100%);
        }
    </style>
</head>
<body>
    <header class="masthead flex items-center justify-center relative overflow-hidden">
        <div class="overlay"></div>
        <div class="container text-center relative z-10 text-white px-4">
            <h1 class="text-6xl md:text-8xl font-bold uppercase mb-6 animate-fade-in-down font-serif">
            GAFAZ Coffee
            </h1>
            <h2 class="text-xl md:text-2xl text-gray-200 mb-8 animate-fade-in-up max-w-2xl mx-auto leading-relaxed">
                Experience the art of premium coffee. Crafted with passion, brewed to perfection.
            </h2>
            <a class="inline-block bg-[#d4a037] hover:bg-[#b8862f] text-white font-semibold py-3 px-8 rounded-full text-lg transition-all duration-300 transform hover:scale-105 animate-fade-in-up shadow-lg hover:shadow-xl" href="auth">
                Explore Our Menu
            </a>
        </div>

        <!-- Scrolling Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </div>
    </header>

    <!-- Tailwind CSS Animation Configuration -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in-down': 'fadeInDown 1s ease-out',
                        'fade-in-up': 'fadeInUp 1s ease-out',
                        'bounce': 'bounce 2s infinite',
                    },
                    keyframes: {
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        bounce: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        },
                    },
                },
            },
        };
    </script>
</body>
</html>