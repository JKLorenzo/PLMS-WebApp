let timestamp = localStorage.getItem('timestamp');

$(document).on('ready', function () {
    var submitbtn = $('#submitbtn');
    var phone_num = $('#phone_number');
    var processing = $('#process');
    var spinner1 = $('#spinner1');
    var spinner2 = $('#spinner2');
    var spinner3 = $('#spinner3');
    var form = $('#unit-form');
    var line1 = $('#line1');
    var line2 = $('#line2');
    var start = $('#start');
    var controller = $('#controller');
    var message = $('#message');
    var pmessage = $('#p-message');

    form.on('submit', function (e) {
        e.preventDefault();
        start.css('background', 'white');
        controller.css('background', 'white');
        message.css('background', 'white');
        line1.css('background', 'white');
        line2.css('background', 'white');
        spinner1.html('<label class="text-muted">1</label>');
        submitbtn.html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            <span class="visually-hidden">Loading...</span>
        `);
        submitbtn.attr('disabled', true);
        spinner1.html(`
            <div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
            <span class="sr-only">Loading...</span>
            </div>
        `);
        spinner2.html('<label class="text-muted">2</label>');
        spinner3.html('<label class="text-muted">3</label>');
        processing.html('');

        phone_num.attr('class', 'form-control')
        pmessage.html('');

        $.ajax({
            type: "post",
            url: "units",
            data: $(this).serialize(),
            dataType: 'json',
            error: function (error) {
                const errors = error.responseJSON?.errors?.phone_number;
                if (Array.isArray(errors)) {
                    let output = "";
                    for (const message of errors) {
                        if (output.length > 0) output += "</br>"
                        output += `<strong>${message}</strong>`;
                    }

                    phone_num.attr('class', 'form-control is-invalid')
                    pmessage.html(output);
                    spinner1.html('<label class="text-muted">1</label>');
                    submitbtn.html('<i class="mdi mdi-send"></i>');
                    submitbtn.attr('disabled', false);
                    submitbtn.show();
                }
            },
        });
    });

    Echo.private("Units").listen("UnitRegisterUpdate", (data) => {
        switch (data.message) {
            case "start":
                submitbtn.attr('disabled', true);
                submitbtn.html(`
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true">
                    </span><span class="visually-hidden">Loading...</span>`
                );
                processing.html('<label class="fs-5 pb-2 fw-bold text-success">Processing...</label>');
                controller.css('background', 'white');
                spinner1.html(`
                    <div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                        <span class="sr-only">Loading...</span>
                    </div>
                `);
                break;
            case "published":
                start.css('background', '#63d19e');
                spinner1.html(
                    '<i class="mdi mdi-check"></i>');
                line1.css('background-color', '#63d19e');
                spinner2.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                        <span class="sr-only">Loading...</span>
                        </div>`);
                break;
            case "controller 1":
                controller.css('background', '#63d19e');
                spinner2.html(
                    '<i class="mdi mdi-check"></i>');
                line2.css('background-color', '#63d19e');
                processing.html(
                    '<label class="fs-5 pb-2 text-success">Connected to Controller</label>'
                );

                spinner3.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                        <span class="sr-only">Loading...</span>
                        </div>`);
                break;
            case "controller 0":
                submitbtn.show();
                processing.html(
                    '<label class="fs-5 pb-2 text-danger">There was an error connecting to controller..</label>'
                );

                controller.css('background', '#f1556c');
                spinner2.html(
                    '<i class="mdi mdi-alert-rhombus fs-4"></i>');
                submitbtn.html('re-send');
                submitbtn.attr('disabled', false);

                break;
            case "message 1":
                message.css('background', '#63d19e');
                processing.html(
                    '<label class="fs-5 pb-2 text-success">Unit Registered Succesfully</label>'
                );
                spinner3.html(
                    '<i class="mdi mdi-check fs-5"></i>');
                submitbtn.html('<i class="mdi mdi-send"></i>');

                location.reload();
                break;
            case "message 0":
                submitbtn.show();
                message.css('background', '#f1556c');
                processing.html(
                    '<label class="fs-5 pb-2 text-danger">There was an error during sending SMS</label>'
                );
                spinner3.html(
                    '<i class="mdi mdi-alert-rhombus fs-4"></i>');
                submitbtn.html('re-send');
                submitbtn.attr('disabled', false);
                spinner3.addClass('resubmit');
                break;
        }
    });
});


function removeUnit(id) {
    $('#remove-id').attr('action', `units/${id}`);
}

function refreshUnit(id) {
    var refresh = document.getElementById(`ref-icon[${id}]`);

    var refreshbtn = document.getElementById(`refreshbtn[${id}]`);
    refreshbtn.disabled = true;

    refresh.className = "fe-refresh-ccw text-success fs-5 rotate";
    setTimeout(() => {
        refresh.className = "fe-refresh-ccw text-success fs-5";
    }, 1000);

    var processing = $('#process2');
    var closebtn = $('#close-btn');
    var spinner1 = $('#s1');
    var spinner2 = $('#s2');
    var spinner3 = $('#s3');
    var line1 = $('#line-1');
    var line2 = $('#line-2');
    var start = $('#start2');
    var controller = $('#controller2');
    var message = $('#message2');
    var pmessage = $('#post-message');

    closebtn.hide();

    start.css('background', 'white');
    controller.css('background', 'white');
    message.css('background', 'white');
    line1.css('background', 'white');
    line2.css('background', 'white');
    spinner1.html('<label class="text-muted">1</label>');


    spinner1.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                <span class="sr-only">Loading...</span>
                </div>`);
    spinner2.html('<label class="text-muted">2</label>');
    spinner3.html('<label class="text-muted">3</label>');
    processing.html('');

    pmessage.html('');

    $('#refreshModal').modal('show');

    Echo.private("Units").listen("UnitRefreshUpdate", (data) => {
        console.log(data.message);
        switch (data.message) {
            case "start":
                $('#refreshModal').modal('show');
                processing.html('<label class="fs-4 pb-2 fw-bold text-success">Processing...</label>');
                controller.css('background', 'white');
                spinner1.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                <span class="sr-only">Loading...</span>
                </div>`);
                break;
            case "published":
                start.css('background', '#63d19e');
                spinner1.html('<i class="mdi mdi-check"></i>');
                line1.css('background-color', '#63d19e');
                spinner2.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                        <span class="sr-only">Loading...</span>
                        </div>`);
                break;
            case "controller 1":
                controller.css('background', '#63d19e');
                spinner2.html('<i class="mdi mdi-check"></i>');
                line2.css('background-color', '#63d19e');
                processing.html('<label class="fs-4 pb-2 text-success">Connected to Controller</label>');
                spinner3.html(`<div class="spinner-border text-secondary" role="status" style="margin-top: 2px">
                        <span class="sr-only">Loading...</span>
                        </div>`);
                break;
            case "controller 0":
                closebtn.show();
                processing.html('<label class="fs-4 pb-2 text-danger">There was an error connecting to controller.</label>');
                controller.css('background', '#f1556c');
                spinner2.html(
                    '<i class="mdi mdi-alert-rhombus fs-4"></i>');
                break;
            case "message 1":
                setTimeout(() => {
                    $('#refreshModal').modal('hide');
                }, 2000);

                message.css('background', '#63d19e');
                processing.html('<label class="fs-4 pb-2 text-success">Request Sent!</label>');
                spinner3.html('<i class="mdi mdi-check fs-5"></i>');
                break;
            case "message 0":
                closebtn.show();
                message.css('background', '#f1556c');
                processing.html('<label class="fs-4 pb-2 text-danger">There was an error in sending request</label>');
                spinner3.html('<i class="mdi mdi-alert-rhombus fs-4"></i>');
                spinner3.addClass('resubmit');
                break;
        }
    });

    $.ajax({
        type: 'get',
        url: `units/${id}/refresh`,
        data: $(this).serialize(),
        dataType: 'json',
    });
}
