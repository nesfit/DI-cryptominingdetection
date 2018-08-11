<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Brno University of Technology">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'sMaSheD') }}</title>

    <link type="text/css" rel="stylesheet" href="{{ mix('/css/app.css') }}" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
    <!-- <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css"/>
    <!-- <link rel="stylesheet" href="bootstrap-select/dist/css/bootstrap-select.css" /> -->


    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.css" >
    <!-- <link rel="stylesheet" href="bootstrap-table/src/bootstrap-table.css"> -->




    <!--

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/css/bootstrap-select.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>

    -->

    @yield('head')

</head>

<body style="background-color:#FFFFFF;">
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">sMaSheD</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li {{ (Request::is('pool') ? 'class=active' : '') }}><a href="{{ url('pool')}}">Pools</a></li>
                    <li {{ (Request::is('crypto') ? 'class=active' : '') }}><a href="{{ url('crypto')}}">Cryptos</a></li>
                    <li {{ (Request::is('server') ? 'class=active' : '') }}><a href="{{ url('server')}}">Servers</a></li>
                    <li {{ (Request::is('port') ? 'class=active' : '') }}><a href="{{ url('port')}}">Ports</a></li>
                    <li {{ (Request::is('address') ? 'class=active' : '') }}><a href="{{ url('address')}}">Addresses</a></li>
                    <li {{ (Request::is('miningProp') ? 'class=active' : '') }}><a href="{{ url('miningProp')}}">Mining Properties</a></li>
                </ul>
                <p class="navbar-text">|</p>
                <ul class="nav navbar-nav">
                    <li {{ (Request::is('dashboard') ? 'class=active' : '') }}><a href="{{ url('dashboard')}}">Dashboard</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @if (Auth::guest())
                        <!-- <li><a href="route('register')"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li> -->
                        <li><a href="{{ url('login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                    @else
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                               document.getElementById('logout-form').submit();">
                               <span class="glyphicon glyphicon-log-out"></span> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    @yield('message')

<div class="container" style="margin-top: 60px;">
    <div class="clearfix"></div>
    @yield('content')
    <div class="clearfix"></div>
</div>

    @yield('footer')
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
    <!-- <script src="js/query-3.3.1.min.js"></script> -->

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script> -->
    <!-- <script src="bootstrap/js/bootstrap.min.js"></script> -->
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.6.3/js/bootstrap-select.min.js"></script>
    <!-- <script src="bootstrap-select/dist/js/bootstrap-select.js"></script> -->
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/bootstrap-table.min.js"></script>
    <!-- <script src="bootstrap-table/src/bootstrap-table.js"></script> -->
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.12.1/extensions/filter-control/bootstrap-table-filter-control.min.js"></script>
    <!-- <script src="bootstrap-table/src/extensions/filter-control/bootstrap-table-filter-control.js"></script> -->

</body>
</html>
