<div class="row post p-1">

    <div class="col-3 bg-light text-center">
        <a href="<?= ROOT ?>/profile/<?= $post->user->id ?>">
            <img class="profile-image rounded-circle m-1" src="<?= get_image($post->user->image ?? '') ?>" alt="" style="width: 80px; height: 80px; object-fit: cover;">
            <h5><?= esc($post->user->username ?? 'Unknown') ?></h5>
        </a>
    </div>

    <div class="col-9 text-start">
        <div class="muted"><?= get_date($post->date) ?></div>

        <p><?= esc($post->post) ?></p>

        <?php if (!empty($post->image)) : ?>
            <a href="<?= ROOT ?>/post/<?= $post->id ?>">
                <img class=" my-1" src="<?= get_image($post->image) ?>" alt="" style="width: 100%;">
            </a>
        <?php endif; ?>

        <div>
            <?php if (user('id') == $post->user_id) : ?>
                <a href="<?= ROOT ?>/post/edit/<?= $post->id ?>">
                    <button class="btn-sm m-1 btn btn-warning">Edit</button>
                </a>
                <a href="<?= ROOT ?>/post/delete/<?= $post->id ?>">
                    <button class="btn-sm m-1 btn btn-danger">Delete</button>
                </a>
            <?php endif; ?>
        </div>

    </div>
    <hr>
    <h5>Comments:</h5>
    <!-- comment area; зона комментарий -->
    <div>
        <form method="post" onsubmit="submit_comment(event)">
            <div class="bg-secondary p-2">
                <textarea id="comment-input" rows="4" class="form-control" placeholder="Type a comment here"></textarea>

                <input type="hidden" id="post_id" value="<?= $post->id ?>">

                <label>
                    <i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
                    <input id="comment-image-input" onchange="display_comment_image(this.files[0])" type="file" class="d-none" name="">
                </label>

                <button class="btn btn-warning mt-1 float-end">Comment</button>

                <div class="text-center d-none">
                    <img class="comment-image m-1" src="" style="width: 100px; height: 100px; object-fit: cover;">
                </div>

                <div class="clearfix"></div>

            </div>
        </form>

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

        <div class="comment-prog progress d-none">
            <div class="progress-bar" style="width: 0%">0%</div>
        </div>

    </div>
    <!-- end comment area; конец зоны комментарий -->

    <div class="my-3">

        <?php if (!empty($comments)) : ?>
            <?php foreach ($comments as $comment) : ?>
                <?php $this->view('comment-small', ['comment' => $comment]) ?>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
    <hr>
</div>
<br>

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
        obj.post_id = e.currentTarget.querySelector("#post_id").value;
        /** берутся из классов html разметки */
        obj.data_type = "create-comment";
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
        // console.log(result);
        let obj = JSON.parse(result);

        /** предупреждение */
        alert(obj.message);
        /** обновление страницы */
        window.location.reload();
    }
</script>