$(document).ready(function(){
    $(document).on('click', '.button_upload', function(){
        titleVid = $('#titlev').val();
        descriptionVid = $('#desc').val();
        tagsVid = $('#tags').val();
        categoryVid = $('#category').val();
        privateVid = $('#private').val();
        fileVid = $('#file_name').text();
        $('.upload_form').fadeOut(300);
            setTimeout(() => $('.upload_form').remove(), 500);
            $('.progress_bar').html('<span class="small_text"><img id="loading" style="margin-left:5px;" src="img/loading.gif">Конвертация видео...</span>');
            $('.progress_bar').addClass('converting');
            $('.converting').removeClass('progress_bar');
            $('.progress_bar_filled').remove();
            $('.progress_bar_percent').remove();
        $.post('/upload.php', {title:titleVid, file:fileVid, description:descriptionVid, tags:tagsVid, category:categoryVid, private:privateVid}, function(response){
            if(response == '"err"'){
                $("#alerts").html(`<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Произошла ошибка при добавлении видео. Попробуйте позже. (Error Code - #f3054U)
                        </div>
                    </div><br>`);
                $('#container').remove();
            }
            if(response != '"err"' && response != ''){
                location.href = '/watch?v=' + response.replace(/['"]+/g, '');
            }else{
                $("#alerts").html(`<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Произошла ошибка при добавлении видео. Попробуйте позже. (Error Code - #f3293U)
                        </div>
                    </div><br>`);
                $('#container').remove();
            }
        })
    })
    $(document).on('click', '.button_upload', function(){
        $(this).hide(0);
    })
    $(document).on('click', '#deletefavch', function(){
        $.post('/extra/user/ajax/deletech.php', {id:$(this).data('favch')}, function(){location.reload()})
    })
    $(document).on('click', '.global--link', function(){
        location.href = $(this).data('link');
    })
    $(document).on('click', '#oVaS', function(){
        if($('.mt-masthead-upr').hasClass('hidden')){
            $('.mt-masthead-upr').removeClass('hidden');
        }else{
            $('.mt-masthead-upr').addClass('hidden');
        }
    })
    $(document).on('click', 'button[type=submit]', function(){
        $(this).hide(0);
        
    })
    $(document).on('click', '#deletecomu', function(){
        $('#comu' + $(this).data('comu')).fadeOut(450);
        $.post('/extra/user/ajax/deletecomu.php', {comuid:$(this).data('comu')}, function(){})
    })
    $(document).on('click', '#add_link_channel', function(){
        $('#popups').html('<div class="popup"><div class="popup-container"><div class="popup-header">Добавить ссылку<div class="close_popup">✕</div></div><div class="popup-content"><form method="POST"><span>Название</span><br><input maxlength="24" type="text" name="title" class="input"><span>URL</span><br><input type="text" name="url" class="input"><div class="mt-display-flex"><input type="submit" name="add_link" class="mt-uix-button mt-button-primary mt-margin-left" value="Добавить"></div></form></div></div></div>');
    })
    $(document).on('click', '#deletelink', function(){
        $('#link' + $(this).data('link')).fadeOut(450);
        $.post('extra/user/ajax/deletelink.php', {linkid:$(this).data('link')}, function(){})
    })
    $(document).on('click', '#deletechp', function(){
        $('.chpla' + $(this).data('pl')).fadeOut(450);
        setTimeout(() => $('.chpla' + $(this).data('pl')).remove(), 500);
        $.post('/extra/playlists/deletech.php', {playlist:$(this).data('pl')}, function(){})
    })
    $(document).on('click', '.global--subscribed', function(){
        if(!$(this).hasClass('global--subscribe')){
            $(this).removeClass('global--subscribed');
            $(this).addClass('global--subscribe');
            counter = $('.watch-page-subs-counter').text();
            if(counter != 0){
                counter--;
                $('.watch-page-subs-counter').html(counter);
                $.post('/extra/user/ajax/subscribe.php', {unsubscribe: $(this).data('user')}, function(data){
                    if(data == '"error"'){
                        $('body').html('');
                        location.href = '/login';
                    }
                })
            }
        }
    })
    $(document).on('click', '.global--edit-channel', function(){
        location.href = '/personalize';
    })
    $(document).on('click', '#trailer', function(){
        $('#popups').html('<div class="popup"><div class="popup-container"><div class="popup-header">Добавить видео как трейлер<div class="close_popup">✕</div></div><div class="popup-content"><form method="POST"><span>URL видео</span><br><input type="text" name="url" class="input"><div class="mt-display-flex"><input type="submit" name="add_trailer" class="mt-uix-button mt-button-primary mt-margin-left" value="Добавить"></div></form></div></div></div>');
    })
    $(document).on('click', '.like-comment', function(){
        if($('#dislikecom' + $(this).data('comment')).hasClass('disliked-comment')){
            $('#dislikecom' + $(this).data('comment')).removeClass('disliked-comment');
            $('#dislikecom' + $(this).data('comment')).addClass('dislike-comment');
        }
        $.post('extra/comments/like.php', {like:$(this).data('comment')}, function(){});
        $(this).removeClass('like-comment');
        $(this).addClass('liked-comment');
        $('#alerts').html(`<div class="alert alert-success">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Успешно добавлен лайк на комментарий
                        </div>
                    </div><br>`);
    })
    $(document).on('click', '.liked-comment', function(){
        if($('#dislikecom' + $(this).data('comment')).hasClass('disliked-comment')){
            $('#dislikecom' + $(this).data('comment')).removeClass('disliked-comment');
            $('#dislikecom' + $(this).data('comment')).addClass('dislike-comment');
        }
        $.post('extra/comments/like.php', {like:$(this).data('comment')}, function(){});
        $(this).removeClass('liked-comment');
        $(this).addClass('like-comment');
        $('#alerts').html(`<div class="alert alert-success">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Успешно убран лайк с комментария
                        </div>
                    </div><br>`);
    })
    $(document).on('click', '.dislike-comment', function(){
        if($('#likecom' + $(this).data('comment')).hasClass('liked-comment')){
            $('#likecom' + $(this).data('comment')).removeClass('liked-comment');
            $('#likecom' + $(this).data('comment')).addClass('like-comment');
        }
        $.post('extra/comments/like.php', {dislike:$(this).data('comment')}, function(){});
        $(this).removeClass('dislike-comment');
        $(this).addClass('disliked-comment');
        $('#alerts').html(`<div class="alert alert-success">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Успешно добавлен дизлайк на комментарий
                        </div>
                    </div><br>`);
    })
    $(document).on('click', '.disliked-comment', function(){
        if($('#likecom' + $(this).data('comment')).hasClass('liked-comment')){
            $('#likecom' + $(this).data('comment')).removeClass('liked-comment');
            $('#likecom' + $(this).data('comment')).addClass('like-comment');
        }
        $.post('extra/comments/like.php', {dislike:$(this).data('comment')}, function(){});
        $(this).removeClass('disliked-comment');
        $(this).addClass('dislike-comment');
        $('#alerts').html(`<div class="alert alert-success">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Успешно убран дизлайк с комментария
                        </div>
                    </div><br>`);
    })
    $(document).on('click', '.global--subscribe', function(){
        if(!$(this).hasClass('global--subscribed')){
            $(this).addClass('global--subscribed');
            $(this).removeClass('global--subscribe');
            counter = $('.watch-page-subs-counter').text();
            counter++;
            $('.watch-page-subs-counter').html(counter);
            $.post('/extra/user/ajax/subscribe.php', {subscribe: $(this).data('user')}, function(data){
                if(data == '"error"'){
                    $(this).removeClass('global--subscribed');
                    $(this).addClass('global--subscribe');
                    $('body').html('');
                    location.href = '/login';
                }
            })
        }
    })
    $(document).on('click', '.close_popup', function(){
        $('.popup').remove();
    })
    $(document).on('click', '.add-wh-vid', function(){
        $('#popups').html('<div class="popup"><div class="popup-container"><div class="popup-header">Добавить видео в посмотреть позже<div class="close_popup">✕</div></div><div class="popup-content"><form method="POST"><span>URL видео</span><br><input type="text" name="url" class="input"><div class="mt-display-flex"><input type="submit" name="add" class="mt-uix-button mt-button-primary mt-margin-left" value="Добавить"></div></form></div></div></div>');
    })
    $(document).on('click', '.addtopl', function(){
        $('#popups').html('<div class="popup"><div class="popup-container"><div class="popup-header">Добавить видео в плейлист<div class="close_popup">✕</div></div><div class="popup-content"><form method="POST"><span>URL видео</span><br><input type="text" name="url" class="input"><div class="mt-display-flex"><input type="submit" name="add" class="mt-uix-button mt-button-primary mt-margin-left" value="Добавить"></div></form></div></div></div>');
    })
    $(document).on('click', '.add-pla-vid', function(){
        $('#popups').html('<div class="popup"><div class="popup-container"><div class="popup-header">Добавить плейлист<div class="close_popup">✕</div></div><div class="popup-content"><form method="POST"><span>Название плейлиста</span><br><input type="text" name="title" class="input"><br><br><span>Описание плейлиста</span><br><textarea name="description" class="input"></textarea><div class="mt-display-flex"><input type="submit" name="add" class="mt-uix-button mt-button-primary mt-margin-left" value="Добавить"></div></form></div></div></div>');
    })
    $(document).on('mouseover', '#thumbmail-video', function(){
        $(this).find('.video-time').addClass('hidden');
        $(this).find('.add-to-button-video').removeClass('hidden');
    })
    $(document).on('mouseout', '#thumbmail-video', function(){
        $(this).find('.video-time').removeClass('hidden');
        $(this).find('.add-to-button-video').addClass('hidden');
    })
    $(document).on('click', '.guide-toggle', function(){
        if($('.guide_container').hasClass('hidden')){
            $('.guide_container').removeClass('hidden');
            $(this).addClass('guide-toggle-clicked');
        }else{
            $('.guide_container').addClass('hidden');
            $(this).removeClass('guide-toggle-clicked');
        }
    })
    $(document).on('click', '#change_lang', function(){
        if($('.footer-extra').hasClass('hidden')){
            $('.footer-extra').removeClass('hidden');
            setTimeout(() => $('.footer-extra').html(`
                <div>
                    <h3>Choose your language</h3>
                    <p class="mt-notes">It's only Russian... Sorry about that</p>
                </div>
                <div class="guide-section-separator"></div>
                <div>
                    <a href="/?lang=ru">
                        Russian (Русский)
                    </a>
                </div>
            `), 500);
        }else{
            $('.footer-extra').addClass('hidden');
            $('.footer-extra').html(`<center>
            <img src="img/loading.gif">&nbsp;Загрузка...
        </center>`);
        }
    })
    $(document).on('click', '#help', function(){
        if($('.footer-extra').hasClass('hidden')){
            $('.footer-extra').removeClass('hidden');
            setTimeout(() => $('.footer-extra').html(`
                <div>
                    <h3>Помощь</h3>
                </div>
                <div class="guide-section-separator"></div>
                <div>
                    Коды ошибки:
                    <br>
                    <br>
                    <div>
                        <strong>
                            #f3054U
                        </strong>
                        - Обозначает что некоторые поля для ввода имеют ТОЛЬКО пробелы.
                    </div>
                    <br>
                    <div>
                        <strong>
                            #f3293U
                        </strong>
                        - Обозначает что файл повреждён, не того формата, видео не удалось сконвертировать или не удалось выложить.
                    </div>
                </div>
            `), 500);
        }else{
            $('.footer-extra').addClass('hidden');
            $('.footer-extra').html(`<center>
            <img src="img/loading.gif">&nbsp;Загрузка...
        </center>`);
        }
    })
    $(document).on('click', '.show_more', function(){
        if($('#another_info').hasClass('hidden')){
            $('#another_info').removeClass('hidden');
            $('#info').removeClass('text-description');
            $(this).html('Свернуть');
        }else{
            $('#another_info').addClass('hidden');
            $('#info').addClass('text-description');
            $(this).html('Развернуть');
        }
    })
    $(document).on('click', '.arrow-action-menu', function(){
        open = $(this).data('open');
        if($(open).hasClass('hidden')){
            $(open).removeClass('hidden');
            $(this).addClass('arrow-action-menu-active');
        }else{
            $(open).addClass('hidden');
            $(this).removeClass('arrow-action-menu-active');
        }
    })
    $(document).on('click', '.add-to-button-video', function(){
        if($(this).hasClass('add-to-button-video-success')){
            $(this).removeClass('add-to-button-video-success');
        }else{
            $(this).addClass('add-to-button-video-success');
        }
        $(this).hide(0);
        $(this).fadeIn(500);
        $.post('/extra/video/wh.php', {video:$(this).data('vid')}, function(data){
            if(data == '"error"'){
                if($(this).hasClass('add-to-button-video-success')){
                    $(this).removeClass('add-to-button-video-success');
                }
                $("#alerts").html(`<div class="alert alert-error">
                        <div>
                            <img src="img/p.png">
                        </div>
                        <div class="alert-message">
                            Произошла ошибка при добавлении видео в посмотреть позже. Попробуйте позже.
                        </div>
                    </div><br>`);
            }
        })
    })
    $(document).on('click', '.mt-button-more', function(){
        open = $(this).data('open');
        if($(open).hasClass('hidden')){
            $(open).removeClass('hidden');
        }else{
            $(open).addClass('hidden');
        }
    })
    $(document).on('click', '.like-button', function(){
        if($('.dislike-button').hasClass('disliked-button')){
            $.post('extra/video/like.php', {dislike:$(this).data('vid')}, function(){})
            $('.dislike-button').removeClass('disliked-button');
        }
        if(!$(this).hasClass('liked-button')){
            $.post('extra/video/like.php', {like:$(this).data('vid')}, function(){})
            $(this).addClass('liked-button');
        }else{
            $.post('extra/video/like.php', {like:$(this).data('vid')}, function(){})
            $(this).removeClass('liked-button');
        }
    })
    $(document).on('click', '.dislike-button', function(){
        if($('.like-button').hasClass('liked-button')){
            $.post('extra/video/like.php', {like:$(this).data('vid')}, function(){})
            $('.like-button').removeClass('liked-button');
        }
        if(!$(this).hasClass('disliked-button')){
            $.post('extra/video/like.php', {dislike:$(this).data('vid')}, function(){})
            $(this).addClass('disliked-button');
        }else{
            $.post('extra/video/like.php', {dislike:$(this).data('vid')}, function(){})
            $(this).removeClass('disliked-button');
        }
    })
    $(document).on('click', '#chs', function(){
        if($('.chS').hasClass('hidden')){
            $('.chS').removeClass('hidden');
            $(this).addClass('hidden');
            $('.chS').hide(0);
            $('.chS').show(500);
        }else{
            $('.chS').hide(500);
            $(this).removeClass('hidden');
            setTimeout(() => $('.chS').addClass('hidden'), 550);
        }
    })
    $(document).on('click', '.delete_c', function(){
        $('#comment_' + $(this).data('comment')).fadeOut(400);
        setTimeout(() => $('#comment_' + $(this).data('comment')).remove(), 450);
        $.post('/extra/comments/delete.php', {delete:$(this).data('comment')}, function(){})
    })
    $(document).on('click', '.mt-panel-trigger', function(){
        open = $(this).data('panel');
        $('.mt-panel-trigger').removeClass('mt-panel-trigger-selected');
        $(this).addClass('mt-panel-trigger-selected');
        $('.panel').addClass('hidden');
        $('#panel-' + open).removeClass('hidden');
    })
    $(document).on('click', '#reply', function(){
        $('#addbut').removeClass('hidden');
        $('textarea[name=comment]').focus();
        $('textarea[name=comment]').val('@' + $(this).data('author') + ' ');
    })
    $(document).on('click', '#like-comment', function(){
        
    })
    $(document).on('click', '#alld', function(){
        if($(this).is(':checked')){
            $('.checkbox').prop('checked', true);
        }else{
            $('.checkbox').prop('checked', false);
        }
    })
    $('.checkbox').change(function () {
        if($('.checkbox:checked').length == $('.checkbox').length){
            $('#alld').prop('checked',true);
        }else{
            $('#alld').prop('checked',false);
        }
    });
    $('#alld').change(function () {
        $('.checkbox').prop('checked',this.checked);
    });
})
function addView(ID){
    $.post('/extra/video/addview.php', {videoID:ID}, function(){})
}
function uploadVid(){
    if(!$('#step_2').hasClass('hidden')){
        setInterval(() => alert('Что-то пошло не так... Пожалуйста, обновите страницу'), 1);
    }else{
        var filename = window.event.target.files[0].name;
        $("h3").html(filename);
        $("#titlev").val(filename);
        $('#step_1').addClass('hidden');
        $('#step_2').removeClass('hidden');
    }
    var bar = $('.progress_bar_filled');
    var percent = $('.progress_bar_percent');
    $('#uploadForm').ajaxForm({
        cache : false,
        beforeSubmit: function() {
        var percentVal = '0%';
        bar.width(percentVal)
        percent.html(percentVal);
        },

        uploadProgress: function(event, position, total, percentComplete) {
        var percentVal = percentComplete + '%';
        bar.width(percentVal)
        percent.html(percentVal);
        },
        
        success: function() {
        var percentVal = '100%';
        bar.width(percentVal)
        percent.html(percentVal);
            $('#loading').remove();
            $('.upload_thump').html('<img width="70" src="img/default.jpg">')
        },

        complete: function(xhr) {
            if(xhr.responseText != 'err'){
                document.getElementById("file_name").innerHTML=xhr.responseText;
            }else{
                $("#upload").remove();
                $("#alerts").html(`<div class="alert alert-error">
                            <div>
                                <img src="img/p.png">
                            </div>
                            <div class="alert-message">
                                Произошла ошибка при загрузке файла. Попробуйте позже. (Error Code - #f2768U)
                            </div>
                        </div>`);
            }
        }
    });
}