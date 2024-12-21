<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pelanggan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Inter", sans-serif;
        }

        .formbold-main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 100vh;
        }

        .formbold-form-wrapper {
            margin: 0 auto;
            max-width: 550px;
            width: 100%;
            background: white;
            padding: 15px;
        }

        /* Preserve multi-step structure while making it responsive */
        .formbold-steps {
            padding-bottom: 18px;
            margin-bottom: 25px;
            border-bottom: 1px solid #DDE3EC;
            overflow-x: auto;
        }

        .formbold-steps ul {
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 15px;
            min-width: min-content;
        }

        .formbold-steps li {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            font-size: 14px;
            line-height: 24px;
            color: #536387;
            white-space: nowrap;
        }

        .formbold-steps li span {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #DDE3EC;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            font-weight: 500;
            font-size: 14px;
            color: #536387;
        }

        .formbold-steps li.active {
            color: #07074D;
        }

        .formbold-steps li.active span {
            background: #F44424;
            color: #FFFFFF;
        }

        /* Make form inputs responsive */
        .formbold-input-flex {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 22px;
        }

        @media (min-width: 540px) {
            .formbold-input-flex {
                flex-direction: row;
            }

            .formbold-input-flex>div {
                width: 50%;
            }
        }

        .formbold-form-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 5px;
            border: 1px solid #DDE3EC;
            background: #FFFFFF;
            font-weight: 500;
            font-size: 15px;
            color: #536387;
            outline: none;
            resize: none;
        }

        .formbold-form-input:focus {
            border-color: #F44424;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }

        .formbold-form-label {
            color: #07074D;
            font-weight: 500;
            font-size: 14px;
            line-height: 24px;
            display: block;
            margin-bottom: 8px;
        }

        /* Adjust confirmation section */
        .formbold-form-confirm {
            border-bottom: 1px solid #DDE3EC;
            padding-bottom: 25px;
        }

        .formbold-form-confirm p {
            font-size: 14px;
            line-height: 24px;
            color: #536387;
            margin-bottom: 22px;
        }

        .formbold-form-confirm>div {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        @media (min-width: 540px) {
            .formbold-form-confirm>div {
                flex-direction: row;
            }
        }

        .formbold-confirm-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #FFFFFF;
            border: 0.5px solid #DDE3EC;
            border-radius: 5px;
            font-size: 14px;
            line-height: 24px;
            color: #536387;
            cursor: pointer;
            padding: 10px 15px;
            transition: all .3s ease-in-out;
            justify-content: center;
            width: 100%;
        }

        @media (min-width: 540px) {
            .formbold-confirm-btn {
                width: auto;
            }
        }

        .formbold-confirm-btn.active {
            background: #F44424;
            color: #FFFFFF;
        }

        /* Navigation buttons */
        /* Navigation wrapper to contain both elements */
        .formbold-form-btn-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 33px;
            margin-top: 25px;
        }

        /* Back button styles */
        .formbold-back-btn {
            cursor: pointer;
            background: #FFFFFF;
            border: none;
            color: #07074D;
            font-weight: 500;
            font-size: 14px;
            line-height: 24px;
            display: none;
        }

        .formbold-back-btn.active {
            background: #DDE3EC;
            border-radius: 5px;
            /* Mengatur tombol menjadi inline-block untuk menghindari ruang kosong */
            display: inline-block;
            /* Menambahkan padding agar lebih seimbang */
            padding: 10px 20px;
        }

        /* Next button styles */
        .formbold-btn {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            border-radius: 5px;
            padding: 10px 20px;
            border: none;
            font-weight: 500;
            background-color: #F44424;
            color: white;
            cursor: pointer;
        }

        .formbold-btn:hover {
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }

        /* Login link styles */
        .login-link {
            text-decoration: none;
            color: #F44424;
            font-size: 16px;
            padding: 10px 0;
        }

        /* Mobile styles (under 540px) */
        @media screen and (max-width: 539px) {
            .formbold-form-btn-wrapper {
                flex-direction: column-reverse;
                align-items: stretch;
            }

            .login-link {
                text-align: center;
                order: 1;
            }

            .formbold-btn {
                order: 2;
                justify-content: center;
            }

            .formbold-back-btn {
                order: 3;
                text-align: center;
            }
        }

        /* Desktop styles (540px and above) */
        @media screen and (min-width: 540px) {
            .formbold-form-btn-wrapper {
                flex-direction: row;
                align-items: center;
            }

            .login-link {
                margin: 0;
                text-align: left;
            }

            .formbold-btn {
                margin-left: auto;
            }
        }

        /* Preserve all the password-related styles */
        .password-strength-meter {
            height: 5px;
            background-color: #f3f3f3;
            border-radius: 3px;
            margin-top: 10px;
        }

        .strength-meter-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;
            width: 0;
        }

        .very-weak {
            background-color: #FF4136;
            width: 20%;
        }

        .weak {
            background-color: #FF851B;
            width: 40%;
        }

        .medium {
            background-color: #FFDC00;
            width: 60%;
        }

        .strong {
            background-color: #2ECC40;
            width: 80%;
        }

        .very-strong {
            background-color: #01FF70;
            width: 100%;
        }

        /* Form step visibility control */
        .formbold-form-step-1,
        .formbold-form-step-2,
        .formbold-form-step-3 {
            display: none;
        }

        .formbold-form-step-1.active,
        .formbold-form-step-2.active,
        .formbold-form-step-3.active {
            display: block;
        }
    </style>
