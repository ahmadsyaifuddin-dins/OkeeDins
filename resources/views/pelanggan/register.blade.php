<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Pelanggan</title>

    <link rel="stylesheet" href="{{ url('css/register.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- IntlTelInput CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>
    <!-- IntlTelInput JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <!-- Pace.js -->
    <script src="https://cdn.jsdelivr.net/npm/pace-js@latest/pace.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pace-js@latest/pace-theme-default.min.css">
    <style>
        .pace {
            -webkit-pointer-events: none;
            pointer-events: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        .pace-inactive {
            display: none;
        }

        .pace .pace-activity {
            display: block;
            position: fixed;
            z-index: 2000;
            top: 50%;
            left: 50%;
            width: 50px;
            height: 50px;
            margin: -25px 0 0 -25px;
            border: 3px solid transparent;
            border-top-color: #D32F2F;
            border-radius: 50%;
            -webkit-animation: pace-spinner 0.8s linear infinite;
            animation: pace-spinner 0.8s linear infinite;
        }

        @-webkit-keyframes pace-spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pace-spinner {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .formbold-main-wrapper {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Pace.on('done', function() {
                document.querySelector('.formbold-main-wrapper').style.display = 'block';
            });
        });
    </script>
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
                            html: '{{ implode('<br>', $errors->all()) }}',
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
                            <label for="fullname" class="formbold-form-label">Nama Lengkap <b
                                    style="color: red;">*</b></label>
                            <input type="text" name="name" placeholder="John Doe" id="fullname"
                                class="formbold-form-input" required value="{{ old('name') }}" />
                        </div>
                        <div>
                            <label for="telepon" class="formbold-form-label">Nomor Telp/WhatsApp <b
                                    style="color: red;">*</b></label>
                            <div class="telepon-wrapper">
                                <input type="tel" name="telepon" id="telepon" class="formbold-form-input" required
                                    value="{{ old('telepon') }}" inputmode="numeric" pattern="[0-9]*"
                                    placeholder="8xxxxxxxx" />
                            </div>
                            <small class="telepon-hint">Contoh awalan: 8xxxxxxx</small>
                            <span id="valid-msg" class="hide">✓ Valid</span>
                            <span id="error-msg" class="hide"></span>
                        </div>
                    </div>

                    <div class="formbold-input-flex">
                        <div>
                            <label for="tgl_lahir" class="formbold-form-label">Tanggal Lahir <b
                                    style="color: red;">*</b></label>
                            <input type="date" name="tgl_lahir" id="tgl_lahir" class="formbold-form-input" required
                                value="{{ old('tgl_lahir') }}" />
                        </div>

                        <div class="mydict">
                            <label for="jenis_kelamin" class="formbold-form-label">Jenis Kelamin <b
                                    style="color: red;">*</b></label>
                            <div>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="Laki-Laki"
                                        class="formbold-radio-input" required
                                        {{ old('jenis_kelamin') == 'Laki-Laki' ? 'checked' : '' }} />
                                    <span>Laki-Laki</span>
                                </label>
                                <label class="formbold-radio-label">
                                    <input type="radio" name="jenis_kelamin" value="Perempuan"
                                        class="formbold-radio-input" required
                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'checked' : '' }} />
                                    <span>Perempuan</span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div>
                        <label for="makanan_fav" class="formbold-form-label">Makanan Favorit <b
                                style="color: red;">*</b></label>
                        <input type="text" name="makanan_fav" id="makanan_fav" class="formbold-form-input"
                            placeholder="Mandai, Bilungka, Kolak" required value="{{ old('makanan_fav') }}" />
                    </div>
                    <br>
                    <div>
                        <label for="address" class="formbold-form-label">Alamat Lengkap <b
                                style="color: red;">*</b></label>
                        <textarea name="alamat" id="address" placeholder="Isilah Alamat Lengkap untuk pengiriman paket anda📦!"
                            class="formbold-form-input" style="resize: none; height: 100px; overflow: hidden;"
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'" required>{{ old('alamat') }}</textarea>
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
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="formbold-form-input"
                                required />
                        </div>

                        <!-- Password requirements section is now more organized -->
                        <div class="password-validation-container">
                            <div class="password-requirements">
                                <div class="requirement length">Password minimal 8 karakter</div>
                                <div class="requirement letter">satu huruf kecil</div>
                                <div class="requirement number">satu angka</div>
                                <div class="requirement capital">satu huruf kapital</div>
                                <div class="requirement special">satu karakter spesial</div>
                                <div class="password-strength-meter">
                                    <div class="strength-meter-fill"></div>
                                </div>
                                <small><b>Minimal 3</b> Kriteria Keamanan diatas dan wajib 8 karakter !</small>
                                <div class="password-strength-text"></div>
                            </div>
                        </div>

                        <div>
                            <br>
                            <label for="password_confirmation" class="formbold-form-label">Konfirmasi Password</label>
                            <div class="password-wrapper">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="formbold-form-input" required />
                                <button type="button" class="password-toggle">
                                    <!-- Removed fa icon, will be replaced by SVG via JavaScript -->
                                </button>
                            </div>
                            <div class="password-match-indicator"></div>
                        </div>

                    </div>
                </div>

                <div class="formbold-form-step-3">
                    <div class="formbold-form-confirm">
                        <h2 class="character-title">Pilih Karakter Yang Mencerminkan Dirimu!</h2>
                        <input type="hidden" name="type_char" id="type_char" value="">

                        <div class="character-selection">
                            <div class="character-card" onclick="selectCharacter('Hero')" id="hero-card">
                                <div class="character-image">
                                    <img src="{{ asset('images/hero-character.png') }}" alt="Hero Character">
                                    <div class="character-glow hero-glow"></div>
                                </div>
                                <div class="character-info">
                                    <h3>Hero</h3>
                                    <p>Pejuang kebenaran sejati</p>
                                </div>
                            </div>
                            <div class="character-card" onclick="selectCharacter('Villain')" id="villain-card">
                                <div class="character-image">
                                    <img src="{{ asset('images/villain-character.png') }}" alt="Villain Character">
                                    <div class="character-glow villain-glow"></div>
                                </div>
                                <div class="character-info">
                                    <h3>Villain</h3>
                                    <p>Penguasa kegelapan</p>
                                </div>
                            </div>
                            <div class="character-card" onclick="selectCharacter('Anti Hero')" id="anti-hero-card">
                                <div class="character-image">
                                    <img src="{{ asset('images/anti_hero_char.png') }}" alt="Anti Hero Character">
                                    <div class="character-glow anti-hero-glow"></div>
                                </div>
                                <div class="character-info">
                                    <h3>Anti Hero</h3>
                                    <p>Pahlawan berani berbeda</p>
                                </div>
                            </div>
                            <div class="character-card" onclick="selectCharacter('Anti Villain')"
                                id="anti-villain-card">
                                <div class="character-image">
                                    <img src="{{ asset('images/anti_villain_char.jpg') }}"
                                        alt="Anti Villain Character">
                                    <div class="character-glow anti-villain-glow"></div>
                                </div>
                                <div class="character-info">
                                    <h3>Anti Villain</h3>
                                    <p>Antagonis bermotif mulia</p>
                                </div>
                            </div>
                        </div>

                        <div class="scroll-indicator">
                            <span class="scroll-dot active"></span>
                            <span class="scroll-dot"></span>
                            <span class="scroll-dot"></span>
                            <span class="scroll-dot"></span>
                        </div>

                        <div class="scroll-hint">
                            Geser untuk melihat karakter lainnya
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
                                <clipPath id="clip0_1675_1807">
                                    <rect width="16" height="16" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </button>
                </div>
            </form>


        </div>
    </div>

    <script src="{{ url('js/register.js') }}"></script>

</body>

</html>
