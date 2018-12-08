<header>
    <div class="top-nav container">
        @guest
            <div class="top-nav-left">
                <div class="logo"><a href="/">Ecommerce</a></div>
            </div>
            @else
            <div class="top-nav-left">
                <div class="logo"><a href="/">Ecommerce</a></div>
                @if ((!request()->is('guestcheckout')|| !request()->is('checkout')))
                    {{ menu('main', 'partials.menus.main') }}
                @endif
            </div>
            <div class="top-nav-right">
                @if ((!request()->is('guestcheckout')|| !request()->is('checkout') ))
                    @include('partials.menus.main-right')
                @endif
            </div>
            @endguest

    </div> <!-- end top-nav -->
</header>