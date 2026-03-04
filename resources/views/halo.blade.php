<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>halo</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;1,300;1,400&family=Cinzel:wght@400&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        :root {
            --ink: #1a0a0a;
            --cream: #f5efe6;
            --rose: #c9748a;
            --gold: #b8973a;
            --shadow: rgba(180, 80, 100, 0.15);
        }

        html, body {
            width: 100%; height: 100%;
            overflow: hidden;
        }

        body {
            background-color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: none;
        }

        /* === BACKGROUND NOISE TEXTURE === */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.05'/%3E%3C/svg%3E");
            opacity: 0.4;
            pointer-events: none;
            z-index: 0;
        }

        /* === VIGNETTE === */
        body::after {
            content: '';
            position: fixed; inset: 0;
            background: radial-gradient(ellipse at center, transparent 40%, rgba(0,0,0,0.85) 100%);
            pointer-events: none;
            z-index: 0;
        }

        /* === FLOATING PETALS === */
        .petals {
            position: fixed; inset: 0;
            pointer-events: none;
            z-index: 1;
        }

        .petal {
            position: absolute;
            width: 6px; height: 9px;
            background: radial-gradient(ellipse, rgba(201,116,138,0.6), transparent);
            border-radius: 50% 0 50% 0;
            animation: fall linear infinite;
        }

        .petal:nth-child(1)  { left:5%;  animation-duration:12s; animation-delay:0s;   opacity:0.5; }
        .petal:nth-child(2)  { left:15%; animation-duration:9s;  animation-delay:2s;   opacity:0.3; width:4px; height:6px; }
        .petal:nth-child(3)  { left:30%; animation-duration:14s; animation-delay:4s;   opacity:0.6; }
        .petal:nth-child(4)  { left:45%; animation-duration:10s; animation-delay:1s;   opacity:0.4; width:5px; height:8px; }
        .petal:nth-child(5)  { left:60%; animation-duration:11s; animation-delay:3s;   opacity:0.5; }
        .petal:nth-child(6)  { left:75%; animation-duration:13s; animation-delay:5s;   opacity:0.3; width:4px; height:7px; }
        .petal:nth-child(7)  { left:88%; animation-duration:8s;  animation-delay:6s;   opacity:0.6; }
        .petal:nth-child(8)  { left:22%; animation-duration:15s; animation-delay:7s;   opacity:0.35; }
        .petal:nth-child(9)  { left:55%; animation-duration:10s; animation-delay:1.5s; opacity:0.45; width:3px; height:5px; }
        .petal:nth-child(10) { left:70%; animation-duration:12s; animation-delay:8s;   opacity:0.4; }

        @keyframes fall {
            0%   { transform: translateY(-20px) rotate(0deg) translateX(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 0.8; }
            100% { transform: translateY(110vh) rotate(360deg) translateX(30px); opacity: 0; }
        }

        /* === MAIN CARD === */
        .card {
            position: relative;
            z-index: 10;
            text-align: center;
            padding: 4rem 5rem;
            animation: emerge 2.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        @keyframes emerge {
            0%   { opacity: 0; transform: translateY(30px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* === DECORATIVE FRAME === */
        .frame {
            position: absolute; inset: 0;
            pointer-events: none;
        }

        .frame::before, .frame::after {
            content: '';
            position: absolute;
            border: 1px solid rgba(184,151,58,0.25);
            border-radius: 2px;
        }

        .frame::before {
            inset: 0;
        }

        .frame::after {
            inset: 8px;
            border-color: rgba(184,151,58,0.12);
        }

        .corner {
            position: absolute;
            width: 18px; height: 18px;
            border-color: var(--gold);
            border-style: solid;
            opacity: 0.6;
        }
        .corner.tl { top: -1px; left: -1px;  border-width: 1px 0 0 1px; }
        .corner.tr { top: -1px; right: -1px; border-width: 1px 1px 0 0; }
        .corner.bl { bottom: -1px; left: -1px;  border-width: 0 0 1px 1px; }
        .corner.br { bottom: -1px; right: -1px; border-width: 0 1px 1px 0; }

        /* === EYEBROW / KICKER === */
        .eyebrow {
            font-family: 'Cinzel', serif;
            font-size: 0.6rem;
            letter-spacing: 0.35em;
            color: var(--gold);
            text-transform: uppercase;
            opacity: 0;
            animation: fadein 1s ease 1s forwards;
            margin-bottom: 2rem;
        }

        /* === DIVIDER === */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1.8rem auto;
            width: fit-content;
            opacity: 0;
            animation: fadein 1s ease 1.4s forwards;
        }

        .divider-line {
            width: 60px; height: 1px;
            background: linear-gradient(to right, transparent, rgba(184,151,58,0.5));
        }

        .divider-line.right {
            background: linear-gradient(to left, transparent, rgba(184,151,58,0.5));
        }

        .divider-diamond {
            width: 5px; height: 5px;
            background: var(--gold);
            transform: rotate(45deg);
            opacity: 0.7;
        }

        /* === MAIN MESSAGE === */
        .message {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-weight: 300;
            font-size: clamp(1.4rem, 4vw, 2.2rem);
            color: var(--cream);
            letter-spacing: 0.05em;
            line-height: 1.5;
            opacity: 0;
            animation: fadein 1.2s ease 0.8s forwards;
            text-shadow: 0 0 40px rgba(201,116,138,0.3);
        }

        .message .highlight {
            font-style: italic;
            font-weight: 400;
            color: #e8a0b0;
            position: relative;
            display: inline-block;
        }

        .message .highlight::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(232,160,176,0.6), transparent);
        }

        /* === FOOTER VERSE === */
        .verse {
            font-family: 'Cormorant Garamond', serif;
            font-style: italic;
            font-size: 0.75rem;
            color: rgba(245,239,230,0.3);
            letter-spacing: 0.1em;
            margin-top: 2rem;
            opacity: 0;
            animation: fadein 1s ease 2s forwards;
        }

        @keyframes fadein {
            to { opacity: 1; }
        }

        /* === CUSTOM CURSOR === */
        .cursor {
            position: fixed;
            width: 8px; height: 8px;
            background: var(--rose);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: transform 0.1s ease, opacity 0.3s;
            mix-blend-mode: screen;
        }

        .cursor-ring {
            position: fixed;
            width: 28px; height: 28px;
            border: 1px solid rgba(201,116,138,0.4);
            border-radius: 50%;
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            transition: transform 0.35s ease, width 0.3s ease, height 0.3s ease;
        }

        /* === AMBIENT GLOW === */
        .glow {
            position: fixed;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(201,116,138,0.08) 0%, transparent 70%);
            border-radius: 50%;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
            z-index: 2;
            animation: pulse 5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.6; }
            50%       { transform: translate(-50%, -50%) scale(1.15); opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Custom Cursor -->
    <div class="cursor" id="cursor"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <!-- Ambient Glow -->
    <div class="glow"></div>

    <!-- Floating Petals -->
    <div class="petals">
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
        <div class="petal"></div>
    </div>

    <!-- Main Card -->
    <div class="card">
        <div class="frame">
            <div class="corner tl"></div>
            <div class="corner tr"></div>
            <div class="corner bl"></div>
            <div class="corner br"></div>
        </div>

        <p class="eyebrow">sebuah pesan kecil</p>

        <p class="message">
            I Love You,<br>
            <span class="highlight">Ilmi Muallimah</span>
        </p>

        <div class="divider">
            <div class="divider-line"></div>
            <div class="divider-diamond"></div>
            <div class="divider-line right"></div>
        </div>

        <p class="verse">— Love You EveryUniverse —</p>
    </div>

    <script>
        const cursor = document.getElementById('cursor');
        const ring   = document.getElementById('cursorRing');

        document.addEventListener('mousemove', e => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top  = e.clientY + 'px';
            setTimeout(() => {
                ring.style.left = e.clientX + 'px';
                ring.style.top  = e.clientY + 'px';
            }, 80);
        });
    </script>

</body>
</html>
