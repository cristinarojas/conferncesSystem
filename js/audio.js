$('.play').on('click', function() {
    var audio = $(this).data('audio');
    var player = $('#player');

    player.attr('src', audio);

    player[0].pause();
    player[0].load();
    player[0].play();
});
