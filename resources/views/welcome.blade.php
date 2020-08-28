<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>saveFav</title>

        <!-- Fonts -->
        <link href="{{asset('./css/app.css')}}" rel="stylesheet">

    </head>
    <body>
          <header>
              <div class="brand">
                  <img src="" alt="" class="logo">
              </div>
              <nav>

              </nav>
          </header>
        <main>
            <passport-clients></passport-clients>
            <passport-authorized-clients></passport-authorized-clients>
            <passport-personal-access-tokens></passport-personal-access-tokens>
        </main>
    </body>
</html>
