<!-- Add to head section -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          coffee: {
            50: '#faf6f0',
            100: '#f5ece0',
            200: '#e6d5b8',
            300: '#d4b997',
            400: '#c49a6d',
            500: '#b58154',
            600: '#8e6542',
            700: '#684c31',
            800: '#423220',
            900: '#211910',
          }
        },
        fontFamily: {
          'sans': ['"Open Sans"', 'sans-serif'],
          'display': ['"Playfair Display"', 'serif']
        }
      }
    }
  }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<!-- Premium Hero Section -->
<section id="hero" class="relative pt-32 pb-24 bg-gradient-to-br from-coffee-50 to-coffee-100 overflow-hidden">
  <!-- Decorative elements -->
  <div class="absolute top-0 left-0 w-full h-full opacity-10">
    <div class="absolute top-20 left-10 w-40 h-40 rounded-full bg-coffee-300"></div>
    <div class="absolute bottom-10 right-20 w-60 h-60 rounded-full bg-coffee-200"></div>
  </div>
  
  <div class="container relative">
    <div class="row align-items-center">
      <div class="col-lg-6 order-lg-1 d-flex flex-column justify-content-center" data-aos="fade-up">
        <div class="text-center text-lg-start px-4">
          <h1 class="text-5xl md:text-6xl lg:text-7xl font-display font-bold text-coffee-800 mb-6 leading-tight">
            Discover <span class="text-coffee-600">ProjectCoffee</span> Excellence
          </h1>
          <p class="text-xl text-coffee-700 mb-8 max-w-lg mx-auto lg:mx-0">
            Experience the perfect blend of tradition and innovation in every cup. Our carefully sourced beans and artisanal roasting create unforgettable coffee moments.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <a href="#about" class="btn-primary px-8 py-4 bg-coffee-600 hover:bg-coffee-700 text-white font-semibold rounded-full transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
              Explore More <i class="fas fa-chevron-right ml-3"></i>
            </a>
            <a href="#" class="btn-secondary px-8 py-4 border-2 border-coffee-600 text-coffee-600 hover:bg-coffee-50 font-semibold rounded-full transition-all duration-300 flex items-center justify-center">
              <i class="fas fa-play mr-3"></i> Watch Story
            </a>
          </div>
        </div>
      </div>
      <div class="col-lg-6 order-lg-2 mt-10 lg:mt-0" data-aos="fade-left" data-aos-delay="200">
        <div class="relative">
          <img src="{{ asset('storage/home/coffee.jpg') }}" class="img-fluid rounded-3xl shadow-2xl transform hover:scale-105 transition-transform duration-700" alt="Premium Coffee">
          <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-coffee-400 rounded-2xl -z-10"></div>
          <div class="absolute -top-6 -left-6 w-24 h-24 bg-coffee-300 rounded-full -z-10"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Premium About Section -->
<section id="about" class="py-24 bg-white">
  <div class="container" data-aos="fade-up">
    <div class="row align-items-center">
      <div class="col-lg-5 mb-12 lg:mb-0">
        <div class="relative" data-aos="fade-right" data-aos-delay="100">
          <img src="{{ asset('storage/home/projectcoffee.jpg') }}" class="img-fluid rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500" alt="ProjectCoffee">
          <div class="absolute -bottom-6 -left-6 w-full h-full border-4 border-coffee-300 rounded-3xl -z-10"></div>
          
          <!-- Stats overlay -->
          <div class="absolute -bottom-8 right-8 bg-white p-6 rounded-2xl shadow-lg">
            <div class="flex items-center">
              <div class="text-coffee-600 mr-4">
                <i class="fas fa-star text-3xl"></i>
              </div>
              <div>
                <div class="text-3xl font-bold text-coffee-800">4.9</div>
                <div class="text-sm text-coffee-600">Customer Rating</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-7">
        <div class="px-6" data-aos="fade-left" data-aos-delay="100">
          <span class="text-coffee-500 font-semibold mb-3 inline-block">OUR STORY</span>
          <h2 class="text-4xl font-display font-bold text-coffee-800 mb-6">
            Crafting <span class="text-coffee-600">Exceptional</span> Coffee Experiences
          </h2>
          <p class="text-lg text-coffee-700 mb-8 leading-relaxed">
            At ProjectCoffee, we combine generations of expertise with innovative techniques to bring you coffee that tells a story. From sustainable farms to your cup, every step reflects our commitment to quality.
          </p>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-coffee-50 p-6 rounded-2xl hover:bg-coffee-100 transition-all duration-300">
              <div class="flex items-start">
                <div class="bg-coffee-100 p-3 rounded-lg mr-4">
                  <i class="fas fa-leaf text-coffee-600 text-xl"></i>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-coffee-800 mb-2">Organic Beans</h3>
                  <p class="text-coffee-700">Ethically sourced from sustainable farms worldwide</p>
                </div>
              </div>
            </div>
            
            <div class="bg-coffee-50 p-6 rounded-2xl hover:bg-coffee-100 transition-all duration-300">
              <div class="flex items-start">
                <div class="bg-coffee-100 p-3 rounded-lg mr-4">
                  <i class="fas fa-fire text-coffee-600 text-xl"></i>
                </div>
                <div>
                  <h3 class="text-xl font-bold text-coffee-800 mb-2">Artisan Roasted</h3>
                  <p class="text-coffee-700">Small-batch roasted for perfect flavor profiles</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Premium Services Section -->
