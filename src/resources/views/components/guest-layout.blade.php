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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Heebo', sans-serif;
            background-color: #f8fafc;
        }
        .auth-visual {
            background: linear-gradient(135deg, var(--primary) 0%, #00795E 100%);
            position: relative;
            overflow: hidden;
        }
        .auth-visual::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 50%;
            height: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        .auth-visual::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -10%;
            width: 60%;
            height: 60%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        .auth-img-frame {
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);
            border: 8px solid rgba(255,255,255,0.1);
            transform: translateY(0);
            transition: transform 0.5s ease;
        }
        .auth-visual:hover .auth-img-frame {
            transform: translateY(-10px);
        }
        .auth-form-side {
            background: #ffffff;
            border-radius: 2rem 0 0 2rem;
            box-shadow: -10px 0 30px rgba(0,0,0,0.05);
            z-index: 10;
        }
        @media (max-width: 991px) {
            body {
                background: linear-gradient(180deg, rgba(0, 185, 142, 0.12) 0%, #ffffff 42%);
            }
            .auth-form-side {
                border-radius: 0;
                box-shadow: none;
                min-height: 100vh;
                padding: 2rem 1.25rem !important;
                align-items: flex-start !important;
            }
            .auth-form-side > .w-100 {
                max-width: 100% !important;
            }
            .auth-form-side .text-center.mb-10 {
                margin-bottom: 2rem !important;
                padding-top: 0.75rem;
            }
            .auth-form-side h2 {
                font-size: 2rem !important;
            }
        }
        @media (max-width: 480px) {
            .auth-form-side {
                padding: 1.5rem 1rem !important;
            }
        }
        .feature-pill {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .feature-pill:hover {
            background: rgba(255,255,255,0.25);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container-fluid p-0">
    <div class="row min-vh-100 g-0">

        {{-- Left Side - Visual --}}
        <div class="col-lg-7 d-none d-lg-flex auth-visual align-items-center justify-content-center position-relative">
            <div class="text-center text-white p-5" style="max-width: 560px; z-index: 2;">
                <div class="mb-5 relative">
                    <img src="{{ asset('makaan/img/carousel-1.jpg') }}"
                        class="img-fluid auth-img-frame"
                        style="height: 400px; width: 100%; object-fit: cover;"
                        alt="Kostify">
                    
                    <div class="absolute -bottom-6 -right-6 bg-white text-gray-900 p-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-500">
                            <i class="fa fa-home"></i>
                        </div>
                        <div class="text-left">
                            <p class="text-xs text-gray-500 font-bold mb-0">Platform #1</p>
                            <h6 class="mb-0 font-extrabold text-sm">Pencarian Kos</h6>
                        </div>
                    </div>
                </div>

                <h1 class="font-extrabold mb-3 text-4xl leading-tight">Hunian Nyaman, Sewa Tanpa Ribet</h1>
                <p class="opacity-90 mb-5 text-lg font-light leading-relaxed">
                    Temukan kos & kontrakan terbaik dengan proses booking
                    dan pembayaran yang mudah, transparan, dan terjamin aman 100%.
                </p>

                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <span class="feature-pill font-semibold shadow-sm">
                        <i class="fa fa-check-circle text-white"></i> Booking Online
                    </span>
                    <span class="feature-pill font-semibold shadow-sm">
                        <i class="fa fa-shield-alt text-white"></i> Pembayaran Aman
                    </span>
                    <span class="feature-pill font-semibold shadow-sm">
                        <i class="fa fa-headset text-white"></i> Support 24/7
                    </span>
                </div>
            </div>
            
            <div class="absolute bottom-5 left-5 text-white/50 text-sm font-medium">
                &copy; {{ date('Y') }} Kostify. All rights reserved.
            </div>
        </div>

        {{-- Right Side - Form --}}
        <div class="col-lg-5 d-flex align-items-center justify-content-center p-4 p-md-5 auth-form-side relative">
            <div class="absolute top-8 right-8 hidden md:block">
                <a href="{{ url('/') }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2 font-medium text-sm">
                    <i class="fa fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="w-100" style="max-width: 420px;">
                <div class="text-center mb-10">
                    <a href="{{ url('/') }}" class="inline-flex align-items-center text-decoration-none group mb-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                            <img src="{{ asset('makaan/img/icon-deal.png') }}" style="width: 24px;" alt="Logo">
                        </div>
                        <h2 class="m-0 text-gray-900 font-extrabold text-3xl tracking-tight">Kostify</h2>
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