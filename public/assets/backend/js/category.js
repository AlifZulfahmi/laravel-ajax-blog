let submit_method;

$(document).ready(function () {
    CategoryTable();
});

function CategoryTable() {
    $('#yajra').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "/admin/categories/serverside",
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

const modalCategory = () => {
    submit_method = 'create';

    resetForm('#formCategory');
    resetValidation();
    $('#modalCategory').modal('show');
    $('.modal-title').html('<i class="fa fa-plus"></i> Create Category');
    $('.btn-submit').html('<i class="fa fa-save"></i> Save');
}

const editData = (e) => {
    let uuid = e.getAttribute('data-id');

    startLoading();
    resetForm('#formCategory');
    resetValidation();

    $.ajax({
        type: "GET",
        url: "/admin/categories/" + uuid,
        success: function (response) {
            let parseData = response.data;


            $('#uuid').val(parseData.uuid);
            $('#name').val(parseData.name);
            $('#modalCategory').modal('show');
            $('.modal-title').html('<i class="fa fa-edit"></i> Edit Category');
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
    console.log("Deleting category with UUID:", id);

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
                url: "/admin/categories/" + id,
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



$('#formCategory').on('submit', function (e) {
    e.preventDefault();

    startLoading();

    let url, method;
    url = '/admin/categories';
    method = 'POST';

    const inputFrom = new FormData(this);


    if (submit_method == 'edit') {
        url = '/admin/categories/' + $('#uuid').val();
        inputFrom.append('_method', 'PUT');
    } else {
        url = '/admin/categories';
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
            $('#modalCategory').modal('hide');
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