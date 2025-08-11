<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Login</title>
</head>
<body>
    <section>
        <div class="login_form">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="offset-4 col-md-4">
                                <div class="login_form_container">
                                    <span>Gym</span>
                                    <h2>LOG IN</h2>
                                    
                                    @if(session('error'))
                                        <p style="color: red;">{{ session('error') }}</p>
                                    @endif

                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="form_group mt-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" id="email" name="email" class="form-control" placeholder="Enter your username" required>
                                        </div>
                                        <div class="form_group mt-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                                        </div>
                                        <div class="col-md-12 mt-3 mb-3">
                                            <div class="text-center">
                                                <button  type="submit" class="btn btn-black mt-3" onclick="">LOG IN</button>
                                           </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>