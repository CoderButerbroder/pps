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
  <form id="send_email">
    <h1 class="h1 mb-3 fw-normal">Верификация email</h1>
    <div class="mb-3">
      <label for="exampleFormControlInput1" class="form-label">Email адрес</label>
      <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
    </div>

    <button class="w-50 btn btn-lg btn-primary" id="btn_send_email" type="submit">Отправить</button
  </form>
</main>

<script type="text/javascript">

     $(function() {
         $('#send_email').on('submit', function () {
             event.preventDefault();
             $("#btn_send_email").attr('disabled', 'disabled');
             var type = $("#btn_send_email").val();
             $.ajax({
                 type: 'POST',
                 url: '/actions/reg_email.php',
                 data: $('#send_email').serialize(),
                 dataType: 'json',
                 success: function (result) {
                     if (result.response) {
                         alert(result.description);
                     } else {
                         alert(result.description);
                         $("#btn_send_email").removeAttr('disabled');
                     }
                 },
                 error: function (jqXHR, textStatus) {
                     alert('Не удалось подключиться');
                     $("#btn_send_email").removeAttr('disabled');
                 }
             });
         });
     })

</script>

  </body>
</html>
