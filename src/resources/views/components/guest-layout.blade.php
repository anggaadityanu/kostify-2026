<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kostify - Platform Kos & Kontrakan Terpercaya</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('makaan/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('makaan/css/style.css') }}" rel="stylesheet">

    <style>
        body {
            font-family: 'Heebo', sans-serif;
        }
        .auth-visual {
            background: linear-gradient(160deg, #00B98E 0%, #00795E 100%);
            position: relative;
            overflow: hidden;
        }
        .auth-visual::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .auth-visual::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 250px;
            height: 250px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .auth-img-frame {
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            border: 4px solid rgba(255,255,255,0.2);
        }
        .auth-form-side {
            background: #ffffff;
        }
        .feature-pill {
            background: rgba(255,255,255,0.15);
            border-radius: 50px;
            padding: 6px 16px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row min-vh-100 g-0">

        {{-- Left Side - Visual --}}
        <div class="col-lg-6 d-none d-lg-flex auth-visual align-items-center justify-content-center position-relative">
            <div class="text-center text-white p-5" style="max-width: 480px; z-index: 2;">
                <img src="{{ asset('makaan/img/carousel-1.jpg') }}"
                    class="img-fluid auth-img-frame mb-4"
                    style="max-height: 380px; width: 100%; object-fit: cover;"
                    alt="Kostify">

                <h2 class="fw-bold mb-2">Hunian Nyaman, Sewa Tanpa Ribet</h2>
                <p class="opacity-85 mb-4" style="font-size: 15px;">
                    Temukan kos & kontrakan terbaik dengan proses booking
                    dan pembayaran yang mudah, transparan, dan aman.
                </p>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <span class="feature-pill">
                        <i class="fa fa-check-circle"></i> Booking Online
                    </span>
                    <span class="feature-pill">
                        <i class="fa fa-shield-alt"></i> Pembayaran Aman
                    </span>
                    <span class="feature-pill">
                        <i class="fa fa-headset"></i> Support 24/7
                    </span>
                </div>
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-md-5 auth-form-side">
            <div class="w-100" style="max-width: 440px;">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                        <img src="{{ asset('makaan/img/icon-deal.png') }}" style="width: 36px;" class="me-2">
                        <h3 class="m-0 text-primary fw-bold">Kostify</h3>
                    </a>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>