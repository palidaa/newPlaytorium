<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,height=device-height,initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <style>
    body {
      background: url(images/bg.jpg) no-repeat center center fixed;
      -webkit-background-size: cover;
      -moz-background-size: cover;
      -o-background-size: cover;
      background-size: cover;
    }
  </style>
  <body>
    <div class="jumbotron" style="background-color: rgba(192, 192, 192, 0.3); !important; margin-top: 50vh;">
      <div class="container">
        <div class="row">
          <div class="col-md-4 col-md-offset-4">

            <form action ="{{ route('login') }}" method="post">
              {{ csrf_field() }}

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>
                  @if ($errors->has('email'))
                    <span class="help-block">
                      <strong>{{ $errors->first('email') }}</strong>
                    </span>
                  @endif
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                  @if ($errors->has('password'))
                    <span class="help-block">
                      <strong>{{ $errors->first('password') }}</strong>
                    </span>
                  @endif
              </div>

              <div class="row">
                <div class="col-md-12">
                  @if(count($errors) > 0)
                    @foreach($errors -> all() as $error)
                      <div class="alert alert-danger">
                        {{$error}}
                      </div>
                    @endforeach
                  @endif
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
            <form action="/export">
              <button type="submit" class="btn btn-primary btn-block">EXPORT</button>
            </form>
            <form action="/export2">
              <button type="submit" class="btn btn-primary btn-block">EXPORT2</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
