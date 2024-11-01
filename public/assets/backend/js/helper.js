let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})

const toastSuccess = (message) => {
    Toast.fire({
        icon: 'success',
        title: message
    })
}

const toastError = (message) => {
    let resJson;

    try {
        resJson = JSON.parse(message);
    } catch (error) {
        console.log("Error parsing JSON:", error);
        return; // Jika parsing JSON gagal
    }

    let errorText = '';

    // Akses error dari Laravel
    if (resJson.errors) {
        for (let key in resJson.errors) {
            errorText = resJson.errors[key][0]; // Ambil pesan error pertama dari tiap field
            break;
        }
    } else {
        errorText = resJson.message; // Ambil pesan umum jika tidak ada detail
    }

    Toast.fire({
        icon: 'error',
        title: 'Data cannot be saved <br>' + errorText
    });
};


const startLoading = () => {
    Swal.fire({
        title: 'Please wait...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    })
}

const stopLoading = () => {
    Swal.close()
}

const reloadTable = () => {
    $('.table').DataTable().draw(false);
}

const resetForm = (form) => {
    $(form)[0].reset();
}

const resetValidation = () => {
    $('.is-invalid').removeClass('is-invalid');
    $('.is-valid').removeClass('is-valid');
    $('.span.invalid-feedback').remove();
}