</head>

<body>
    <div class="formbold-main-wrapper">

        <div class="formbold-form-wrapper">
            <form action="{{ route('register.store') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            html: '{!! implode('<br>', $errors->all()) !!}',
                            showConfirmButton: true
                        });
                    </script>
                @endif


                <div class="formbold-steps">
                    <ul>
                        <li class="formbold-step-menu1 active">
                            <span>1</span>
                            Data Diri
                        </li>
                        <li class="formbold-step-menu2">
                            <span>2</span>
                            Data Login
                        </li>
                        <li class="formbold-step-menu3">
                            <span>3</span>
                            Konfirmasi
                        </li>
                    </ul>
                </div>


                <div class="formbold-form-step-1 active">
                    <div class="formbold-input-flex">
                        <div>
                            <label for="fullname" class="formbold-form-label">Nama Lengkap </label>
                            <input type="text" name="name" placeholder="John Doe" id="fullname"
                                class="formbold-form-input" required value="{{ old('name') }}" />
                        </div>
                        <div>
                            <label for="telepon" class="formbold-form-label">Nomor Telp/WhatsApp </label>
                            <input type="number" name="telepon" placeholder="085812345678" id="telepon"
                                class="formbold-form-input" required value="{{ old('telepon') }}" />
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="tgl_lahir" class="formbold-form-label"> Tanggal Lahir </label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="formbold-form-input" required
                                value="{{ old('tgl_lahir') }}" />
                        </div>
                        <div>
                            <label for="makanan_fav" class="formbold-form-label"> Makanan Favorit </label>
                            <input type="text" name="makanan_fav" id="makanan_fav" class="formbold-form-input"
                                placeholder="Mandai" required value="{{ old('makanan_fav') }}" />
                        </div>
                    </div>

                    <div>
                        <label for="address" class="formbold-form-label"> Alamat Lengkap </label>
                        <input type="text" name="alamat" id="address"
                            placeholder="Jl. Kaca Piring No. 8 Banjarmasin" class="formbold-form-input" required
                            value="{{ old('alamat') }}" />
                    </div>
                </div>

                <div class="formbold-form-step-2">
                    <div>
                        <label for="email" class="formbold-form-label">Email</label>
                        <input type="email" name="email" placeholder="example@mail.com" id="email"
                            class="formbold-form-input @error('email') is-invalid @enderror" required
                            value="{{ old('email') }}" />
                    </div>


                    <div>
                        <br>
                        <label for="password" class="formbold-form-label">Password</label>
                        <input type="password" name="password" id="password" class="formbold-form-input" required />
                        <div class="password-requirements">
                            <p> <b>Minimal 3</b> Validasi kriteria keamanan Terpenuhi</p>
                        </div>


                    </div>
                </div>

                <div class="formbold-form-step-3">
                    <div class="formbold-form-confirm">
                        <p>
                            Dengan Mendaftar Akun di Platform Food Fusion Kami, Apakah Anda menyetujui
                        </p>

                        <div>
                            <button class="formbold-confirm-btn active">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11" cy="11" r="10.5" fill="white" stroke="#DDE3EC" />
                                    <g clip-path="url(#clip0_1667_1314)">
                                        <path
                                            d="M9.83343 12.8509L15.1954 7.48828L16.0208 8.31311L9.83343 14.5005L6.12109 10.7882L6.94593 9.96336L9.83343 12.8509Z"
                                            fill="#536387" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1667_1314)">
                                            <rect width="14" height="14" fill="white"
                                                transform="translate(4 4)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                Yes! I want it.
                            </button>

                            <button class="formbold-confirm-btn">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11" cy="11" r="10.5" fill="white" stroke="#DDE3EC" />
                                    <g clip-path="url(#clip0_1667_1314)">
                                        <path
                                            d="M9.83343 12.8509L15.1954 7.48828L16.0208 8.31311L9.83343 14.5005L6.12109 10.7882L6.94593 9.96336L9.83343 12.8509Z"
                                            fill="#536387" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_1667_1314)">
                                            <rect width="14" height="14)" fill="white"
                                                transform="translate(4 4)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                                No! I don’t want it.
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hyperlink untuk login -->
                <div class="formbold-form-btn-wrapper">
                    <p>Sudah punya akun?<a href="{{ route('login') }}" class="login-link"> Masuk disini</a></p>


                    <button class="formbold-back-btn">
                        Kembali
                    </button>

                    <button class="formbold-btn">
                        Selanjutnya
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_1675_1807)">
                                <path
                                    d="M10.7814 7.33312L7.20541 3.75712L8.14808 2.81445L13.3334 7.99979L8.14808 13.1851L7.20541 12.2425L10.7814 8.66645H2.66675V7.33312H10.7814Z"
                                    fill="white" />
                            </g>
                            <defs>
                                <clipPath id="clip0_1675_1807)">
                                    <rect width="16" height="16)" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </form>

        </div>
    </div>



    <script>
        // Add these CSS rules to your existing <style> section
        const styleSheet = document.createElement("style");
        styleSheet.textContent = `
.formbold-form-input.valid {
    border-color: #4CAF50 !important;
    background-color: #f8fff8 !important;
}

.formbold-form-input.invalid {
    border-color: #FF5252 !important;
    background-color: #fff8f8 !important;
}

.password-requirements {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
}

.requirement {
    display: flex;
    align-items: center;
    gap: 5px;
    margin: 3px 0;
    position: relative;
    padding-left: 20px;
}

.requirement:before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #ddd;
    transition: all 0.3s ease;
}

.requirement.valid:before {
    border-color: #4CAF50;
    background-color: #4CAF50;
}

.requirement.invalid:before {
    border-color: #FF5252;
}

.password-strength-meter {
    height: 5px;
    background-color: #f3f3f3;
    border-radius: 3px;
    margin-top: 10px;
}

.strength-meter-fill {
    height: 100%;
    border-radius: 3px;
    transition: width 0.3s ease-in-out, background-color 0.3s ease-in-out;
    width: 0;
}

.very-weak { background-color: #FF4136; width: 20%; }
.weak { background-color: #FF851B; width: 40%; }
.medium { background-color: #FFDC00; width: 60%; }
.strong { background-color: #2ECC40; width: 80%; }
.very-strong { background-color: #01FF70; width: 100%; }


.password-wrapper {
    position: relative;
    width: 100%;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    padding: 5px;
    z-index: 10;
    background: none;
    border: none;
    display: flex;
    align-items: center;
}

.password-toggle i {
    color: #536387;
    font-size: 18px;
}

.password-toggle:hover i {
    color: #F44424;
}

`;
        document.head.appendChild(styleSheet);

        $(document).ready(function() {

            // Form elements
            const formSubmitBtn = $('.formbold-btn');
            const stepMenuOne = $('.formbold-step-menu1');
            const stepMenuTwo = $('.formbold-step-menu2');
            const stepMenuThree = $('.formbold-step-menu3');
            const stepOne = $('.formbold-form-step-1');
            const stepTwo = $('.formbold-form-step-2');
            const stepThree = $('.formbold-form-step-3');
            const formBackBtn = $('.formbold-back-btn');
            const emailInput = $('#email');
            const passwordInput = $('#password');

            // Add password requirements div
            const passwordRequirements = $('<div>', {
                class: 'password-requirements'
            }).html(`
        <div class="requirement length">Minimal 8 karakter</div>
        <div class="requirement letter">Minimal satu huruf kecil</div>
        <div class="requirement capital">Minimal satu huruf kapital</div>
        <div class="requirement number">Minimal satu angka</div>
        <div class="requirement special">Minimal satu karakter spesial</div>
    `);

            const strengthMeter = $('<div>', {
                class: 'password-strength-meter'
            }).html('<div class="strength-meter-fill"></div>');

            const strengthText = $('<div>', {
                class: 'password-strength-text',
                style: 'font-size: 12px; margin-top: 5px; color: #666;'
            });

            // Insert elements after password input
            passwordInput.after(strengthText);
            passwordInput.after(strengthMeter);
            passwordInput.after(passwordRequirements);


            const showPasswordIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>`;

            const hidePasswordIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
        <line x1="1" y1="1" x2="23" y2="23"/>
    </svg>`;

            passwordInput.wrap('<div class="password-wrapper"></div>');
            const toggleButton = $('<button>', {
                type: 'button',
                class: 'password-toggle',
                html: showPasswordIcon
            });
            passwordInput.after(toggleButton);

            toggleButton.on('click', function(e) {
                e.preventDefault();
                const input = passwordInput;

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    $(this).html(hidePasswordIcon);
                } else {
                    input.attr('type', 'password');
                    $(this).html(showPasswordIcon);
                }
            });

            // Email validation function
            function validateEmail(email) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (emailPattern.test(email)) {
                    emailInput.removeClass('invalid').addClass('valid');
                    return true;
                } else {
                    emailInput.removeClass('valid').addClass('invalid');
                    return false;
                }
            }

            function checkEmailExistence(email) {
                return $.ajax({
                    url: '/check-email', // Add this route to your web.php
                    method: 'POST',
                    data: {
                        email: email,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                });
            }

            // Modify the email input event handler
            emailInput.on('input', function() {
                const email = $(this).val();
                if (validateEmail(email)) {
                    // Check email existence only if format is valid
                    checkEmailExistence(email).then(function(response) {
                        if (response.exists) {
                            emailInput.removeClass('valid').addClass('invalid');
                            // Show warning using SweetAlert2
                            Swal.fire({
                                icon: 'warning',
                                title: 'Email Sudah Terdaftar',
                                text: 'Email ini sudah terdaftar. Silakan gunakan email lain atau login ke akun Anda.',
                                showCancelButton: true,
                                confirmButtonText: 'Login',
                                cancelButtonText: 'Gunakan Email Lain'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = '/login';
                                }
                            });
                        } else {
                            emailInput.removeClass('invalid').addClass('valid');
                        }
                    });
                }
            });


            // Password validation function
            function calculatePasswordStrength(password) {
                let strength = 0;
                const requirements = {
                    length: password.length >= 8,
                    letter: /[a-z]/.test(password),
                    capital: /[A-Z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
                };

                // Update requirement indicators
                Object.keys(requirements).forEach(req => {
                    const $requirement = $(`.requirement.${req}`);
                    if (requirements[req]) {
                        $requirement.addClass('valid').removeClass('invalid');
                        strength++;
                    } else {
                        $requirement.addClass('invalid').removeClass('valid');
                    }
                });

                return strength;
            }

            function updatePasswordStrength(strength) {
                const $strengthMeter = $('.strength-meter-fill');
                const $strengthText = $('.password-strength-text');

                $strengthMeter.removeClass('very-weak weak medium strong very-strong');
                let strengthClass, strengthText;

                switch (strength) {
                    case 1:
                        strengthClass = 'very-weak';
                        strengthText = 'Sangat Lemah';
                        break;
                    case 2:
                        strengthClass = 'weak';
                        strengthText = 'Lemah';
                        break;
                    case 3:
                        strengthClass = 'medium';
                        strengthText = 'Sedang';
                        break;
                    case 4:
                        strengthClass = 'strong';
                        strengthText = 'Kuat';
                        break;
                    case 5:
                        strengthClass = 'very-strong';
                        strengthText = 'Sangat Kuat';
                        break;
                    default:
                        strengthClass = '';
                        strengthText = '';
                }
                $strengthMeter.addClass(strengthClass);
                $strengthText.text(`Kekuatan Password: ${strengthText}`);
                return strength >= 3; // Returns true if password is at least medium strength
            }

            // Real-time validation for email
            emailInput.on('input', function() {
                validateEmail($(this).val());
            });

            // Real-time validation for password
            passwordInput.on('input', function() {
                const password = $(this).val();
                const strength = calculatePasswordStrength(password);
                const isValid = updatePasswordStrength(strength);

                if (isValid) {
                    $(this).removeClass('invalid').addClass('valid');
                } else {
                    $(this).removeClass('valid').addClass('invalid');
                }
            });

            // Handle back button
            formBackBtn.on('click', function(event) {
                event.preventDefault();
                if (stepMenuTwo.hasClass('active')) {
                    stepMenuTwo.removeClass('active');
                    stepMenuOne.addClass('active');
                    stepTwo.removeClass('active');
                    stepOne.addClass('active');
                    formBackBtn.removeClass('active');
                    formSubmitBtn.text('Selanjutnya');
                } else if (stepMenuThree.hasClass('active')) {
                    stepMenuThree.removeClass('active');
                    stepMenuTwo.addClass('active');
                    stepThree.removeClass('active');
                    stepTwo.addClass('active');
                    formBackBtn.addClass('active');
                    formSubmitBtn.text('Selanjutnya');
                }
            });

            // Handle next button
            formSubmitBtn.on('click', function(event) {
                event.preventDefault();

                if (stepMenuOne.hasClass('active')) {
                    const fullname = $('#fullname').val().trim();
                    const telepon = $('#telepon').val().trim();
                    const tglLahir = $('#tgl_lahir').val().trim();
                    const makananFav = $('#makanan_fav').val().trim();
                    const address = $('#address').val().trim();

                    if (fullname && telepon && tglLahir && makananFav && address) {
                        stepMenuOne.removeClass('active');
                        stepMenuTwo.addClass('active');
                        stepOne.removeClass('active');
                        stepTwo.addClass('active');
                        formBackBtn.addClass('active');
                        formSubmitBtn.text('Selanjutnya');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Perhatian!',
                            text: 'Mohon isi semua field yang wajib diisi'
                        });
                    }
                } else if (stepMenuTwo.hasClass('active')) {
                    const email = emailInput.val().trim();
                    const password = passwordInput.val().trim();
                    const strength = calculatePasswordStrength(password);

                    if (!email || !password) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Perhatian!',
                            text: 'Mohon isi semua field yang wajib diisi'
                        });
                        return;
                    }



                    const isEmailValid = validateEmail(email);
                    const isPasswordValid = strength >= 3; // Minimum medium strength required

                    if (!isEmailValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Perhatian!',
                            text: 'Format email tidak valid'
                        });
                        return;
                    }

                    if (!isPasswordValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Perhatian!',
                            text: 'Password terlalu lemah. Pastikan memenuhi minimal 3 kriteria keamanan'
                        });
                        return;
                    }

                    stepMenuTwo.removeClass('active');
                    stepMenuThree.addClass('active');
                    stepTwo.removeClass('active');
                    stepThree.addClass('active');
                    formBackBtn.addClass('active');
                    formSubmitBtn.text('Daftar !');
                } else if (stepMenuThree.hasClass('active')) {
                    const confirmBtns = $('.formbold-confirm-btn');
                    let confirmed = false;

                    confirmBtns.each(function() {
                        if ($(this).hasClass('active') && $(this).text().trim()
                            .includes('Yes')) {
                            confirmed = true;
                        }
                    });

                    if (!confirmed) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Mohon Perhatian!',
                            text: 'Silakan pilih konfirmasi terlebih dahulu'
                        });
                        return;
                    }

                    $('form').submit();
                }
            });

            // Handle confirmation buttons
            $('.formbold-confirm-btn').on('click', function(e) {
                e.preventDefault();
                $('.formbold-confirm-btn').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>

</body>

</html>
