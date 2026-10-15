<div class="intro" id="intro">
    <div class="intro-card">
        <svg class="intro-svg" viewBox="0 0 800 560" role="img"
             aria-label="Logo kode yang bercabang">
            <defs>
                <filter id="introGlow" x="-50%" y="-50%" width="200%" height="200%">
                    <feGaussianBlur stdDeviation="6" result="b"/>
                    <feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
            </defs>

            {{-- Cabang: tergambar sendiri setara efek DrawSVG (gratis, murni CSS) --}}
            <g class="branches" fill="none" stroke="#8db4ee" stroke-width="3" stroke-linecap="round">
                <path id="br-top" class="br b1" pathLength="1" d="M400 300 L400 72"/>
                <path id="br-rt"  class="br b2" pathLength="1" d="M400 300 L662 112"/>
                <path id="br-r"   class="br b3" pathLength="1" d="M400 300 L724 336"/>
                <path id="br-l"   class="br b4" pathLength="1" d="M400 300 L76 336"/>
                <path id="br-lt"  class="br b5" pathLength="1" d="M400 300 L138 112"/>
                <path id="br-b"   class="br b6" pathLength="1" d="M400 300 L400 512"/>
            </g>

            {{-- Titik ujung cabang --}}
            <g class="nodes" fill="#ffffff" filter="url(#introGlow)">
                <circle class="dot d1" cx="400" cy="72"  r="7"/>
                <circle class="dot d2" cx="662" cy="112" r="7"/>
                <circle class="dot d3" cx="724" cy="336" r="7"/>
                <circle class="dot d4" cx="76"  cy="336" r="7"/>
                <circle class="dot d5" cx="138" cy="112" r="7"/>
                <circle class="dot d6" cx="400" cy="512" r="7"/>
            </g>

            {{-- Label tiap cabang --}}
            <g class="labels" text-anchor="middle" font-size="21">
                <text class="lab l1" x="400" y="48">PHP</text>
                <text class="lab l2" x="662" y="88">JavaScript</text>
                <text class="lab l3" x="724" y="370">Supabase</text>
                <text class="lab l4" x="76"  y="370">Laravel</text>
                <text class="lab l5" x="138" y="88">Tailwind</text>
                <text class="lab l6" x="400" y="548">Git</text>
            </g>

            {{-- Titik data yang berjalan di sepanjang cabang (setara MotionPath, gratis) --}}
            <g class="travel" fill="#ffffff" filter="url(#introGlow)">
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.15s"/>
                    <animateMotion dur="1.5s" begin="1.15s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-top"/>
                    </animateMotion>
                </circle>
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.23s"/>
                    <animateMotion dur="1.7s" begin="1.23s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-rt"/>
                    </animateMotion>
                </circle>
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.31s"/>
                    <animateMotion dur="1.6s" begin="1.31s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-r"/>
                    </animateMotion>
                </circle>
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.39s"/>
                    <animateMotion dur="1.7s" begin="1.39s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-l"/>
                    </animateMotion>
                </circle>
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.47s"/>
                    <animateMotion dur="1.5s" begin="1.47s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-lt"/>
                    </animateMotion>
                </circle>
                <circle r="5" opacity="0">
                    <set attributeName="opacity" to="1" begin="1.55s"/>
                    <animateMotion dur="1.8s" begin="1.55s" repeatCount="indefinite" rotate="0">
                        <mpath xlink:href="#br-b"/>
                    </animateMotion>
                </circle>
            </g>

            {{-- Logo kode di tengah, menggantikan batang pohon --}}
            <g class="intro-logo">
                <rect x="344" y="244" width="112" height="112" rx="30"
                      fill="rgba(255,255,255,.10)" stroke="rgba(255,255,255,.75)" stroke-width="2.5"/>
                <text x="400" y="322" text-anchor="middle" font-size="52" font-weight="700"
                      fill="#ffffff" class="code-glyph">&lt;/&gt;</text>
            </g>
        </svg>

        <p class="intro-caption">
            <b>{{ config('portfolio.name') }}</b>
            <span>{{ config('portfolio.role') }}</span>
        </p>

        <button class="intro-skip" id="introSkip" type="button">Lewati</button>
    </div>
</div>

<script>
(function () {
    var intro = document.getElementById('intro');
    if (!intro) return;

    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var seen = false;
    try { seen = sessionStorage.getItem('aan_intro_seen') === '1'; } catch (e) {}

    if (reduce || seen) {
        intro.parentNode && intro.parentNode.removeChild(intro);
        return;
    }

    try { sessionStorage.setItem('aan_intro_seen', '1'); } catch (e) {}
    document.body.classList.add('intro-lock');

    var done = false;
    function finish() {
        if (done) return;
        done = true;
        intro.classList.add('intro--out');
        document.body.classList.remove('intro-lock');
        setTimeout(function () {
            if (intro.parentNode) intro.parentNode.removeChild(intro);
        }, 650);
    }

    var skip = document.getElementById('introSkip');
    if (skip) skip.addEventListener('click', finish);
    setTimeout(finish, 2900);
})();
</script>
