<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Custom Styles -->
  <style>
    .card:hover {
      transform: translateY(-5px);
      transition: transform 0.3s ease;
    }
    .chart-card {
      background: linear-gradient(145deg, #ffffff, #f9fafb);
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>
<body class="bg-gray-100">

  <div class="container mx-auto px-4 pt-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
      <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
          <li class="inline-flex items-center">
            <a href="#" class="text-gray-700 hover:text-gray-900 inline-flex items-center">
              <i class="fas fa-home mr-2"></i>
              Home
            </a>
          </li>
          <li aria-current="page">
            <div class="flex items-center">
              <i class="fas fa-angle-right text-gray-400"></i>
              <span class="text-gray-500 ml-2">Dashboard</span>
            </div>
          </li>
        </ol>
      </nav>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Primary Card -->
      <div class="card bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg shadow-lg hover:shadow-xl">
        <div class="p-6">
          <h2 class="text-lg font-semibold">Total Users</h2>
          <p class="text-3xl font-bold mt-2">1,234</p>
        </div>
        <div class="p-4 bg-blue-600 rounded-b-lg flex justify-between items-center">
          <a href="#" class="text-sm hover:underline">View Details</a>
          <i class="fas fa-arrow-right"></i>
        </div>
      </div>

      <!-- Warning Card -->
      <div class="card bg-gradient-to-r from-yellow-400 to-yellow-500 text-white rounded-lg shadow-lg hover:shadow-xl">
        <div class="p-6">
          <h2 class="text-lg font-semibold">Pending Orders</h2>
          <p class="text-3xl font-bold mt-2">56</p>
        </div>
        <div class="p-4 bg-yellow-500 rounded-b-lg flex justify-between items-center">
          <a href="#" class="text-sm hover:underline">View Details</a>
          <i class="fas fa-arrow-right"></i>
        </div>
      </div>

      <!-- Success Card -->
      <div class="card bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg shadow-lg hover:shadow-xl">
        <div class="p-6">
          <h2 class="text-lg font-semibold">Completed Orders</h2>
          <p class="text-3xl font-bold mt-2">789</p>
        </div>
        <div class="p-4 bg-green-600 rounded-b-lg flex justify-between items-center">
          <a href="#" class="text-sm hover:underline">View Details</a>
          <i class="fas fa-arrow-right"></i>
        </div>
      </div>

      <!-- Danger Card -->
      <div class="card bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg shadow-lg hover:shadow-xl">
        <div class="p-6">
          <h2 class="text-lg font-semibold">Cancelled Orders</h2>
          <p class="text-3xl font-bold mt-2">23</p>
        </div>
        <div class="p-4 bg-red-600 rounded-b-lg flex justify-between items-center">
          <a href="#" class="text-sm hover:underline">View Details</a>
          <i class="fas fa-arrow-right"></i>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Sales Chart -->
      <div class="chart-card p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-chart-area text-blue-500 mr-2"></i>
            Sales Chart
          </h2>
          <a href="#" class="text-blue-500 hover:text-blue-700">View Report</a>
        </div>
        <div class="w-full h-64">
          <canvas id="sales_chart"></canvas>
        </div>
      </div>

      <!-- Profits Chart -->
      <div class="chart-card p-6">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">
            <i class="fas fa-chart-bar text-green-500 mr-2"></i>
            Profits Chart
          </h2>
          <a href="#" class="text-green-500 hover:text-green-700">View Report</a>
        </div>
        <div class="w-full h-64">
          <canvas id="profits_chart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Sales Chart
    const salesChart = new Chart(document.getElementById('sales_chart'), {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Sales',
          data: [65, 59, 80, 81, 56, 55, 40],
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          fill: true,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    });

    // Profits Chart
    const profitsChart = new Chart(document.getElementById('profits_chart'), {
      type: 'bar',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Profits',
          data: [12000, 19000, 3000, 5000, 2000, 3000, 45000],
          backgroundColor: '#10b981',
          borderColor: '#10b981',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
      }
    });
  </script>
</body>
</html>