@php
    $user = Auth::user();
@endphp

<nav class="navbar navbar-default">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" style="width:200px;" href="/home">
                <img src="/img/logo.png" style="width:30px; margin-top:-5px; float:left;">
                <span style="float:left;">Coldreader</span>
            </a>
          </div>
      
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @auth  
            <ul class="nav navbar-nav">
              <li><a href="/codex">Codex</a></li>
              <li><a href="/help">Help</a></li>
              
              <li class="navbar-form">@include('search.search_form')</li>
              
            </ul>

            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {{ $user->name }} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                  <li><a href="#"><i class="fa fa-fw fa-btn fa-cog"></i>Preferences</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="/logout#"><i class="fa fa-fw fa-btn fa-sign-out"></i>Logout</a></li>
                </ul>
              </li>
            </ul>
            @endauth


          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>