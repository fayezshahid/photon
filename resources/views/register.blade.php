<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="photon.webp">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>PHOTON</title>
</head>
<body class="bg-light align-items-center" style="background-image: url('background.jpeg'); background-repeat: no-repeat; background-size: cover;">
    <div class="container">
        <div class="row mt-5">
            <div class="col-lg-4 bg-white m-auto rounded-top wrapper">
                <h2 class="text-center pt-3">Signup Now</h2>
                <p class="text-center text-muted lead">It`s free and takes a miniute</p>
                <form action ="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="firstname" placeholder="First Name" value="{{ old('firstname') }}" required>
                        </div>
                        @error('firstname')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                            <input type="text" class="form-control" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required>
                        </div>
                        @error('lastname')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                            <input type="text" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        @error('password')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                        </div>
                        @error('password_confirmation')
                            <div class="text-danger">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Signup Now</button>
                        <p class="text-center text-muted mt-2">When you register by clicking Signup Button, You agree to our 
                        <a href="#">Privacy Policy</a> and <a href="#">Terms and Conditions</a></p>
                        <p class="text-center">
                            Already Have Account ? <a href="{{ route('login') }}">Login Here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</body>
</html>