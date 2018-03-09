@if (Auth::check())
    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="{{ Auth::user()->image }}" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p>{{ Auth::user()->name }}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">{{ trans('backpack::base.administration') }}</li>
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/dashboard') }}">
              <i class="fa fa-dashboard"></i>
              <span>{{ trans('backpack::base.dashboard') }}</span>
            </a>
          </li>

          <li class="header">VERIFICATION</li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/organizations') }}">
              <i class="fa fa-check-square-o"></i>
              <span>Organizations</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/certifications') }}">
              <i class="fa fa-certificate"></i>
              <span>Certifications</span>
            </a>
          </li>

          <li class="header">MODERATION</li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/comments') }}">
              <i class="fa fa-comments-o"></i>
              <span>Comments</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/users') }}">
              <i class="fa fa-users"></i>
              <span>Users</span>
            </a>
          </li>


          <!-- ======================================= -->
          <li class="header">{{ trans('backpack::base.user') }}</li>
          <li><a href="{{ url(config('backpack.base.route_prefix', 'admin').'/logout') }}"><i class="fa fa-sign-out"></i> <span>{{ trans('backpack::base.logout') }}</span></a></li>
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
