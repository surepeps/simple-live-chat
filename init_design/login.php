<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
        <!-- custom css -->
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body>

        <div class="container">
            <div class="row align-items-center justify-content-center vh-100">
                <div class="col-md-10 mx-auto">

                    <div class="card shadow border">
                        <div class="row g-0">
                            <div class="col-lg-7 col-xl-7 col-xxl-7 d-none d-lg-block d-xl-block d-xxl-block">
                                <img src="assets/images/login-bg.jpg" style="height: 100%;" class="img-fluid rounded-start" alt="...">
                            </div>
                            <div class="col-md-12 col-xl-5 col-xxl-5 col-lg-5 col-sm-12">
                                <div class="card-body d-flex flex-column align-items-center">
                                    <h3 class="card-title">
                                        <a href="#" class="mylogo">Sure<label>Chatter</label></a>
                                        <hr class="bg-danger border-2 border-top border-danger w-50 mx-auto">
                                    </h3>
                                    
                                    <form class="row g-3 mt-2 needs-validation" novalidate>
                                        <div class="col-12">
                                            <input type="email" class="form-control" placeholder="Email" id="validationCustom01" value="" required>
                                            <div class="valid-feedback">
                                            Looks good!
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <input type="password" class="form-control" placeholder="***********" id="validationCustom01" value="" required>
                                            <div class="valid-feedback">
                                            Looks good!
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                            <label class="form-check-label" for="invalidCheck">
                                                Kepp me logged in
                                            </label>
                                            <div class="invalid-feedback">
                                                You must agree before submitting.
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 d-grid">
                                            <button class="btn btn-primary mybtn" type="button">Login</button>
                                        </div>
                                        <div class="col-12 text-center">
                                            <label class="form-check-label" for="invalidCheck">
                                                Don't have an account yet?  <a href="register.php" class="myDislink">Register Now</a>
                                            </label>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </body>
</html>