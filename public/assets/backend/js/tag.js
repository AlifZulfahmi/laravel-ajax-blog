let submit_method;

$(document).ready(function () {
    TagTable();
});

// DataTable serverSide
function TagTable() {
    $('#yajra').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/admin/tags/serverside",
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        },
        {
            data: 'name',
            name: 'name'
        },
        {
            data: 'slug',
            name: 'slug'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        }
        ]
    });
}

// from create
const modalTag = () => {
    submit_method = 'create';
    resetForm('#formTag');
    resetValidation();
    $('#modalTag').modal('show');
    $('.modal-title').html('<i class="fa fa-plus"></i> Create Tag');
    $('.btn-submit').html('<i class="fa fa-save"></i> Save');
}


// from edit
const editData = (e) => {
    let uuid = e.getAttribute('data-id');

    startLoading();
    resetForm('#formTag');
    resetValidation();

    $.ajax({
        type: "GET",
        url: "/admin/tags/" + uuid,
        success: function (response) {
            let parseData = response.data;


            $('#uuid').val(parseData.uuid);
            $('#name').val(parseData.name);
            $('#modalTag').modal('show');
            $('.modal-title').html('<i class="fa fa-edit"></i> Edit Tag');
            $('.btn-submit').html('<i class="fa fa-save"></i> Save');

            submit_method = 'edit';

            stopLoading();
        },
        error: function (jqXHR) {
            console.log(jqXHR.responseText);
            toastError(jqXHR.responseText);
        }
    });
}



const deleteData = (e) => {
    let id = e.getAttribute('data-id');
    console.log("Deleting Tag with UUID:", id);

    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Delete",
    }).then((result) => {
        if (result.value) {
            startLoading();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
                },
                type: "DELETE",
                url: "/admin/tags/" + id,
                dataType: "JSON",
                success: function (response) {

                    reloadTable();
                    toastSuccess(response.message);

                },
                error: function (jqXHR) {
                    console.log(jqXHR.responseText);
                    toastError(jqXHR.responseText);
                }
            });
        }
    });
};



$('#formTag').on('submit', function (e) {
    e.preventDefault();

    startLoading();

    let url, method;
    url = '/admin/tags';
    method = 'POST';

    const inputFrom = new FormData(this);


    if (submit_method == 'edit') {
        url = '/admin/tags/' + $('#uuid').val();
        inputFrom.append('_method', 'PUT');
    } else {
        url = '/admin/tags';
        method = 'POST';
    }

    $.ajax({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
        },
        type: method,
        url: url,
        data: inputFrom,
        contentType: false,
        processData: false,
        success: function (response) {
            $('#modalTag').modal('hide');
            reloadTable();
            resetValidation();
            stopLoading();
            toastSuccess(response.message);

        },
        error: function (jqXHR) {
            console.log(jqXHR.responseText);
            toastError(jqXHR.responseText);
        }


    })

});