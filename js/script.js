$(window).ready(function () {
    if (url) {
        $('#status').show();
        $.ajax({
            type: 'POST',
            url: 'handlers/getInfo.php',
            data: { 'url': url, },
            dataType: 'json',
            timeout: 10000,
        }).always(function (data) {
            $('#status').hide();
            console.log(data);
        }).done(function (data) {
            title = data.title;
            duration = data.duration;
            mp4 = data.mp4;
            m4a = data.m4a;
            filename = data.filename;
            $('#thumbnail').attr('src', data.thumbnail);
            $('#title').text(title);
            $('.btn-info').show();
        }).fail(function (data) {
            $('#title').text('Failed');
        });
    }
});

function downloadM4A(url) {
    $("#progress-text").text("Converting to M4A");
    $('#progress').show();
    $('.btn-info').hide();
    $('#progress').css('animation-duration', m4a + "s");
    $.ajax({
        type: 'POST',
        url: 'handlers/downloadAudio.php',
        data: {'url': url,},
        dataType: 'json',
    }).done(function (data) {
        success(data, 'm4a');
    }).fail(function (data) {
        fail(data, 'm4a');
    });
}
function downloadMP4(url) {
    $("#progress-text").text("Converting to MP4");
    $('#progress').show();
    $('.btn-info').hide();
    $('#progress').css('animation-duration', mp4 + "s");
    $.ajax({
        type: 'POST',
        url: 'handlers/downloadVideo.php',
        data: {'url': url,},
        dataType: 'json',
    }).done(function (data) {
        console.log(data);
        success(data, 'mp4');
    }).fail(function (data) {
        fail(data, 'mp4');
    });
}
function success(data, type) {
    if (data.error == "") {
        $('#progress').css('background-color', '#28a745');
        $('#progress').toggleClass('transition-slow progress-bar-animated progress-bar-striped');
        $('#progress').css('width', '100%');
        $('#download').attr('href', 'audio/' + filename + '.' + type);
        $('#download').show();
        $("#progress-text").toggleClass("text-animation");
        $("#progress-text").text("Complete!");
        $('#download').show();
    } else {
        fail(data, type);
    }
}
function fail(data, type) {
    console.log("fail");
    console.log(data);
    $('#progress').addClass('progress-bar-danger transition-fast');
    $('#progress').css('width', '100%');
    $('#progress').toggleClass('transition-slow progress-bar-striped');
    $('#download').attr('href', 'audio/' + filename + '.' + type);
    $('#download').show();
    $("#progress-text").toggleClass("text-animation");
    $("#progress-text").text("Failed! You can try to download anyway!");
}