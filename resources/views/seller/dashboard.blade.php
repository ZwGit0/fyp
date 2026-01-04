@extends('seller.sellerMain')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/seller/sellerDashboard.css') }}">
@endsection

@section('content')
    <div class="container">
        <h2><span>Welcome back, {{ Auth::guard('seller')->user()->name }}! Your products are waiting to be showcased. Let's get started!</span></h2>

        <div class="dashboard-overview">
            <h3>Dashboard Overview</h3>
            <div class="overview-stats">
                <div class="stat-box">
                    <p>Total Products</p>
                    <strong>{{ $productCount }}</strong>
                </div>
                <div class="stat-box">
                    <p>Total Revenue Generated</p>
                    <strong class="revenue-display">RM {{ number_format($totalRevenue, 2) }}</strong>
                </div>
            </div>
        </div>

        <div class="dashboard-charts">
            <div class="chart-section">
                <!-- Top 6 Products Sold -->
                <div class="chart-container">
                    <h4>The Top 6 Products Sold</h4>
                    <canvas id="topProductsChart"></canvas>
                </div>

                <!-- Top 4 Frequently Added to Cart -->
                <div class="chart-container pie-chart-container">
                    <h4>Top 4 Frequently Added to Cart</h4>
                    <div class="pie-chart-wrapper">
                        <canvas id="topCartItemsChart"></canvas>
                    </div>
                    <div class="pie-chart-legend">
                        @foreach($topCartItems as $item)
                            <div>
                                <span class="dot" style="background-color: {{ $loop->index == 0 ? '#ff6b6b' : ($loop->index == 1 ? '#ff9999' : ($loop->index == 2 ? '#f0f0f0' : '#d3d3d3')) }}"></span>
                                <span>{{ $item['percentage'] }}% {{ $item['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <div class="products-sold-table">
            <h3>Products Sold Details</h3>
            @if(!empty($productsSold))
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                            <th>Total Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productsSold as $product)
                            <tr>
                                <td>
                                    {{ $product['name'] }}
                                    @if($product['name'] === 'Deleted Product')
                                        <span class="deleted-product">(Deleted)</span>
                                    @endif
                                </td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>{{ number_format($product['total_price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No products have been sold yet.</p>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Top Products Chart (Bar Chart)
        const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: [@foreach($topProducts as $product)'{{ $product['name'] }}', @endforeach],
                datasets: [{
                    label: 'Units Sold',
                    data: [@foreach($topProducts as $product){{ $product['units_sold'] }}, @endforeach],
                    backgroundColor: ['#ff6b6b', '#ff9999', '#ff6b6b', '#ff9999', '#ff6b6b', '#ff9999'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Units Sold'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Top Cart Items Chart (Pie Chart)
        const topCartItemsCtx = document.getElementById('topCartItemsChart').getContext('2d');
        new Chart(topCartItemsCtx, {
            type: 'pie',
            data: {
                labels: [@foreach($topCartItems as $item)'{{ $item['name'] }}', @endforeach],
                datasets: [{
                    data: [@foreach($topCartItems as $item){{ $item['percentage'] }}, @endforeach],
                    backgroundColor: ['#ff6b6b', '#ff9999', '#f0f0f0', '#d3d3d3'],
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
