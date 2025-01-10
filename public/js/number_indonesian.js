//!Start Untuk Inputan Telepon (Khusus Indonesia)
// Definisi format provider Indonesia yang valid dan lengkap
const INDONESIA_CARRIERS = {
    // Telkomsel Group
    telkomsel: {
        prefixes: ['0811', '0812', '0813', '0821', '0822', '0823', '0851', '0852', '0853'],
        lengths: [11, 12, 13],
        name: 'Telkomsel'
    },
    kartuHalo: {
        prefixes: ['0811'],
        lengths: [11, 12, 13],
        name: 'kartuHalo'
    },
    simplePon: {
        prefixes: ['0822', '0823'],
        lengths: [11, 12, 13],
        name: 'simPATI Loop'
    },
    byU: {
        prefixes: ['0851', '0852', '0853'],
        lengths: [11, 12, 13],
        name: 'by.U'
    },

    // Indosat Group
    indosat: {
        prefixes: ['0814', '0815', '0816', '0855', '0856', '0857', '0858'],
        lengths: [11, 12, 13],
        name: 'Indosat Ooredoo'
    },
    matrix: {
        prefixes: ['0855', '0856', '0857', '0858'],
        lengths: [11, 12, 13],
        name: 'Matrix'
    },
    mentari: {
        prefixes: ['0815', '0816'],
        lengths: [11, 12, 13],
        name: 'Mentari'
    },

    // XL Group
    xl: {
        prefixes: ['0817', '0818', '0819', '0859', '0877', '0878', '0831', '0832', '0833'],
        lengths: [11, 12, 13],
        name: 'XL'
    },
    axis: {
        prefixes: ['0831', '0832', '0833', '0838'],
        lengths: [11, 12, 13],
        name: 'Axis'
    },

    // Tri (3)
    tri: {
        prefixes: ['0895', '0896', '0897', '0898', '0899'],
        lengths: [11, 12, 13],
        name: 'Tri (3)'
    },

    // Smartfren Group
    smartfren: {
        prefixes: ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
        lengths: [11, 12, 13],
        name: 'Smartfren'
    },

    // MOBILE VIRTUAL NETWORK OPERATOR (MVNO)
    switch: {
        prefixes: ['0881', '0882', '0883', '0884', '0885', '0886', '0887', '0888', '0889'],
        lengths: [11, 12, 13],
        name: 'Switch Mobile'
    }
};

// Modifikasi fungsi validasi
const input = document.querySelector("#telepon");
const errorMsg = document.querySelector("#error-msg");
const validMsg = document.querySelector("#valid-msg");

const iti = window.intlTelInput(input, {
    initialCountry: "id",
    preferredCountries: ["id", "my", "sg", "au"],
    separateDialCode: true,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
});

// Extended error messages
const errorMap = {
    INVALID_NUMBER: "Nomor tidak valid",
    INVALID_COUNTRY: "Kode negara tidak valid",
    TOO_SHORT: "Nomor terlalu pendek",
    TOO_LONG: "Nomor terlalu panjang",
    INVALID_FORMAT: "Format nomor tidak valid",
    INVALID_PREFIX: "Awalan nomor tidak valid untuk provider Indonesia",
    INVALID_LENGTH: "Panjang nomor tidak sesuai standar provider",
    NOT_INDONESIAN: "Mohon gunakan nomor Indonesia"
};

