@if (!filament()->hasTopNavigation())
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
    </svg>
@else
    <style>
        .fi-topbar-close-collapse-sidebar-btn {
            display: none;
        }
    </style>
@endif
@if (request()->route()->uri === 'dashboard')
    <style>
        /* .fi-section-content {
            padding: 0 !important;
        } */
    </style>
@endif
