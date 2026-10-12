<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/logoputih.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/static/images/logo/logoputih.png') }}" type="image/png">

    <title>LOGIN - SIMENOR BPS KOTA BOGOR</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background: #ffffff;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        /* ========================================
           GAMBAR SEBELAH KIRI
        ======================================== */

        .left-side {
            width: 80%;
            height: 100vh;
            overflow: hidden;
            background: #ffffff;
        }

        .left-side img {
            width: 100%;
            height: 100%;
            object-fit: fill;
            display: block;
        }

        /* ========================================
           FORM SEBELAH KANAN
        ======================================== */

        .right-side {
            width: 42%;
            min-height: 100vh;

            display: flex;
            justify-content: center;
            align-items: center;

            background: #ffffff;

            padding: 40px;
        }

        .login-box {
            width: 100%;
            max-width: 550px;
        }

        /* ========================================
           LOGO
        ======================================== */

        .logo {
            margin-bottom: 35px;
        }

        .logo img {
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        /* ========================================
           JUDUL
        ======================================== */

        .title {
            color: #083782;
            font-size: 32px;
            font-weight: 700;

            margin-bottom: 8px;
        }

        .subtitle {
            color: #071b46;
            font-size: 17px;
            font-weight: 600;

            margin-bottom: 35px;
        }

        /* ========================================
           FORM
        ======================================== */

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;

            color: #071b46;

            font-size: 15px;
            font-weight: 500;

            margin-bottom: 9px;
        }

        .input {
            width: 100%;
            height: 48px;

            padding: 0 14px;

            border: 1px solid #d3ddec;
            border-radius: 4px;

            background: #edf4ff;

            font-size: 15px;

            outline: none;

            transition: 0.2s;
        }

        .input::placeholder {
            color: #9ba8bf;
        }

        .input:focus {
            border-color: #0969e8;
            background: #ffffff;
        }

        /* ========================================
           PASSWORD
        ======================================== */

        .password-box {
            position: relative;
        }

        .password-box .input {
            padding-right: 55px;
        }

        .eye {
            position: absolute;

            right: 0;
            top: 0;

            width: 50px;
            height: 48px;

            border: none;
            border-left: 1px solid #d3ddec;

            background: transparent;

            cursor: pointer;

            color: #536784;

            font-size: 18px;
        }

        .eye:hover {
            color: #0969e8;
        }

        /* ========================================
           REMEMBER ME
        ======================================== */

        .form-options {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-top: 2px;
            margin-bottom: 30px;
        }

        .remember {
            display: flex;

            align-items: center;

            gap: 8px;

            color: #071b46;

            font-size: 15px;
        }

        .remember input {
            width: 18px;
            height: 18px;

            cursor: pointer;
        }

        .forgot-password {
            color: #0969e8;

            font-size: 14px;

            text-decoration: underline;
        }

        .forgot-password:hover {
            color: #0758c7;
        }

        /* ========================================
           BUTTON LOGIN
        ======================================== */

        .login-button {
            width: 100%;
            height: 48px;

            border: none;
            border-radius: 4px;

            background: #083782;
            color: #ffffff;

            font-size: 16px;
            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;
        }

        .login-button:hover {
            background: rgb(0, 71, 171);
        }

        /* ========================================
           FOOTER
        ======================================== */

        .login-footer {
            text-align: center;

            margin-top: 35px;

            color: #8a94a6;

            font-size: 13px;
        }

        /* ========================================
           RESPONSIVE TABLET
        ======================================== */

        @media (max-width: 1100px) {

            .left-side {
                width: 50%;
            }

            .right-side {
                width: 50%;

                padding: 35px;
            }

            .login-box {
                max-width: 480px;
            }

            .title {
                font-size: 28px;
            }
        }

        /* ========================================
           RESPONSIVE HP
        ======================================== */

        @media (max-width: 800px) {

            .login-container {
                display: block;
            }

            .left-side {
                display: none;
            }

            .right-side {
                width: 100%;
                min-height: 100vh;

                padding: 30px 25px;
            }

            .login-box {
                max-width: 480px;
            }

            .title {
                font-size: 28px;
            }
        }

        @media (max-width: 500px) {

            .right-side {
                padding: 25px 20px;
            }

            .logo {
                margin-bottom: 25px;
            }

            .logo img {
                width: 52px;
                height: 52px;
            }

            .title {
                font-size: 25px;
            }

            .subtitle {
                font-size: 15px;

                margin-bottom: 28px;
            }

            .form-options {
                flex-direction: column;

                align-items: flex-start;

                gap: 15px;
            }

            .bottom-buttons {
                gap: 10px;
            }

            .bottom-button {
                font-size: 14px;
            }
        }
    </style>

</head>

<body>

    <div class="login-container">

        <!-- ========================================
             SEBELAH KIRI
        ======================================== -->

        <div class="left-side">
            <img src="{{ asset('assets/static/images/bg/bg_login.png') }}" alt="Background SIMENOR">
        </div>


        <!-- ========================================
             SEBELAH KANAN
        ======================================== -->

        <div class="right-side">

            <div class="login-box">

                <!-- LOGO BPS -->

                <div class="logo">

                    <img src="{{ asset('assets/static/images/logo/logo.png') }}" alt="Logo BPS Kota Bogor">

                </div>


                <!-- JUDUL -->

                <h1 class="title">
                    SIMONER
                </h1>

                <p class="subtitle">
                    Sistem Informasi Monitoring & Penilaian Kinerja Pegawai
                </p>


                <!-- NIP -->

                <div class="form-group">

                    <label for="username">
                        Username
                    </label>

                    <input type="text" name="username" id="username" class="input"
                        placeholder="Masukkan username anda">

                </div>


                <!-- PASSWORD -->

                <div class="form-group">

                    <label for="password">
                        Password
                    </label>

                    <div class="password-box">

                        <input type="password" name="password" id="password" class="input"
                            placeholder="Masukkan password anda">

                        <button type="button" class="eye" onclick="showPassword()" aria-label="Tampilkan password">

                            👁

                        </button>

                    </div>

                </div>


                <!-- REMEMBER ME + LUPA PASSWORD -->

                <div class="form-options">

                    <label class="remember">

                        <input type="checkbox" id="remember">

                        <span>
                            Remember me
                        </span>

                    </label>

                </div>


                <!-- LOGIN -->

                <button type="button" class="login-button">

                    Login

                </button>


                <!-- FOOTER -->

                <div class="login-footer">

                    © 2026 BPS Kota Bogor

                </div>

            </div>

        </div>

    </div>


    <!-- ========================================
         JAVASCRIPT
    ======================================== -->

    <script>
        function showPassword() {

            const password =
                document.getElementById("password");

            const eye =
                document.querySelector(".eye");

            if (password.type === "password") {

                password.type = "text";

                eye.innerHTML = "🙈";

            } else {

                password.type = "password";

                eye.innerHTML = "👁";

            }
        }
    </script>

</body>

</html>
