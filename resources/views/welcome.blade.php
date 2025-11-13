<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DHT22 Sensor Monitoring</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            background: linear-gradient(135deg, #1e1e1e, #2c2c2c);
            color: #e0e0e0;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            color: #f1f1f1;
            font-weight: 600;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.6);
        }

        .card {
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 35px rgba(0,0,0,0.5);
        }

        .card-temperature {
            background: linear-gradient(145deg, #3b3b3b, #1f1f1f);
        }

        .card-humidity {
            background: linear-gradient(145deg, #444, #1c1c1c);
        }

        .icon-box {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #f8f9fa;
        }

        .value {
            font-size: 2.8rem;
            font-weight: bold;
            color: #f8f9fa;
            margin: 0;
        }

        .label {
            font-size: 1.2rem;
            color: #b0b0b0;
            margin-bottom: 0.5rem;
        }

        .footer-text {
            color: #9e9e9e;
            font-size: 0.9rem;
        }

        .loading {
            opacity: 0.6;
        }
    </style>
  </head>
  <body>
    <div class="container py-5">
        <h1 class="text-center mb-5">
            <i class="fas fa-microchip me-2"></i>
            DHT22 Sensor Monitoring
        </h1>

        <div class="row justify-content-center g-4">
            <div class="col-md-5 col-lg-4">
                <div class="card card-temperature text-center p-4">
                    <div class="icon-box">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <h5 class="label">Temperature</h5>
                    <p class="value">
                        <span id="temperature">--</span> °C
                    </p>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card card-humidity text-center p-4">
                    <div class="icon-box">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h5 class="label">Humidity</h5>
                    <p class="value">
                        <span id="humidity">--</span> %
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 footer-text">
            <i class="fas fa-sync-alt me-1"></i>
            Auto refresh setiap 2 detik
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            function getData() {
                $.ajax({
                    type: "GET",
                    url: "/get-data",
                    success: function (response) {
                        $("#temperature").text(response.temperature);
                        $("#humidity").text(response.humidity);
                    },
                    error: function() {
                        $("#temperature").text("Error");
                        $("#humidity").text("Error");
                    }
                });
            }

            // Load pertama kali
            getData();

            // Auto refresh setiap 2 detik
            setInterval(getData, 2000);
        });
    </script>
  </body>
</html>
