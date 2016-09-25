$(function () {
    $.AdminLTE.srController('video_poster', function () {
        this.index = function() {

            $('.capture').click(function (e) {
                e.preventDefault();
                var frame = captureVideoFrame(document.getElementById('video' + e.target.dataset.video),'jpeg');
                var poster = frame.dataUri;

                $.post('/admin/media/save-poster', {
                    'video': poster,
                    'path': this.dataset.videoPath
                }, function (data) {
                    console.log(data);
                });

            });
        };
    });
});