<!-- Side Navbar -->
<nav class="side-navbar">
  <div class="side-navbar-wrapper">
    <div class="sidenav-header d-flex align-items-center justify-content-center">
      <div class="sidenav-header-inner text-center"><img src="{{ asset('dashboard/img/avatar-1.jpg') }}" alt="person" class="img-fluid rounded-circle">
        <h2 class="h5 text-uppercase">Anderson Hardy</h2><span class="text-uppercase">Web Developer</span>
      </div>
      <div class="sidenav-header-logo"><a href="index.html" class="brand-small text-center"> <strong>B</strong><strong class="text-primary">D</strong></a></div>
    </div>
    <div class="main-menu">
      <ul id="side-main-menu" class="side-menu list-unstyled">                  
        <li @if(Route::currentRouteName() == 'admin.index')class="active" @endif><a href="{{ route('admin.index') }}"> <i class="fa fa-home"></i><span>首页</span></a></li>
        <li @if(Route::currentRouteName() == 'users.index')class="active" @endif><a href="{{ route('users.index') }}"><i class="fa fa-users"></i><span>用户</span></a></li>
        <li @if(Route::currentRouteName() == 'forums.index')class="active" @endif> <a href="{{ route('forums.index') }}"><i class="fa fa-table"></i><span>版块</span></a></li>
        <li> <a href="tables.html"> <i class="fa fa-pencil"></i><span>话题</span></a></li>
        <li> <a href="login.html"> <i class="icon-interface-windows"></i><span>Login page</span></a></li>
        <li> <a href="#"> <i class="icon-mail"></i><span>Demo</span><div class="badge badge-warning">6 New</div></a></li>
      </ul>
    </div>
    <div class="admin-menu">
      <ul id="side-admin-menu" class="side-menu list-unstyled"> 
        <li> <a href="#pages-nav-list" data-toggle="collapse" aria-expanded="false"><i class="icon-interface-windows"></i><span>Dropdown</span>
            <div class="arrow pull-right"><i class="fa fa-angle-down"></i></div></a>
          <ul id="pages-nav-list" class="collapse list-unstyled">
            <li> <a href="#">Page 1</a></li>
            <li> <a href="#">Page 2</a></li>
            <li> <a href="#">Page 3</a></li>
            <li> <a href="#">Page 4</a></li>
          </ul>
        </li>
        <li> <a href="#"> <i class="icon-screen"> </i><span>Demo</span></a></li>
        <li> <a href="#"> <i class="icon-flask"> </i><span>Demo</span>
            <div class="badge badge-info">Special</div></a></li>
        <li> <a href=""> <i class="icon-flask"> </i><span>Demo</span></a></li>
        <li> <a href=""> <i class="icon-picture"> </i><span>Demo</span></a></li>
      </ul>
    </div>
  </div>
</nav>