function isValidIndonesianCarrier(number) {
    // Remove any non-digit characters
    const cleanNumber = number.replace(/\D/g, '');

    // Check if it's an Indonesian number
    if (!cleanNumber.startsWith('0')) {
        return {
            isValid: false,
            error: 'Nomor harus dimulai dengan 0'
        };
    }


    // Check against all carrier prefixes
    let validPrefix = false;
    let validLength = false;
    let carrierInfo = null;

    for (const [carrierKey, carrier] of Object.entries(INDONESIA_CARRIERS)) {
        if (carrier.prefixes.some(prefix => cleanNumber.startsWith(prefix))) {
            validPrefix = true;
            carrierInfo = {
                name: carrier.name,
                type: getCarrierType(carrierKey)
            };
            if (carrier.lengths.includes(cleanNumber.length)) {
                validLength = true;
                break;
            }
        }
    }

    if (!validPrefix) {
        return {
            isValid: false,
            error: 'Awalan nomor tidak valid untuk provider Indonesia'
        };
    }

    if (!validLength) {
        return {
            isValid: false,
            error: 'Panjang nomor tidak sesuai standar provider'
        };
    }

    return {
        isValid: true,
        carrierInfo: carrierInfo
    };
}

// Helper function untuk mendapatkan tipe provider
function getCarrierType(carrierKey) {
    const mvno = ['switch'];
    const prepaid = ['byU', 'tri', 'axis', 'mentari'];
    const postpaid = ['kartuHalo', 'matrix'];

    if (mvno.includes(carrierKey)) return 'MVNO';
    if (prepaid.includes(carrierKey)) return 'Prepaid';
    if (postpaid.includes(carrierKey)) return 'Postpaid';
    return 'Regular';
}



// Prevent non-numeric input
input.addEventListener("keypress", function (e) {
    if (e.key.match(/[^0-9]/)) {
        e.preventDefault();
    }
});

// Prevent non-numeric paste
input.addEventListener("paste", function (e) {
    e.preventDefault();
    const pastedText = (e.clipboardData || window.clipboardData).getData("text");
    if (pastedText.match(/^\d+$/)) {
        this.value = pastedText;
        validatePhoneNumber(); // Trigger validation after paste
    }
});

// Remove non-numeric characters and validate on input
input.addEventListener("input", function (e) {
    this.value = this.value.replace(/[^\d]/g, "");
    validatePhoneNumber();
});

function resetPhoneValidation() {
    input.classList.remove("error", "valid", "invalid");
    errorMsg.innerHTML = "";
    errorMsg.classList.remove("show");
    errorMsg.classList.add("hide");
    validMsg.classList.remove("show");
    validMsg.classList.add("hide");
}

// Modifikasi fungsi validasi utama
function validatePhoneNumber() {
    resetPhoneValidation();

    if (input.value.trim()) {
        const carrierCheck = isValidIndonesianCarrier(input.value);

        if (!carrierCheck.isValid) {
            input.classList.remove("valid");
            input.classList.add("invalid");
            errorMsg.innerHTML = carrierCheck.error;
            errorMsg.classList.remove("hide");
            setTimeout(() => errorMsg.classList.add("show"), 10);
            return false;
        }

        // Additional international format validation
        if (iti.isValidNumber()) {
            const carrierInfo = carrierCheck.carrierInfo;
            validMsg.innerHTML = `✓ Valid (${carrierInfo.name} - ${carrierInfo.type})`;
            validMsg.classList.remove("hide");
            setTimeout(() => validMsg.classList.add("show"), 10);
            input.classList.add("valid");
            input.classList.remove("invalid");
            return true;
        } else {
            input.classList.remove("valid");
            input.classList.add("invalid");
            errorMsg.innerHTML = "Format nomor tidak valid";
            errorMsg.classList.remove("hide");
            setTimeout(() => errorMsg.classList.add("show"), 10);
            return false;
        }
    }
    return false;
}

// Event listeners
const phoneInput = document.querySelector("#telepon");
phoneInput.addEventListener("blur", validatePhoneNumber);
phoneInput.addEventListener("change", validatePhoneNumber);
phoneInput.addEventListener("keyup", function () {
    if (this.value.length > 0) {
        validatePhoneNumber();
    } else {
        this.classList.remove("valid", "invalid");
        document.querySelector("#valid-msg").classList.add("hide");
        document.querySelector("#error-msg").classList.add("hide");
    }
});
//!End Untuk Inputan Telepon