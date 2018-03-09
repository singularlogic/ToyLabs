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

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/certification') }}">
              <i class="fa fa-certificate"></i>
              <span>Certifications</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/award') }}">
              <i class="fa fa-trophy"></i>
              <span>Awards</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/user') }}">
              <i class="fa fa-users"></i>
              <span>Users</span>
            </a>
          </li>


          <li class="header">VERIFICATION & MODERATION</li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/verify/organization') }}">
              <i class="fa fa-check-square-o"></i>
              <span>Verify Organizations</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/verify/certification') }}">
              <i class="fa fa-certificate"></i>
              <span>Verify Certifications</span>
            </a>
          </li>

          <li>
            <a href="{{ url(config('backpack.base.route_prefix', 'admin').'/verify/comment') }}">
              <i class="fa fa-comments-o"></i>
              <span>Moderate Comments</span>
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
