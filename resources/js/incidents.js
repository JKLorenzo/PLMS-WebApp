function editIncident(id) {

    $(`#dotIcon${id}`).hide();

    const cancelbtn = $(`#cancelbtn${id}`);
    $.ajax({
        type: 'get',
        url: `incidents/${id}/edit`,
        data: $(this).serialize(),
        success: function (data) {

            for (const info of data) {
                const editIcon = $(`#_editIcon${info.id}`);

                cancelbtn.on('click', function () {
                    editIcon.html('');
                    cancelbtn.html('');
                    $(`#dotIcon${id}`).show();

                });

                editIcon.html(`<a href="#" onclick="modal(${info.incident_id}, ${info.id})"><i class="mdi mdi-playlist-edit fs-3"></i></a>`);
                cancelbtn.html('<button type="button" class="btn btn-danger btn-close py-1 px-2"></button>');
            }
        }
    });
}

function modal(incidentId, infoId) {

    $.ajax({
        type: 'get',
        url: `incidents/${infoId}/info`,
        data: $(this).serialize(),
        success: function (data) {
            const infoID = $('#infoID');
            const option = $('#default');
            const description = $('#description');

            for (const info of data) {
                const dis_title = info.title.charAt(0).toUpperCase() + info.title.slice(1);
                infoID.html('ID: ' + info.id);
                option.html(dis_title);
                option.val(dis_title);
                description.html(info.description);
            }
        }
    });
    $('#modalEditInfo').modal('show');
    $('#formUpdateInfo').attr('action', `incidents/${incidentId}/${infoId}`);

    $('#formUpdateInfo').on('submit', function (event) {
        event.preventDefault();
        $('#editinfobtn').attr('disabled', true);

        setTimeout(() => {
            $('#editinfobtn').attr('disabled', false);
        }, 2000);

        $.ajax({
            url: $(this).prop('action'),
            method: "PUT",
            data: $(this).serialize(),
            success: function (data) {
                $('#modalEditInfo').modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        })
    });
}

function addIncident(id) {
    $("#modalAddInfo").modal('show');
    $('#incidentID').html('Incident ID: ' + id);
    $('#formAddInfo').attr('action', `incidents/${id}/add`);

    $('#formAddInfo').on('submit', function (event) {
        event.preventDefault();
        var $this = $(this);
        $('#addinfobtn').attr('disabled', true);

        setTimeout(() => {
            $('#addinfobtn').attr('disabled', false);
        }, 2000);

        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: $this.serialize(),
            dataType: 'json',
            success: function (result) {
                $("#modalAddInfo").modal('hide');

                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
        });
    });
}

function deleteIncident(route) {
    $('#incident-form').attr('action', route);
}
