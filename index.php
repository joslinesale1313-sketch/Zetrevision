<?php
// Simulation d'une base de données de 5 chaînes
$channels = [
    [
        "id" => "1",
        "name" => "RTNC",
        "category" => "Actualités",
        "logo" => "https://picsum.photos/seed/rtnc/300/170",
        "streamUrl" => "https://tnt-television.com/rtnc_HD/index.m3u8",
        "description" => "Radio Télévision Nationale Congolaise - Le média public.",
        "viewers" => "12.4k"
    ],
    [
        "id" => "2",
        "name" => "La Compassion",
        "category" => "Religieux",
        "logo" => "https://picsum.photos/seed/compassion/300/170",
        "streamUrl" => "https://tnt-television.com/CCPV-TV/index.m3u8",
        "description" => "Chaîne de édification chrétienne et de prière.",
        "viewers" => "45.2k"
    ],
    [
        "id" => "3",
        "name" => "Télé 50",
        "category" => "Info & Débat",
        "logo" => "https://picsum.photos/seed/tele50/300/170",
        "streamUrl" => "https://stream-195689.castr.net/63dea568fbc24884706157bb/live_08637130250e11f0afb3a374844fe15e/index.m3u8",
        "description" => "Le décryptage de l'actualité en République Démocratique du Congo.",
        "viewers" => "9.1k"
    ],
    [
        "id" => "4",
        "name" => "RTVE",
        "category" => "International",
        "logo" => "https://picsum.photos/seed/rtve/300/170",
        "streamUrl" => "https://ztnr.rtve.es/ztnr/3293681.m3u8",
        "description" => "Actualités internationales et culture espagnole.",
        "viewers" => "15.8k"
    ],
    [
        "id" => "5",
        "name" => "B-One TV",
        "category" => "Divertissement",
        "logo" => "https://picsum.photos/seed/bone/300/170",
        "streamUrl" => "https://tnt-television.com/B-ONE/index.m3u8",
        "description" => "Émissions de variétés, musique et talk-shows.",
        "viewers" => "6.3k"
    ]
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>zetrevion | Live TV</title>
    
    <link href="https://vjs.zencdn.net/8.10.0/video-js.css" rel="stylesheet" />
    <script src="https://vjs.zencdn.net/8.10.0/video.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #0B0B0B;
            --surface: #121212;
            --muted-surface: #1A1A1A;
            --primary: #E50914;
            --accent: #FF6B6B;
            --text: #F5F5F5;
            --text-dim: #A6A6A6;
            --glass: rgba(255,255,255,0.03);
        }

        * { box-sizing: border-box; }

        body {
            background: linear-gradient(180deg, var(--bg) 0%, #070707 100%);
            color: var(--text);
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* Header */
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 12px 20px;
            background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(0,0,0,0.25));
            position: sticky;
            top: 0;
            z-index: 200;
            backdrop-filter: blur(6px);
            flex-wrap: wrap;
        }

        .brand { display:flex; align-items:center; gap:10px; }
        .logo {
            background: var(--primary);
            color: #fff;
            padding: 8px 12px;
            border-radius: 8px;
            font-weight: 800;
            letter-spacing: 0.6px;
            box-shadow: 0 6px 18px rgba(229,9,20,0.14);
            font-size: 16px;
        }

        .site-title { color:var(--text-dim); font-weight:300; }

        .header-actions { display:flex; gap:12px; align-items:center; width: auto; }
        .search-input {
            background: var(--glass);
            border: 1px solid rgba(255,255,255,0.04);
            color: var(--text);
            padding: 8px 12px;
            border-radius: 10px;
            width: 260px;
            transition: width .18s ease;
        }
        .search-input::placeholder{ color:var(--text-dim); }

        /* Player + Info */
        .container { max-width: 1200px; margin: 18px auto; padding: 0 16px; }
        .player-section { display:flex; gap:20px; align-items:flex-start; }
        .player-wrapper { flex: 1 1 720px; background: #000; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 30px rgba(0,0,0,0.6); }
        .video-js { width:100%; aspect-ratio: 16/9; }

        .current-info { width: 360px; background: linear-gradient(180deg, rgba(255,255,255,0.02), transparent); padding:16px; border-radius:12px; border:1px solid rgba(255,255,255,0.03); }
        .live-badge { background: linear-gradient(90deg,var(--primary),var(--accent)); padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:8px; }
        .current-info h1 { margin:12px 0 6px; font-size:20px; }
        .current-info p { margin:0; color:var(--text-dim); }
        .stat { margin-top:10px; font-size:14px; color:var(--text-dim); }

        /* Grid des chaînes */
        .grid-container { margin-top:22px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 18px; margin-top:12px; }

        .channel-card { background: linear-gradient(180deg,var(--muted-surface), var(--surface)); border-radius:12px; overflow:hidden; cursor:pointer; transition:transform .22s ease, box-shadow .22s ease; border:1px solid rgba(255,255,255,0.02); }
        .channel-card:hover { transform: translateY(-6px); box-shadow: 0 10px 30px rgba(0,0,0,0.6); }
        .channel-card.active { outline: 3px solid rgba(229,9,20,0.12); }
        .channel-card img { width:100%; height:140px; object-fit:cover; display:block; }
        .card-body { padding:10px 12px 14px; }
        .card-name { font-weight:600; margin-bottom:6px; }
        .card-cat { color:var(--text-dim); font-size:13px; }

        .card-badge { position: absolute; right:10px; top:10px; background: rgba(0,0,0,0.45); color:var(--text); padding:6px 8px; border-radius:10px; font-size:12px; }
        .card-wrap{ position:relative; }

        /* Responsive tweaks */
        @media (max-width: 900px) {
            .player-section { flex-direction: column; }
            .current-info { width: 100%; margin-top: 12px; }
            .search-input { width: 220px; }
        }

        @media (max-width: 600px) {
            header { padding: 10px 12px; }
            .brand .site-title { display: none; }
            .logo { padding: 8px; font-size: 15px; }
            .search-input { width: 100%; }
            .header-actions { width: 100%; order: 2; margin-top: 8px; }
            .player-wrapper { width: 100%; }
            .current-info { order: 3; }
            .container { padding: 0 12px; }
        }

        @media (max-width: 420px) {
            .grid { grid-template-columns: 1fr; gap:12px; }
            .channel-card img { height: 160px; }
            .card-body { padding: 12px; }
            .card-name { font-size: 16px; }
            .card-cat { font-size: 13px; }
            .search-input { padding: 10px 12px; }
            .live-badge { padding: 6px 10px; font-size: 13px; }
        }
    </style>

</head>
<body>

<header>
    <div class="brand">
        <div class="logo">Z</div>
        <div class="site-title">VISION</div>
    </div>
    <div class="header-actions">
        <input id="search" class="search-input" placeholder="Rechercher une chaîne..." oninput="filterChannels(this.value)">
    </div>
</header>

<main>
    <div class="container">
        <div class="player-section">
            <div class="player-wrapper">
                <video id="main-video" class="video-js vjs-big-play-centered" controls preload="auto" data-setup='{"fluid": true}'>
                    <source id="video-source" src="<?= $channels[0]['streamUrl'] ?>" type="application/x-mpegURL">
                </video>
            </div>

            <aside class="current-info">
                <span class="live-badge">🔴 EN DIRECT</span>
                <h1 id="active-title"><?= $channels[0]['name'] ?></h1>
                <p id="active-desc"><?= $channels[0]['description'] ?></p>
                <p class="stat"><strong id="active-viewers"><?= $channels[0]['viewers'] ?></strong> spectateurs</p>
            </aside>
        </div>

        <div class="grid-container">
            <h3>Choisir une chaîne</h3>
            <div class="grid">
                <?php foreach ($channels as $index => $ch): ?>
                    <div class="channel-card <?= $index === 0 ? 'active' : '' ?>" 
                         onclick="switchChannel(this, '<?= addslashes($ch['name']) ?>', '<?= $ch['streamUrl'] ?>', '<?= addslashes($ch['description']) ?>', '<?= $ch['viewers'] ?>')">
                        <div class="card-wrap">
                            <img src="<?= $ch['logo'] ?>" alt="<?= $ch['name'] ?>">
                            <div class="card-badge"><?= $ch['viewers'] ?></div>
                        </div>
                        <div class="card-body">
                            <div class="card-name"><?= $ch['name'] ?></div>
                            <div class="card-cat"><?= $ch['category'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<script>
    // Initialisation du lecteur Video.js
    var player = videojs('main-video');
    function switchChannel(element, name, url, desc, viewers) {
        // 1. Mettre à jour l'interface visuelle (bordure active)
        document.querySelectorAll('.channel-card').forEach(card => card.classList.remove('active'));
        element.classList.add('active');

        // 2. Changer le texte
        document.getElementById('active-title').innerText = name;
        document.getElementById('active-desc').innerText = desc;
        document.getElementById('active-viewers').innerText = viewers;

        // 3. Changer la source du flux vidéo et relancer la lecture
        player.src({ type: 'application/x-mpegURL', src: url });
        player.play().catch(()=>{});

        // 4. Remonter en haut de page en douceur
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Filtre simple pour la recherche
    function filterChannels(query) {
        const q = String(query || '').trim().toLowerCase();
        document.querySelectorAll('.grid .channel-card').forEach(card => {
            const name = (card.querySelector('.card-name')?.innerText || '').toLowerCase();
            const cat = (card.querySelector('.card-cat')?.innerText || '').toLowerCase();
            if (!q) { card.style.display = ''; return; }
            card.style.display = (name.includes(q) || cat.includes(q)) ? '' : 'none';
        });
    }
</script>

</body>
</html>