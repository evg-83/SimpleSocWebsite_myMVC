<?php $this->view('header') ?>

<div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

        <?php if (!empty($row)) : ?>
            <form method="post" onsubmit="submit_post(event)">
                <div class="row post p-1">

                    <center>
                        <h5>Edit Post</h5>
                    </center>

                    <div class="row post p-1">

                        <div class="col-3 bg-light text-center">
                            <a href="<?= ROOT ?>/profile/<?= $post->user->id ?>">
                                <img class="profile-image rounded-circle m-1" src="<?= get_image($post->user->image ?? '') ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
                                <h5><?= esc($post->user->username ?? 'Unknown') ?></h5>
                            </a>
                        </div>

                        <div class="col-9 text-start">
                            <div class="muted"><?= get_date($post->date) ?></div>

                            <div>
                                <div class="bg-secondary p-2">
                                    <textarea id="post-input" rows="4" class="form-control" placeholder="Whats on your mind?"><?= $post->post ?></textarea>

                                    <label>
                                        <i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
                                        <input id="post-image-input" onchange="display_post_image(this.files[0])" type="file" class="d-none" name="">
                                    </label>

                                    <button class="btn btn-warning mt-1 float-end">Save</button>

                                    <div class="text-center d-none">
                                        <img class="post-image m-1" src="" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                                <script>
                                    function display_post_image(file) {
                                        /** разрешенные формы изображений */
                                        let allowed = ['jpg', 'jpeg', 'png', 'webp'];
                                        // расширение(у файла есть название->разделить точкой->взять последний элемент)
                                        let ext = file.name.split(".").pop();

                                        //если форма(файл-изображение) не содержит название(файл-изображение) в нижнем регистре
                                        if (!allowed.includes(ext.toLowerCase())) {
                                            alert('Only files of this type allowed: ' + allowed.toString(", "));
                                            post_image_added = false;
                                            return;
                                        }

                                        document.querySelector(".post-image").src = URL.createObjectURL(file);
                                        document.querySelector(".post-image").parentNode.classList.remove("d-none");

                                        post_image_added = true;
                                    }
                                </script>
                            </div>

                            <?php if (!empty($post->image)) : ?>
                                <a href="<?= ROOT ?>/post/<?= $post->id ?>">
                                    <img class=" my-1" src="<?= get_image($post->image) ?>" alt="" style="width: 100%;">
                                </a>
                            <?php endif; ?>

                            <input type="hidden" id="post_id" value="<?= $post->id ?>">

                            <div>
                                <?php if (user('id') == $post->user_id) : ?>
                                    <a href="<?= ROOT ?>/post/edit/<?= $post->id ?>">
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

        <div class="post-prog progress d-none">
            <div class="progress-bar" style="width: 0%">0%</div>
        </div>

    </div>
</div>

<script>
    /** переменная сохраненных изображений в постах */
    var post_image_added = false;

    /** функция размещения поста в профиле */
    function submit_post(e) {
        // будет событие, которое захватим используя (e).
        //предотвращение публикации
        e.preventDefault();


        var obj = {};
        /** если верно, то беру первую картинку из в () */
        if (post_image_added) {
            obj.image = e.currentTarget.querySelector("#post-image-input").files[0];
        }
        /** в общем, ищем внутри формы - это(в скобках) */
        obj.post = e.currentTarget.querySelector("#post-input").value;
        obj.post_id = e.currentTarget.querySelector("#post_id").value;
        /** берутся из классов html разметки */
        obj.data_type = "edit-post";
        /** даст текущий id юзера */
        obj.id = "<?= user('id') ?>";
        /** берутся из классов html разметки */
        obj.progressbar = 'post-prog';

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
            window.location.href = '<?= ROOT ?>/post/<?= $post->id ?? 0 ?>';
        }
    }
</script>

<?php $this->view('footer') ?>