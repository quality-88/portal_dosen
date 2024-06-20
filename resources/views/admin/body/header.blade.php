<nav class="navbar">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <ul class="navbar-nav">
            
                <div class="dropdown-menu p-0" aria-labelledby="appsDropdown">

                    
                </div>
            </li>

            <li class="nav-item dropdown">
                <li class="nav-item">
  <a href="{{ Auth::check() ? route('logout') : '#' }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
    <i class="link-icon" data-feather="log-out"></i>
    <span class="link-title">Logout</span>
  </a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
  </form>
</li>

                <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
                    <div class="d-flex flex-column align-items-center border-bottom px-5 py-3">
                        
                    </div>
    
                </div>
            </li>
        </ul>
    </div>
</nav>