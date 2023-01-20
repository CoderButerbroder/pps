<!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Антикафе">
    <meta name="author" content="Иванов Дмитрий">
    <title>Антикафе</title>
    <script src="/assets/jquery-3.6.3.min.js"></script>

    <link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      .b-example-divider {
        height: 3rem;
        background-color: rgba(0, 0, 0, .1);
        border: solid rgba(0, 0, 0, .15);
        border-width: 1px 0;
        box-shadow: inset 0 .5em 1.5em rgba(0, 0, 0, .1), inset 0 .125em .5em rgba(0, 0, 0, .15);
      }

      .b-example-vr {
        flex-shrink: 0;
        width: 1.5rem;
        height: 100vh;
      }

      .bi {
        vertical-align: -.125em;
        fill: currentColor;
      }

      .nav-scroller {
        position: relative;
        z-index: 2;
        height: 2.75rem;
        overflow-y: hidden;
      }

      .nav-scroller .nav {
        display: flex;
        flex-wrap: nowrap;
        padding-bottom: 1rem;
        margin-top: -1px;
        overflow-x: auto;
        text-align: center;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
      }

      html,
body {
  height: 100%;
}

body {
  display: flex;
  align-items: center;
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #f5f5f5;
}

.form-signin {
  max-width: 800px;
  padding: 15px;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
    </style>

  </head>
  <body class="text-center">

<main class="form-signin w-100 m-auto">
  <form id="send_phone">
    <h1 class="h1 mb-3 fw-normal">Антикафе</h1>
    <h class="h3 mb-3 fw-normal">Регистарция</h>
<div class="input-group pb-3">
  <span class="input-group-text">+7(</span>
  <input type="number" min="0" max="9" name="q1" class="form-control">
  <input type="number" min="0" max="9" name="q2" class="form-control">
  <input type="number" min="0" max="9" name="q3" class="form-control">
    <span class="input-group-text">)</span>
      <input type="number" min="0" max="9" name="q4" class="form-control">
  <input type="number" min="0" max="9" name="q5" class="form-control">
  <input type="number" min="0" max="9" name="q6" class="form-control">
    <span class="input-group-text">-</span>
      <input type="number" min="0" max="9" name="q7" class="form-control">
  <input type="number" min="0" max="9" name="q8" class="form-control">
    <span class="input-group-text">-</span>
  <input type="number" min="0" max="9" name="q9" class="form-control">
  <input type="number" min="0" max="9" name="q10" class="form-control">
</div>


    <button class="w-50 btn btn-lg btn-primary" id="btn_send_phone" type="submit">Регистрация</button
  </form>
</main>

<script type="text/javascript">

     $(function() {
         $('#send_phone').on('submit', function () {
             event.preventDefault();
             $("#btn_send_phone").attr('disabled', 'disabled');
             var type = $("#btn_send_phone").val();
             $.ajax({
                 type: 'POST',
                 url: '/actions/reg_phone.php',
                 data: $('#send_phone').serialize(),
                 dataType: 'json',
                 success: function (result) {
                     if (result.response) {
                             window.location.href = 'https://ivanovpps.full-data.ru/sms_code.php';
                     } else {
                         alert(result.description);
                         $("#btn_send_phone").removeAttr('disabled');
                     }
                 },
                 error: function (jqXHR, textStatus) {
                     alert('Не удалось подключиться');
                     $("#btn_send_phone").removeAttr('disabled');
                 }
             });
         });
     })

</script>

  </body>
</html>
