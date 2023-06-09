<?php $this->view('header') ?>

<div class="p-2 col-md-6 shadow mx-auto border rounded">

    <div class="text-center">

        <span>
            <img class="profile-image rounded-circle m-4" src="<?= get_image($row->image) ?>" style="width: 200px; height: 200px; object-fit: cover;">

            <?php if (user('id') == $row->id) : ?>
                <label>
                    <i style="position: absolute; cursor: pointer;" class="h1 text-primary bi bi-image"></i>
                    <input onchange="display_image(this.files[0])" type="file" class="d-none" name="">
                </label>
            <?php endif; ?>

        </span>

        <div class="profile-image-prog progress d-none">
            <div class="progress-bar" style="width: 0%">0%</div>
        </div>

        <h3><?= esc($row->username) ?></h3>

        <script>
            function display_image(file) {
                /** разрешенные формы изображений */
                let allowed = ['jpg', 'jpeg', 'png', 'webp'];
                // расширение(у файла есть название->разделить точкой->взять последний элемент)
                let ext = file.name.split(".").pop();

                //если форма(файл-изображение) не содержит название(файл-изображение) в нижнем регистре
                if (!allowed.includes(ext.toLowerCase())) {
                    alert('Only files of this type allowed: ' + allowed.toString(", "));
                    return;
                }

                document.querySelector(".profile-image").src = URL.createObjectURL(file);
                change_image(file);
            }

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

    <!-- post area; почтовая зона -->
    <?php if (user('id') == $row->id) : ?>
        <div>
            <form method="post" onsubmit="submit_post(event)">
                <div class="bg-secondary p-2">
                    <textarea id="post-input" rows="4" class="form-control" placeholder="Whats on your mind?"></textarea>

                    <label>
                        <i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
                        <input id="post-image-input" onchange="display_post_image(this.files[0])" type="file" class="d-none" name="">
                    </label>

                    <button class="btn btn-warning mt-1 float-end">Post</button>

                    <div class="text-center d-none">
                        <img class="post-image m-1" src="" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>

                    <div class="clearfix"></div>

                </div>
            </form>

            <div class="post-prog progress d-none">
                <div class="progress-bar" style="width: 0%">0%</div>
            </div>

        </div>
    <?php endif; ?>
    <!-- end post area; конец почтовой зоны -->

    <div class="my-3">

        <?php if (!empty($posts)) : ?>
            <?php foreach ($posts as $post) : ?>
                <?php $this->view('post-small', ['post' => $post]) ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- кнопки пагинации -->
        <?php $pager->display() ?>

    </div>
</div>

<script>
    /** переменная сохраненных изображений в постах */
    var post_image_added = false;

    /** функция изменения изображения */
    function change_image(file) {
        var obj = {};
        /** в принципе создаю ключи-значения */
        obj.image = file;
        /** берутся из классов html разметки */
        obj.data_type = "profile-image";
        /** даст текущий id юзера */
        obj.id = "<?= user('id') ?>";
        /** берутся из классов html разметки */
        obj.progressbar = 'profile-image-prog';

        send_data(obj);
    }

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
        /** берутся из классов html разметки */
        obj.data_type = "create-post";
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

        /** если тип данных объекта такой */
        if (obj.data_type == "profile-image") {
            alert(obj.message);
            /** обновление страницы */
            window.location.reload();
        } else {
            if (obj.data_type == "create-post") {
                alert(obj.message);
                /** обновление страницы */
                window.location.reload();
            }
        }
    }
</script>

<?php $this->view('footer') ?>