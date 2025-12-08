<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        .card-alert {
            border: 2px solid #dc3545 !important;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 10px rgba(220, 53, 69, 0.5); }
            50% { box-shadow: 0 0 25px rgba(220, 53, 69, 0.8); }
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

        .max-value-badge {
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
            background: #3a3a3a;
            border-radius: 20px;
            color: #b0b0b0;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .alert-badge {
            font-size: 0.75rem;
            padding: 0.35rem 0.85rem;
            background: #dc3545;
            color: white;
            border-radius: 20px;
            display: inline-block;
            margin-top: 0.5rem;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .form-card {
            background: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 15px;
            padding: 2rem;
            margin-top: 3rem;
        }

        .form-label {
            color: #b0b0b0;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            background: #1f1f1f;
            border: 1px solid #3a3a3a;
            color: #e0e0e0;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .form-control:focus, .form-select:focus {
            background: #252525;
            border-color: #0d6efd;
            color: #e0e0e0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .form-control::placeholder {
            color: #666;
        }

        .btn-primary {
            background: linear-gradient(145deg, #0d6efd, #0a58ca);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
        }

        /* Button Lampu Styles */
        .btn-lamp {
            background: linear-gradient(145deg, #6c757d, #5a6268);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .btn-lamp:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
        }

        .btn-lamp.active {
            background: linear-gradient(145deg, #28a745, #218838);
            box-shadow: 0 0 20px rgba(40, 167, 69, 0.6);
        }

        .btn-lamp.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            animation: shine 2s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .lamp-status {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            font-weight: 600;
        }

        .footer-text {
            color: #9e9e9e;
            font-size: 0.9rem;
        }
    </style>
  </head>
  <body>
    <div class="container py-5">
        <h1 class="text-center mb-5">
            <i class="fas fa-microchip me-2"></i>
            DHT22 Sensor Monitoring
        </h1>

        <!-- Alert Toast -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div id="alertToast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastMessage">
                        Data berhasil disimpan!
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-4">
            <div class="col-md-5 col-lg-4">
                <div class="card card-temperature text-center p-4" id="tempCard">
                    <div class="icon-box">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <h5 class="label">Temperature</h5>
                    <p class="value">
                        <span id="temperature">--</span> °C
                    </p>
                    <div class="max-value-badge">
                        Max: <span id="maxTemp">--</span> °C
                    </div>
                    <div id="tempAlert" class="alert-badge" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-1"></i> ALERT!
                    </div>
                </div>
            </div>

            <div class="col-md-5 col-lg-4">
                <div class="card card-humidity text-center p-4" id="humCard">
                    <div class="icon-box">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h5 class="label">Humidity</h5>
                    <p class="value">
                        <span id="humidity">--</span> %
                    </p>
                    <div class="max-value-badge">
                        Max: <span id="maxHum">--</span> %
                    </div>
                    <div id="humAlert" class="alert-badge" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-1"></i> ALERT!
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Setting Nilai Maksimum -->
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="form-card">
                    <h4 class="text-center mb-4" style="color: #f1f1f1;">
                        <i class="fas fa-cog me-2"></i>
                        Pengaturan Nilai Maksimum
                    </h4>
                    
                    <form id="maxValueForm">
                        <div class="mb-3">
                            <label for="jenis_nilai" class="form-label">Jenis Nilai</label>
                            <select name="jenis_nilai" id="jenis_nilai" class="form-select" required>
                                <option value="">Pilih Jenis Nilai</option>
                                <option value="max_temperature">Maksimum Temperature (°C)</option>
                                <option value="max_humidity">Maksimum Humidity (%)</option>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label for="nilai" class="form-label">Nilai Maksimum</label>
                            <input type="number" 
                                   name="nilai" 
                                   id="nilai" 
                                   class="form-control" 
                                   placeholder="Masukkan nilai maksimum"
                                   step="0.1"
                                   min="0"
                                   max="100"
                                   required>
                            <small class="form-text" style="color: #888;">
                                Nilai harus antara 0-100
                            </small>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- 6 Button Kontrol Lampu -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-8 col-lg-6">
                <div class="form-card">
                    <h4 class="text-center mb-4" style="color: #f1f1f1;">
                        <i class="fas fa-lightbulb me-2"></i>
                        Kontrol Lampu
                    </h4>
                    
                    <div class="row g-3">
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="1">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 1
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="2">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 2
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="3">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 3
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="4">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 4
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="5">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 5
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4">
                            <button class="btn btn-lamp w-100" data-lamp="6">
                                <i class="fas fa-lightbulb me-1"></i>
                                Lampu 6
                                <div class="lamp-status">OFF</div>
                            </button>
                        </div>
                    </div>

                    <!-- Tombol Kontrol Semua -->
                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <button class="btn btn-primary w-100" id="allOn">
                                <i class="fas fa-toggle-on me-1"></i>
                                Nyalakan Semua
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-primary w-100" id="allOff">
                                <i class="fas fa-toggle-off me-1"></i>
                                Matikan Semua
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 footer-text">
            <i class="fas fa-sync-alt me-1"></i>
            Auto refresh setiap 2 detik
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            let maxTemperature = 0;
            let maxHumidity = 0;

            function showToast(message, type = 'success') {
                const toast = $('#alertToast');
                const toastBootstrap = new bootstrap.Toast(toast[0]);
                
                if (type === 'success') {
                    toast.removeClass('bg-danger').addClass('bg-success');
                } else {
                    toast.removeClass('bg-success').addClass('bg-danger');
                }
                
                $('#toastMessage').text(message);
                toastBootstrap.show();
            }

            function checkAlert(temp, hum) {
                if (temp > maxTemperature && maxTemperature > 0) {
                    $('#tempCard').addClass('card-alert');
                    $('#tempAlert').show();
                } else {
                    $('#tempCard').removeClass('card-alert');
                    $('#tempAlert').hide();
                }

                if (hum > maxHumidity && maxHumidity > 0) {
                    $('#humCard').addClass('card-alert');
                    $('#humAlert').show();
                } else {
                    $('#humCard').removeClass('card-alert');
                    $('#humAlert').hide();
                }
            }

            function updateLampStatus() {
                $.ajax({
                    type: "GET",
                    url: "/get-lamp-status",
                    success: function (response) {
                        // Update status setiap lampu
                        for (let i = 1; i <= 6; i++) {
                            const lampKey = 'lamp' + i;
                            const isOn = response[lampKey] === 'on';
                            const btn = $(`button[data-lamp="${i}"]`);
                            
                            if (isOn) {
                                btn.addClass('active');
                                btn.find('.lamp-status').text('ON');
                            } else {
                                btn.removeClass('active');
                                btn.find('.lamp-status').text('OFF');
                            }
                        }
                    },
                    error: function() {
                        console.error("Gagal mengambil status lampu");
                    }
                });
            }

            function getData() {
                $.ajax({
                    type: "GET",
                    url: "/get-data",
                    success: function (response) {
                        const temp = parseFloat(response.temperature);
                        const hum = parseFloat(response.humidity);
                        
                        $("#temperature").text(temp);
                        $("#humidity").text(hum);
                        
                        if (response.max_temperature) {
                            maxTemperature = parseFloat(response.max_temperature);
                            $("#maxTemp").text(maxTemperature);
                        }
                        
                        if (response.max_humidity) {
                            maxHumidity = parseFloat(response.max_humidity);
                            $("#maxHum").text(maxHumidity);
                        }

                        checkAlert(temp, hum);
                    },
                    error: function() {
                        $("#temperature").text("Error");
                        $("#humidity").text("Error");
                        showToast("Gagal mengambil data sensor", "error");
                    }
                });
            }

            // Toggle lampu individual
            $('.btn-lamp').on('click', function() {
                const lampNumber = $(this).data('lamp');
                const isActive = $(this).hasClass('active');
                const newStatus = isActive ? 'off' : 'on';

                $.ajax({
                    type: "POST",
                    url: "/control-lamp",
                    data: {
                        lamp: lampNumber,
                        status: newStatus,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast(`Lampu ${lampNumber} berhasil ${newStatus === 'on' ? 'dinyalakan' : 'dimatikan'}`);
                        updateLampStatus();
                    },
                    error: function() {
                        showToast("Gagal mengontrol lampu", "error");
                    }
                });
            });

            // Nyalakan semua lampu
            $('#allOn').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "/control-all-lamps",
                    data: {
                        status: 'on',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast("Semua lampu berhasil dinyalakan");
                        updateLampStatus();
                    },
                    error: function() {
                        showToast("Gagal menyalakan semua lampu", "error");
                    }
                });
            });

            // Matikan semua lampu
            $('#allOff').on('click', function() {
                $.ajax({
                    type: "POST",
                    url: "/control-all-lamps",
                    data: {
                        status: 'off',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast("Semua lampu berhasil dimatikan");
                        updateLampStatus();
                    },
                    error: function() {
                        showToast("Gagal mematikan semua lampu", "error");
                    }
                });
            });

            // Submit form nilai maksimum
            $('#maxValueForm').on('submit', function(e) {
                e.preventDefault();
                
                const jenisNilai = $('#jenis_nilai').val();
                const nilai = $('#nilai').val();

                if (!jenisNilai || !nilai) {
                    showToast("Mohon lengkapi semua field", "error");
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "/update-nilai-maksimal",
                    data: {
                        jenis_nilai: jenisNilai,
                        nilai: nilai,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showToast("Pengaturan berhasil disimpan!");
                        
                        if (jenisNilai === 'max_temperature') {
                            maxTemperature = parseFloat(nilai);
                            $("#maxTemp").text(nilai);
                        } else {
                            maxHumidity = parseFloat(nilai);
                            $("#maxHum").text(nilai);
                        }
                        
                        $('#maxValueForm')[0].reset();
                        getData();
                    },
                    error: function() {
                        showToast("Gagal menyimpan pengaturan", "error");
                    }
                });
            });

            // Load pertama kali
            getData();
            updateLampStatus();

            // Auto refresh setiap 2 detik
            setInterval(function() {
                getData();
                updateLampStatus();
            }, 2000);
        });
    </script>
  </body>
</html>