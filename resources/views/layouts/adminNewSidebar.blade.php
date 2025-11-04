<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('asset/js/form-validation.js') }}"></script>
<script src="{{ asset('asset/js/progress-uploader.js') }}"></script>
<nav class="sidebar">
    <?php
    $user = Auth::user();
    $role = App\Models\Role::where('id', $user->role)->get()->first();
    ?>
    <div class="sidebar-header">
        <a href="{{ asset('/home') }}" class="sidebar-brand">
            {{ $role->role_name }}
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div class="sidebar-body">
        <ul class="nav">
            <?php
            $roles = explode(',', $role->menu_ids);
            $menus = App\Models\Menu::whereHas('type', function ($query) {
                $query->where('type', 'SideBar');
            })
                ->whereHas('category', function ($query) {
                    $query->where('category', 'Admin');
                })
                ->where('menu_id', '=', 0)
                ->get();
            ?>
            <li class="nav-item nav-category">{{ $role->role_name }} Content</li>
            @foreach ($menus as $menu)
                @if (in_array($menu->id, $roles))
                    <?php
                    $subMenus = App\Models\Menu::where('menu_id', $menu->id)->get();
                    $men = explode('/', $menu->menu_link);
                    $req = explode('/', request()->path());
                    
                    // Icon mappings for FontAwesome
                    $iconMappings = [
                        'Dashboard' => 'fa fa-box',
                        'Images' => 'fa fa-image',
                        'Videos' => 'fa fa-video',
                        'Reel Videos' => 'fa fa-video',
                        'User' => 'fa fa-user',
                        'Category' => 'fa-solid fa-list',
                        'Post' => 'fa-solid fa-newspaper',
                        'State' => 'fa-solid fa-location-dot',
                        'District' => 'fa-solid fa-location-dot',
                        'Web Story' => 'fa fa-photo-film',
                        'Rashiphal' => 'fa-solid fa-feather',
                        'All Users List' => 'fa-solid fa-users',
                        'Banner Sequence Control' => 'fa-solid fa-image',
                        'State Sequence Control' => 'fa-solid fa-map',
                        'Change Password' => 'fa-solid fa-key',
                        'File' => 'fa-solid fa-file',
                    ];
                    
                    $iconClasses = $iconMappings[$menu->menu_name] ?? 'fa fa-bars';
                    $collapseId = 'submenu-' . $menu->id;
                    
                    // Check if any submenu is active
                    $isActive = false;
                    foreach ($subMenus as $subMenu) {
                        $subLink = trim($subMenu->menu_link, '/');
                        if (request()->is($subLink) || request()->is($subLink . '/*')) {
                            $isActive = true;
                            break;
                        }
                    }
                    
                    $subMenuReplacements = [
                        'Post List' => 'Article List',
                        'Post Add' => 'Article Add',
                        'Post Archive' => 'Article Archive',
                    ];
                    ?>

                    <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
                        <a href="{{ $subMenus->isEmpty() ? asset($menu->menu_link) : url('/') }}?t={{ time() }}"
                            class="nav-link parent-menu" data-menu-toggle="{{ $collapseId }}"
                            @if ($subMenus->isNotEmpty()) data-bs-toggle="collapse" 
                          data-bs-target="#{{ $collapseId }}" 
                          aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
                          role="button" @endif>

                            <!-- FontAwesome icon -->
                            <i class="{{ $iconClasses }} link-icon"></i>
                            <span
                                class="link-title">{{ $menu->menu_name === 'Post' ? 'Article' : $menu->menu_name }}</span>
                            @if ($subMenus->isNotEmpty())
                                <i class="link-arrow" data-feather="chevron-down"></i>
                            @endif
                        </a>
                        @if ($subMenus->isNotEmpty())
                            <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                                <ul class="nav sub-menu">
                                    @foreach ($subMenus as $subMenu)
                                        @if (in_array($subMenu->id, $roles))
                                            <li class="nav-item">
                                                @php
                                                    $link = asset($subMenu->menu_link);
                                                    $separator = Str::contains($link, '?') ? '&' : '?';
                                                @endphp

                                                <a href="{{ $link }}{{ $separator }}t={{ time() }}"
                                                    class="nav-link {{ request()->is(trim($subMenu->menu_link, '/')) ? 'active' : '' }}">
                                                    {{ $subMenuReplacements[$subMenu->menu_name] ?? $subMenu->menu_name }}
                                                </a>

                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </li>
                @endif
            @endforeach
            </li>
        </ul>

        <?php /* ?>
        <?php
        $sauser = Auth::user();
        $sarole = App\Models\Role::where('role_name', 'Super Admin')->first();
        ?>

        <ul class="nav">
            <?php
            $saroles = explode(',', $sarole->menu_ids);
            $samenus = App\Models\Menu::whereHas('type', function ($query) {
                $query->where('type', 'SideBar');
            })
                ->whereHas('category', function ($query) {
                    $query->where('category', 'Admin');
                })
                ->where('menu_id', '=', 0)
                ->get();
            ?>
            @if (Auth::user()->role == 4)
                <li class="nav-item nav-category">{{ $sarole->role_name }} Content</li>
                @foreach ($samenus as $samenu)
                    @if (in_array($samenu->id, $saroles))
                        <?php
                        $saSubMenus = App\Models\Menu::where('menu_id', $samenu->id)->get();
                        $samen = explode('/', $samenu->menu_link);
                        $sareq = explode('/', request()->path());
                        
                        $collapseId = 'submenu-' . $samenu->id;
                        
                        // Check if any submenu is active
                        $isActive = false;
                        foreach ($saSubMenus as $saSubMenu) {
                            if (request()->is(trim($saSubMenu->menu_link, '/'))) {
                                $isActive = true;
                                break;
                            }
                        }
                        
                        ?>

                        <li class="nav-item {{ $isActive ? 'menu-open' : '' }}">
                            <a href="{{ $saSubMenus->isEmpty() ? asset($samenu->menu_link) : url('/') }}"
                                class="nav-link parent-menu" data-menu-toggle="{{ $collapseId }}"
                                @if ($saSubMenus->isNotEmpty()) data-bs-toggle="collapse" 
                          data-bs-target="#{{ $collapseId }}" 
                          aria-expanded="{{ $isActive ? 'true' : 'false' }}" 
                          role="button" @endif>

                                <!-- FontAwesome icon -->
                                <i class="fa fa-user link-icon"></i>
                                <span class="link-title">{{ $samenu->menu_name }}</span>
                                @if ($saSubMenus->isNotEmpty())
                                    <i class="link-arrow" data-feather="chevron-down"></i>
                                @endif
                            </a>
                            @if ($saSubMenus->isNotEmpty())
                                <div class="collapse {{ $isActive ? 'show' : '' }}" id="{{ $collapseId }}">
                                    <ul class="nav sub-menu">
                                        @foreach ($saSubMenus as $saSubMenu)
                                            @if (in_array($saSubMenu->id, $saroles))
                                                <li class="nav-item">
                                                    <a href="{{ asset($saSubMenu->menu_link) }}"
                                                        class="nav-link {{ request()->is(trim($saSubMenu->menu_link, '/')) ? 'active' : '' }}">
                                                        {{ $saSubMenu->menu_name }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </li>
                    @endif
                @endforeach
                </li>
            @endif
        </ul>
        <?php */ ?>
    </div>
