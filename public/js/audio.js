var classname = document.getElementsByClassName("play");
var player = $('#player');

var playOrStop = function() {
    var current = this.innerHTML;

    if (current === '<i class="fa fa-play"></i>') {
        for(var i = 0; i < classname.length; i++) {
            classname[i].innerHTML = '<i class="fa fa-play"></i>';
        }

        var audio = $(this).data('audio');

        if ($('#currentAudio').html() == '') {
            $('.player audio').css('visibility', 'visible');
            player.attr('src', audio);
            $('#currentAudio').html(audio);
        } else {
            var currentAudio = $('#currentAudio').html();

            if (currentAudio !== audio) {
                player.attr('src', audio);
                $('#currentAudio').html(audio);
            }
        }

        player[0].play();

        this.innerHTML = '<i class="fa fa-pause"></i>';
    } else {
        this.innerHTML = '<i class="fa fa-play"></i>';

        player[0].pause();
    }
};

for(var i = 0; i < classname.length; i++) {
    classname[i].addEventListener('click', playOrStop, false);
}
