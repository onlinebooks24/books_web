<nav class="navbar navbar-inverse">
  <div class="container-fluid">

    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="{{ route('admin.index') }}">Dashboard</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li {{ Request::is('category') ? 'class=active' : ''}}><a href="{{ route('admin_category.index') }}">Category</a></li>
        <li {{ Request::is('articles') ? 'class=active' : ''}}><a href="{{ route('admin_articles.index') }}">Articles</a></li>
        <li {{ Request::is('auto') ? 'class=active' : ''}}><a href="{{ route('admin_auto_articles.index') }}">Auto Articles</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">  
        <li class="dropdown" style="text-transform: capitalize;">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                 document.getElementById('logout-form').submit();">
                        Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>