<section id="services" class="py-24 bg-coffee-50 relative overflow-hidden">
  <!-- Decorative elements -->
  <div class="absolute top-0 left-0 w-full h-full opacity-5">
    <div class="absolute top-1/4 right-10 w-80 h-80 rounded-full bg-coffee-200"></div>
  </div>
  
  <div class="container relative">
    <div class="text-center mb-20" data-aos="fade-up">
      <span class="text-coffee-500 font-semibold mb-3 inline-block">WHY CHOOSE US</span>
      <h2 class="text-4xl font-display font-bold text-coffee-800 mb-6">
        Our <span class="text-coffee-600">Commitment</span> to Excellence
      </h2>
      <p class="text-xl text-coffee-700 max-w-3xl mx-auto">
        Discover what makes ProjectCoffee different from ordinary coffee experiences
      </p>
    </div>

    <div class="row g-5">
      <div class="col-lg-6" data-aos="zoom-in">
        <div class="bg-white p-10 rounded-3xl shadow-lg hover:shadow-xl transition-all duration-500 h-full border-t-4 border-orange-500">
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-orange-100 rounded-full mb-6">
              <i class="fas fa-mug-hot text-3xl text-orange-500"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-coffee-800 mb-4">Why ProjectCoffee?</h3>
          </div>
          <div class="space-y-4 text-coffee-700">
            <p>
              We meticulously select only the top 1% of coffee beans globally, ensuring unparalleled quality in every batch.
            </p>
            <p>
              Our master roasters combine traditional techniques with modern technology to unlock each bean's full potential.
            </p>
          </div>
          <div class="mt-8 pt-6 border-t border-coffee-100">
            <a href="#" class="inline-flex items-center text-coffee-600 font-semibold hover:text-coffee-800 transition-colors">
              Learn more about our process
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-lg-6" data-aos="zoom-in" data-aos-delay="100">
        <div class="bg-white p-10 rounded-3xl shadow-lg hover:shadow-xl transition-all duration-500 h-full border-t-4 border-teal-400">
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-teal-100 rounded-full mb-6">
              <i class="fas fa-basket-shopping text-3xl text-teal-500"></i>
            </div>
            <h3 class="text-2xl font-display font-bold text-coffee-800 mb-4">Seamless Experience</h3>
          </div>
          <div class="space-y-4 text-coffee-700">
            <p>
              From browsing to brewing, we've streamlined every step for your convenience without compromising quality.
            </p>
            <ul class="space-y-3 pl-5">
              <li class="relative before:absolute before:left-0 before:top-2 before:w-2 before:h-2 before:bg-coffee-500 before:rounded-full">
                <span class="font-semibold">Curated Selection:</span> Expertly chosen varieties for every palate
              </li>
              <li class="relative before:absolute before:left-0 before:top-2 before:w-2 before:h-2 before:bg-coffee-500 before:rounded-full">
                <span class="font-semibold">Fast Delivery:</span> Freshly roasted beans at your doorstep
              </li>
              <li class="relative before:absolute before:left-0 before:top-2 before:w-2 before:h-2 before:bg-coffee-500 before:rounded-full">
                <span class="font-semibold">Brewing Support:</span> Personalized guidance for perfect cups
              </li>
            </ul>
          </div>
          <div class="mt-8 pt-6 border-t border-coffee-100">
            <a href="#" class="inline-flex items-center text-coffee-600 font-semibold hover:text-coffee-800 transition-colors">
              Start your coffee journey
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- AOS Animation -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true,
    offset: 120
  });
</script>