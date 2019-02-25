<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('admin.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! env('APP_ENV') !!}
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;

        @if(config('admin.show_version'))
            <strong>LA Version</strong>&nbsp;&nbsp; {{ package_version('encore/laravel-admin') }}

            &nbsp;&nbsp;&nbsp;&nbsp;

            <strong>QA Version</strong>&nbsp;&nbsp; {{ package_version('tiderjian/qs-lara-admin')  ?? "master" }}
        @endif

    </div>
    <!-- Default to the left -->
    <strong>由  <a href="http://www.quansitech.com" target="_blank">全思科技</a>  技术驱动</strong>
</footer>