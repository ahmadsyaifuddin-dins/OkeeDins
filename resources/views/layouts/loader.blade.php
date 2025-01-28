{{-- <link rel="stylesheet" href="{{ asset('css/loader.css') }}"> --}}

<style>
    .spinnerContainer {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #fff;
        z-index: 9999;
        justify-content: center;
    }

    .loader {
        width: fit-content;
        font-weight: bold;
        font-family: monospace;
        white-space: pre;
        font-size: 30px;
        line-height: 1.2em;
        height: 1.2em;
        overflow: hidden;
    }

    .loader:before {
        content: "Loading...\A⌰oading...\A⌰⍜ading...\A⌰⍜⏃ding...\A⌰⍜⏃⎅ing...\A⌰⍜⏃⎅⟟ng...\A⌰⍜⏃⎅⟟⋏g...\A⌰⍜⏃⎅⟟⋏☌...\A⌰⍜⏃⎅⟟⋏☌⟒..\A⌰⍜⏃⎅⟟⋏☌⟒⏁.\A⌰⍜⏃⎅⟟⋏☌⟒⏁⋔";
        white-space: pre;
        display: inline-block;
        animation: l39 1s infinite steps(11) alternate;
    }

    @keyframes l39 {
        100% {
            transform: translateY(-100%)
        }
    }
</style>
<div class="spinnerContainer" id="loading">
    <div class="loader"></div>
</div>
