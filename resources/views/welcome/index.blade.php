@extends('layout.app')
@section('content')

    <!-- welcome -->
    <main role="main" class="container-fluid pt-5 ">
        <div class="container-fluid ">
            <div class="row">
                <div class="col">
                    <div class="jumbotron " style="border-radius: 0px ;" >
                        <h1 class="display-4">Hello !</h1>
                        <p class="lead">Bienvenue sur Discord Manager. Le meilleur gestionnaire de serveur Discord pour les administrateurs</p>
                        <hr class="my-4">
                        <p></p>
                        <div class="d-flex  ">
                            <a class="btn btn-primary btn-lg  " href="{{route("login")}}" role="button">go</a>
                        </div>
                    </div>
                </div>
                <div class="col">
                </div>
            </div>
        </div>


    </main>
@endsection
