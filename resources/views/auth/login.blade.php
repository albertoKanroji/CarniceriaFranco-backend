<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Carnicería Franco - Login</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/structure.css') }}" rel="stylesheet" type="text/css" class="structure" />
    <link href="{{ asset('assets/css/authentication/form-1.css') }}" rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/theme-checkbox-radio.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/forms/switches.css') }}">
    <style>
        .form-control:focus {
            border-color: #C9A961 !important;
            box-shadow: 0 0 0 3px rgba(201, 169, 97, 0.15) !important;
        }

        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(201, 169, 97, 0.6) !important;
        }

        button[type="submit"]:active {
            transform: translateY(0);
        }

        .form-content {
            padding: 40px !important;
        }

        @media (max-width: 768px) {
            .form-content {
                padding: 20px !important;
            }
        }
    </style>
</head>

<body class="form" style="background: linear-gradient(135deg, #2C2C2C 0%, #1a1a1a 100%);">


    <div class="form-container">
        <div class="form-form">
            <div class="form-form-wrap">
                <div class="form-container">
                    <div class="form-content">

                        <div class="text-center mb-4">
                            <div style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); width: 90px; height: 90px; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; box-shadow: 0 10px 30px rgba(201, 169, 97, 0.5); border: 3px solid #D4B570;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="#2C2C2C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                                </svg>
                            </div>
                            <h1 class="text-center mb-2" style="color: #C9A961; font-weight: 800; font-size: 36px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); letter-spacing: 1px;">Carnicería Franco</h1>
                            <p class="text-center" style="color: #B8935A; font-size: 17px; font-weight: 600; letter-spacing: 0.5px;">Sistema de Gestión Comercial</p>
                        </div>
                        <form class="text-left mt-5" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="form">

                                <div id="username-field" class="field-wrapper input" style="margin-bottom: 20px;">
                                    <label style="color: #C9A961; font-weight: 700; font-size: 15px; margin-bottom: 10px; display: block; letter-spacing: 0.5px;">Correo Electrónico</label>
                                    <div style="position: relative;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="#C9A961" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-user" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); z-index: 1;">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <input id="email" name="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="tucorreo@ejemplo.com" value="{{ old('email') }}" required
                                            autocomplete="email" autofocus
                                            style="border: 2px solid #3D3D3D; border-radius: 12px; padding: 15px 15px 15px 50px; font-size: 15px; font-weight: 600; color: #2C2C2C; background: #f8f8f8; transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert" style="color: #dc3545; font-weight: 600; font-size: 13px; margin-top: 5px; display: block;">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div id="password-field" class="field-wrapper input" style="margin-bottom: 30px;">
                                    <label style="color: #C9A961; font-weight: 700; font-size: 15px; margin-bottom: 10px; display: block; letter-spacing: 0.5px;">Contraseña</label>
                                    <div style="position: relative;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                            fill="none" stroke="#C9A961" stroke-width="2.5" stroke-linecap="round"
                                            stroke-linejoin="round" class="feather feather-lock" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); z-index: 1;">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                        <input id="password" name="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="••••••••" required autocomplete="current-password"
                                            style="border: 2px solid #3D3D3D; border-radius: 12px; padding: 15px 15px 15px 50px; font-size: 15px; font-weight: 600; color: #2C2C2C; background: #f8f8f8; transition: all 0.3s; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert" style="color: #dc3545; font-weight: 600; font-size: 13px; margin-top: 5px; display: block;">
                                            {{ $message }}
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <button type="submit" class="btn btn-block" value=""
                                            style="background: linear-gradient(135deg, #C9A961 0%, #B8935A 100%); color: #2C2C2C; border: 3px solid #D4B570; border-radius: 12px; padding: 16px 20px; font-size: 17px; font-weight: 800; box-shadow: 0 6px 20px rgba(201, 169, 97, 0.5); transition: all 0.3s; text-transform: uppercase; letter-spacing: 1.5px;">
                                        Iniciar Sesión
                                    </button>
                                </div>

                                <!--  </div> -->
                                <!--
                                <div class="field-wrapper text-center keep-logged-in">
                                    <div class="n-chk new-checkbox checkbox-outline-primary">
                                        <label class="new-control new-checkbox checkbox-outline-primary">
                                          <input type="checkbox" class="new-control-input">
                                          <span class="new-control-indicator"></span>Keep me logged in
                                        </label>
                                    </div>
                                </div>

                                <div class="field-wrapper">
                                    <a href="auth_pass_recovery.html" class="forgot-pass-link">Forgot Password?</a>
                                </div>
                            -->
                            </div>
                        </form>
                        <p class="terms-conditions text-center" style="color: #B8935A; font-size: 14px; margin-top: 35px; font-weight: 500;">
                            © 2025 Carnicería Franco. Todos los derechos reservados.<br>
                            <span style="color: #8B7346; font-size: 12px; font-weight: 400;">Versión 1.0</span>
                        </p>

                    </div>
                </div>
            </div>
        </div>
        <div class="form-image">
            <a href="https://www.youtube.com/playlist?list=PLJjetMSzCM-oklVD-W3yVilbEXAbOk2Ns" target="_blank">
                <div class="l-image">
                </div>
            </a>
        </div>
    </div>


</body>

</html>
