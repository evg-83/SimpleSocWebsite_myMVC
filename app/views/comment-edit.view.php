<?php $this->view('header') ?>

<div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

        <?php if (!empty($comment)) : ?>
            <form method="post" onsubmit="submit_comment(event)">
                <div class="row post p-1">

                    <center>
                        <h5>Edit Comment</h5>
                    </center>

                    <div class="row post p-1">

                        <div class="col-3 bg-light text-center">
                            <a href="<?= ROOT ?>/profile/<?= $comment->user->id ?>">
                                <img class="profile-image rounded-circle m-1" src="<?= get_image($comment->user->image ?? '') ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
                                <h5><?= esc($comment->user->username ?? 'Unknown') ?></h5>
                            </a>
                        </div>

                        <div class="col-9 text-start">
                            <div class="muted"><?= get_date($comment->date) ?></div>

                            <div>
                                <div class="bg-secondary p-2">
                                    <textarea id="comment-input" rows="4" class="form-control" placeholder="Whats on your mind?"><?= $comment->comment ?></textarea>

                                    <label>
                                        <i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
                                        <input id="comment-image-input" onchange="display_comment_image(this.files[0])" type="file" class="d-none" name="">
                                    </label>

                                    <button class="btn btn-warning mt-1 float-end">Save</button>

                                    <div class="text-center d-none">
                                        <img class="comment-image m-1" src="" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                                <script>
                                    function display_comment_image(file) {
                                        /** разрешенные формы изображений */
                                        let allowed = ['jpg', 'jpeg', 'png', 'webp'];
                                        // расширение(у файла есть название->разделить точкой->взять последний элемент)
                                        let ext = file.name.split(".").pop();

                                        //если форма(файл-изображение) не содержит название(файл-изображение) в нижнем регистре
                                        if (!allowed.includes(ext.toLowerCase())) {
                                            alert('Only files of this type allowed: ' + allowed.toString(", "));
                                            comment_image_added = false;
                                            return;
                                        }

                                        document.querySelector(".comment-image").src = URL.createObjectURL(file);
                                        document.querySelector(".comment-image").parentNode.classList.remove("d-none");

                                        comment_image_added = true;
                                    }
                                </script>
                            </div>

                            <?php if (!empty($comment->image)) : ?>
                                <a href="<?= ROOT ?>/comment/<?= $comment->id ?>">
                                    <img class=" my-1" src="<?= get_image($comment->image) ?>" alt="" style="width: 100%;">
                                </a>
                            <?php endif; ?>

                            <input type="hidden" id="comment_id" value="<?= $comment->id ?>">

                            <div>
                                <?php if (user('id') == $comment->user_id) : ?>
                                    <a href="<?= ROOT ?>/home/<?= $comment->id ?>">
                                        <button type="button" class="btn-sm m-1 btn btn-secondary">Back</button>
                                    </a>
                                <?php endif; ?>
                            </div>

                        </div>

                    </div>
                </div>
            </form>

        <?php else : ?>
            <div class="m-1 alert alert-danger text-center">Sorry! That record was not found!</div>
        <?php endif; ?>

        <div class="comment-prog progress d-none">
            <div class="progress-bar" style="width: 0%">0%</div>
        </div>

    </div>
</div>

<script>
    /** переменная сохраненных изображений в постах */
    var comment_image_added = false;

    /** функция размещения поста в профиле */
    function submit_comment(e) {
        // будет событие, которое захватим используя (e).
        //предотвращение публикации
        e.preventDefault();


        var obj = {};
        /** если верно, то беру первую картинку из в () */
        if (comment_image_added) {
            obj.image = e.currentTarget.querySelector("#comment-image-input").files[0];
        }
        /** в общем, ищем внутри формы - это(в скобках) */
        obj.comment = e.currentTarget.querySelector("#comment-input").value;
        obj.comment_id = e.currentTarget.querySelector("#comment_id").value;
        /** берутся из классов html разметки */
        obj.data_type = "edit-comment";
        /** даст текущий id юзера */
        obj.id = "<?= user('id') ?>";
        /** берутся из классов html разметки */
        obj.progressbar = 'comment-prog';

        send_data(obj);
    }

    /** send data function; функция оправки данных */
    function send_data(obj) {
        /** создаю объект(форма(моя имитация формы)) */
        var myform = new FormData();
        var progressbar = null;

        /** проверка связанная с прогрессбаром */
        if (typeof obj.progressbar != 'undefined') {
            progressbar = document.querySelector("." + obj.progressbar);
        }

        for (key in obj) {
            /** добавить вещи в форму(ключ, значение(внутри объекта ключ)) */
            myform.append(key, obj[key]);
        }
        /** объект аякс */
        var ajax = new XMLHttpRequest();
        /** прослушиватель событий: 1й-какое событие измененного состояние слушаем; 2е-функ работающая при получении готового измененного состояния(будет ловит это изм сост) */
        ajax.addEventListener('readystatechange', function(e) {
            /** 4-Операция полностью завершена(состояние); 200-понятно статус ОК */
            if (ajax.readyState == 4 && ajax.status == 200) {
                handle_result(ajax.responseText);
            }
        });

        /** проверка связанная с прогрессбаром */
        if (progressbar) {
            /** предыдущий прогрессбар */
            progressbar.classList.remove("d-none");

            /** прежде чем запустить проверку, установлю прогрессбар на ноль, точка-начала его так сказать */
            progressbar.children[0].style.width = "0%";
            progressbar.children[0].innerHTML = "0%";

            /** проверка прогресса загрузки(при загрузке объекта, проверяется аяксом(прослушивается) прогресс загрузки) */
            ajax.upload.addEventListener('progress', function(e) {
                /** процент равен загружаемые данные делю на всего загруженных и умножаю на 100, чтобы получить процент, и все это округлить */
                let percent = Math.round((e.loaded / e.total) * 100);

                /** а затем меняю эти значения */
                progressbar.children[0].style.width = percent + "%";
                progressbar.children[0].innerHTML = percent + "%";
            });
        }

        /** открываю объект */
        ajax.open('post', '<?= ROOT ?>/ajax', true);
        /** отправляю объект */
        ajax.send(myform);
    }

    /** функция возврата результата */
    function handle_result(result) {
        /** чтобы видеть какой результат будем получать */
        console.log(result);
        /** предупреждение */
        let obj = JSON.parse(result);

        alert(obj.message);

        if (obj.success) {
            /** обновление страницы */
            window.location.href = '<?= ROOT ?>/comment/<?= $comment->id ?? 0 ?>';
        }
    }
</script>

<?php $this->view('footer') ?>