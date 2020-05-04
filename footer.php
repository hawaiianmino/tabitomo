<footer id="footer">
Copyright&ensp;<a href="http://50storm.info/">tabitomo</a>.&ensp;All Rights Reserved.
</footer>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.0.min.js" integrity="sha256-xNzN2a4ltkB44Mc/Jz3pT4iU1cmeR0FkXs4pru/JxaQ=" crossorigin="anonymous"></script>
<script>
    $(function(){

        //メッセージ表示
        var $jsShowMsg = $('#js-show-msg');
        var msg = $jsShowMsg.text();
        if(msg.replace(/^[\s　]+|[\s　]+$/g,"").length){
            $jsShowMsg.slideToggle('slow');
            setTimeout(() => {
                $jsShowMsg.slideToggle('slow');
            }, 5000);
        }

        //画像ライブプレビュー
        var $dropArea = $('.area-drop');
        var $fileInput = $('.input-file');
        $dropArea.on('dragover',function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border','3px #ccc dashed');
        });
        $dropArea.on('dragleave',function(e){
            e.stopPropagation();
            e.preventDefault();
            $(this).css('border','none');
        });
        $fileInput.on('change',function(e){
            $dropArea.css('border','none');
            var file = this.files[0],//ファイル配列にファイルが入っている
            $img = $(this).siblings('.prev-img'),//sblingメソッドで兄弟IMGを取得
            fileReader = new FileReader();//ファイルを読みこむ

            //読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット
            fileReader.onload = function(event){
                //読み込んだデータをimgに設定
                $img.attr('src',event.target.result).show();
            }

            //画像読み込み
            fileReader.readAsDataURL(file);

        });


    });
</script>