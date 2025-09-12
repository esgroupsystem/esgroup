<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>ES Intro – Netflix-Style (Blue)</title>
<style>
/* ==== YOUR STYLES EXACTLY ==== */
:root {
  --bg: #c0bbbb;
  --blue: #2aa8ff;
  --blue-2: #70d0ff;
  --cyan: #9ff1ff;
  --ink: #dee1e4;
  --glow: #53c8ff;
  --glow-2: #b5ecff;
  --duration: 2600ms;
}
* { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; }
body {
  background: var(--bg);
  font-family: system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial;
  overflow: hidden;
}
/* INTRO */
.intro { position: relative; width: 100vw; height: 100vh; background: radial-gradient(1200px 600px at 50% 60%, #081018 0%, #02060a 55%, #000 75%); display: grid; place-items: center; perspective: 1200px; animation: camera-pop var(--duration) ease both; will-change: transform, filter; }
/* VIGNETTE */
.vignette { position: absolute; inset: 0; pointer-events: none; background: radial-gradient(60% 60% at 50% 50%, transparent 40%, rgba(0,0,0,.4) 80%, rgba(0,0,0,.75) 100%); mix-blend-mode: multiply; opacity: 0; animation: vignette-in calc(var(--duration) * .75) ease .2s forwards; will-change: opacity; }
/* MIST */
.mist { position: absolute; inset: -20% -20%; background: radial-gradient(40% 60% at 30% 60%, rgba(40,160,255,.15), transparent 60%), radial-gradient(50% 70% at 70% 40%, rgba(120,220,255,.12), transparent 65%), radial-gradient(30% 30% at 50% 80%, rgba(30,120,200,.20), transparent 65%); filter: blur(28px); transform: translateZ(-300px) scale(1.5); opacity: 0; animation: mist-in calc(var(--duration) * .9) ease .1s forwards; will-change: opacity; }
/* RIBBONS */
.ribbons { position: absolute; inset: -10% -10%; overflow: hidden; filter: blur(2px) saturate(120%); }
.ribbon { position: absolute; left: -30vw; right: -30vw; height: 2px; background: linear-gradient(90deg, transparent 0%, var(--blue) 30%, var(--cyan) 50%, var(--blue-2) 70%, transparent 100%); opacity: 0; transform: translateZ(-100px) rotate(6deg); will-change: transform, opacity, filter; }
.ribbon.r1 { top: 35%; animation: sweep 1300ms ease-out .0s forwards; }
.ribbon.r2 { top: 48%; animation: sweep 1500ms ease-out .1s forwards; }
.ribbon.r3 { top: 52%; animation: sweep 1700ms ease-out .15s forwards; }
.ribbon.r4 { top: 60%; animation: sweep 1400ms ease-out .2s forwards; }
@keyframes sweep { 0% { transform: translateX(-50vw) translateZ(-120px) rotate(6deg); opacity:0; } 35% { opacity:.9; } 100% { transform: translateX(50vw) translateZ(-120px) rotate(6deg); opacity:0; } }
/* LOGO */
.logo-wrap { position: relative; width: min(78vw, 1100px); height: min(36vw, 510px); display: grid; place-items: center; }
.logo { font-weight: 900; font-size: clamp(120px, 22vw, 420px); letter-spacing: -0.04em; text-transform: uppercase; background: repeating-linear-gradient(-15deg, #00263a 0px, #00263a 10px, #0b7eb8 28px, #0b7eb8 34px, #69d4ff 34px, #69d4ff 38px, #c9f6ff 38px, #c9f6ff 42px); background-size: 240px 240px; background-position: -240px 0; -webkit-background-clip: text; color: transparent; filter: drop-shadow(0 0 6px rgba(120,220,255,.35)) drop-shadow(0 0 24px rgba(80,180,255,.25)); animation: streak calc(var(--duration) * 0.9) ease-in-out .35s both; will-change: background-position, transform, opacity; }
@keyframes streak { 0% { background-position: -320px 0; opacity: 0; transform: scale(1.08); } 35% { opacity: 1; } 100% { background-position: 320px 0; transform: scale(1); } }
/* SCAN LIGHT */
.logo::after { content: ""; position: absolute; inset: -6% -3%; background: linear-gradient(110deg, transparent 0%, rgba(255,255,255,.06) 35%, rgba(255,255,255,.18) 50%, rgba(255,255,255,.06) 65%, transparent 100%); transform: translateX(-140%) skewX(-12deg); animation: scan calc(var(--duration) * .7) ease .55s forwards; will-change: transform; }
@keyframes scan { to { transform: translateX(140%) skewX(-12deg); } }
/* GLOW */
.glow { position: absolute; inset: 0; display: grid; place-items: center; filter: blur(18px) saturate(140%); opacity: 0; animation: glow-in calc(var(--duration) * .7) ease .5s forwards; will-change: opacity; }
.glow .stroke { font-size: clamp(120px, 22vw, 420px); font-weight: 800; letter-spacing: -0.04em; color: transparent; -webkit-text-stroke: 10px rgba(120,220,255,.45); }
/* PARTICLES */
.particles { position: absolute; inset: 0; mix-blend-mode: screen; }
.dot { position: absolute; width: 2px; height: 2px; border-radius: 50%; background: var(--glow-2); filter: blur(.5px); opacity: 0; animation: sparkle 1800ms ease-in-out both; }
.dot.d1 { top: 22%; left: 26%; animation-delay:.35s; }
.dot.d2 { top: 68%; left: 62%; animation-delay:.55s; }
.dot.d3 { top: 48%; left: 42%; animation-delay:.75s; }
.dot.d4 { top: 34%; left: 74%; animation-delay:.95s; }
@keyframes sparkle { 0% { opacity: 0; transform: translateY(6px); } 40% { opacity: .9; } 100% { opacity: 0; transform: translateY(-6px); } }
/* CAMERA MOTION */
@keyframes camera-pop { 0% { transform: scale(1.1) rotateX(0.6deg); filter: brightness(.95); } 60% { transform: scale(1.0) rotateX(0); filter: brightness(1.0); } 100% { transform: scale(1.0); } }
@keyframes vignette-in { to { opacity: 1; } }
@keyframes mist-in { to { opacity: .9; } }
@keyframes glow-in { 0% { opacity: 0; } 60% { opacity: .9; } 100% { opacity: .5; } }
/* REPLAY BUTTON */
.controls { position: absolute; bottom: 28px; left: 50%; transform: translateX(-50%); }
.btn { padding: 10px 16px; border-radius: 999px; background: linear-gradient(180deg, #0f2234, #08141f); color: #cfeeff; font-weight: 600; border: none; cursor: pointer; }
.btn:hover { filter: brightness(1.08); }
.btn:active { filter: brightness(.98); }
</style>
</head>
<body>
<section class="intro" id="intro">
  <div class="mist"></div>
  <div class="ribbons">
    <span class="ribbon r1"></span>
    <span class="ribbon r2"></span>
    <span class="ribbon r3"></span>
    <span class="ribbon r4"></span>
  </div>
  <div class="logo-wrap">
    <h1 class="logo">ES</h1>
    <div class="glow"><div class="stroke">ES</div></div>
  </div>
  <div class="vignette"></div>
  <div class="particles">
    <span class="dot d1"></span>
    <span class="dot d2"></span>
    <span class="dot d3"></span>
    <span class="dot d4"></span>
  </div>
  <div class="controls">
    <button class="btn" id="replay">Replay</button>
  </div>
</section>
<script>
const replayBtn = document.getElementById('replay');
const intro = document.getElementById('intro');
function replay() {
  const clone = intro.cloneNode(true);
  intro.parentNode.replaceChild(clone, intro);
  clone.querySelector('#replay').addEventListener('click', replay);
}
replayBtn.addEventListener('click', replay);

// Auto-redirect after animation
setTimeout(() => {
  window.location.href = "{{ route('landing') }}";
}, 2800);
</script>
</body>
</html>