</nav>
<!-- <nav class="settings-sidebar">
  <div class="sidebar-body">
    <a href="#" class="settings-sidebar-toggler">
      <i data-feather="settings"></i>
    </a>
    <h6 class="text-muted mb-2">Sidebar:</h6>
    <div class="mb-3 pb-3 border-bottom">
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarLight" value="sidebar-light" checked>
          Light
        </label>
      </div>
      <div class="form-check form-check-inline">
        <label class="form-check-label">
          <input type="radio" class="form-check-input" name="sidebarThemeSettings" id="sidebarDark" value="sidebar-dark">
          Dark
        </label>
      </div>
    </div>
    <div class="theme-wrapper">
      <h6 class="text-muted mb-2">Light Version:</h6>
      <a class="theme-item" href="{{ asset('/home') }}">
        <img src="{{ url('asset/new_admin/assets/images/screenshots/light.jpg') }}" alt="light version">
      </a>
      <h6 class="text-muted mb-2">Dark Version:</h6>
      <a class="theme-item" href="{{ asset('/home') }}">
        <img src="{{ url('asset/new_admin/assets/images/screenshots/dark.jpg') }}" alt="dark version">
      </a>
    </div>
  </div>
</nav> -->
<x-delete-modal />
<script>
    let deleteTarget = null;

    function openDeleteModal(link, customMessage = null) {
        deleteTarget = link;
        const modal = document.getElementById('deleteModal');
        const messageElement = modal.querySelector('p');

        if (customMessage && messageElement) {
            messageElement.textContent = customMessage;
        }

        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }


    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    function confirmDelete() {
        if (deleteTarget) {
            window.location.href = deleteTarget.href;
        }
    }
</script>

<script>
    $(document).ready(function () {
        ProgressUploader.init();
    });
</script>
