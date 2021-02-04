@php
    $configData = Helper::applClasses();
@endphp
<div class="main-menu menu-fixed {{($configData['theme'] === 'light') ? "menu-light" : "menu-dark"}} menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item mr-auto"><a class="navbar-brand" href="dashboard-analytics">
                    <div class="brand-logo"></div>
                    <h2 class="brand-text mb-0">Vuexy</h2>
                </a></li>
            
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            {{-- Foreach menu item starts --}}
            @php 
                $roles = [0 =>'theme', 1 => 'adminSidebar', 2 => 'acceptorSidebar', 3 => 'operatorSidebar', 4 => 'customerSidebar'];
                $role = isset(Auth::user()->role) ? Auth::user()->role : 0;
                $sidebar = $roles[$role]; 
                
            @endphp
            @foreach($menuData[0]->$sidebar as $menu)
                @if(isset($menu->navheader))
                    <li class="navigation-header">
                        <span>{{ $menu->navheader }}</span>
                    </li>
                @else
                  {{-- Add Custom Class with nav-item --}}
                  @php
                    $custom_classes = "";
                    if(isset($menu->classlist)) {
                      $custom_classes = $menu->classlist;
                    }
                    $translation = "";
                    if(isset($menu->i18n)){
                        $translation = $menu->i18n;
                    }
                    
                  @endphp
                  <li class="nav-item {{ (request()->is($menu->url)) ? 'active' : '' }} {{ $custom_classes }}">
                        <a href="{{ $menu->url }}">
                            <i class="{{ $menu->icon }}"></i>
                            <span class="menu-title" data-i18n="{{ $translation }}">{{ $menu->name }}</span>
                            @if (isset($menu->badge))
                                <?php $badgeClasses = "badge badge-pill badge-primary float-right" ?>
                                <span class="{{ isset($menu->badgeClass) ? $menu->badgeClass.' test' : $badgeClasses.' notTest' }} ">{{$menu->badge}}</span>
                            @endif
                        </a>
                        @if(isset($menu->submenu))
                            @include('panels/submenu', ['menu' => $menu->submenu])
                        @endif
                    </li>
                @endif
            @endforeach
            {{-- Foreach menu item ends --}}
        </ul>
    </div>
</div>
<!-- END: Main Menu-->
