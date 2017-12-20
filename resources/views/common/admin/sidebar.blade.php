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
        <li @if(Route::currentRouteName() == 'user.index')class="active" @endif><a href="{{ route('user.index') }}"><i class="fa fa-user"></i><span>用户</span></a></li>
        <li @if(Route::currentRouteName() == 'forum.index')class="active" @endif> <a href="{{ route('forum.index') }}"><i class="fa fa-table"></i><span>版块</span></a></li>
        <li @if(Route::currentRouteName() == 'topic.index')class="active" @endif> <a href="{{ route('topic.index') }}"> <i class="fa fa-pencil"></i><span>话题</span></a></li>
        <li @if(Request::routeIs('comment.index'))class="active" @endif> <a href="{{ route('comment.index') }}"> <i class="fa fa-comment"></i><span>回复</span></a></li>
        <li @if(Request::routeIs('attachment.index'))class="active" @endif> <a href="{{ route('attachment.index') }}"> <i class="fa fa-file"></i><span>附件</span></a></li>
        @can('view_huisuo_list')
        <li @if(Request::routeIs('huisuo.index'))class="active" @endif> <a href="{{ route('huisuo.index') }}"> <i class="fa fa-h-square"></i><span>HS</span></a></li>
        @endcan
        @can('view_jishi_list')
        <li @if(Request::routeIs('jishi.index'))class="active" @endif> <a href="{{ route('jishi.index') }}"> <i class="fa fa-female"></i><span>JS</span></a></li>
        @endcan
        @can('view_role_list')
        <li @if(Request::routeIs('role.index'))class="active" @endif> <a href="{{ route('role.index') }}"> <i class="fa fa-users"></i><span>用户组</span></a></li>
        @endcan
        @can('view_permission_list')
        <li @if(Request::routeIs('permission.index'))class="active" @endif> <a href="{{ route('permission.index') }}"> <i class="fa fa-address-book"></i><span>权限</span></a></li>
        @endcan
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