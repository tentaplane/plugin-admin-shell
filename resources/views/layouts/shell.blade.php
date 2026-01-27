<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>@yield('title', 'Admin') - TentaPress</title>

        @vite(['plugins/tentapress/admin-shell/resources/css/admin.css', 'plugins/tentapress/admin-shell/resources/js/admin.js'])
    </head>
    <body class="bg-[#f0f0f1] text-[#1d2327]">
        <div class="min-h-screen">
            @include('tentapress-admin::partials.sidebar')

            <div class="ml-64 flex min-h-screen flex-1 flex-col">
                @include('tentapress-admin::partials.topbar')

                <main class="flex-1 p-6">
                    <div class="tp-wrap">
                        @include('tentapress-admin::partials.notices')

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
