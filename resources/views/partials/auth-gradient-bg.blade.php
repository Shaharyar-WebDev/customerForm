@if ($backgroudType === 'gradient')
    <div class="bg-blob-outer" aria-hidden="true">
        <div class="bg-blob-inner"
            style="clip-path: polygon(
        74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%,
        80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%,
        47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%,
        17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%
    )">
        </div>
    </div>
@endif
@if ($backgroudType === 'pattern')
    <style>
        .fi-main,
        .fi-simple-main {
            &.fi-width-lg {
                opacity: 100;
            }

            &.fi-width-lg:hover {
                opacity: 100;
            }
        }
    </style>
    <div class="bg-blob-img-outer" aria-hidden="true">
        <div class="bg-blob-img" alt="background image"></div>
    </div>
@endif
<style>
    img.fi-logo {
        width: 5rem;
    }
</style>

{{-- file:///D:/Downloads/orange_glow%20(1).png --}}
{{-- https://app.autocalls.ai/images/header.png --}}
{{-- https://gradienty.codes/_next/image?url=%2F_next%2Fstatic%2Fmedia%2Fbackdrop.ad6be646.jpg&w=1920&q=75 --}}
{{-- https://posly.getstocky.com/images/photo-wide-4.jpg --}}
