<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>DEV — Clifford’s Webhole</title>
<style>
    :root {
        --bg-main: #050509;
        --bg-section: #11111a;
        --border-accent: #8a2be2;
        --text-main: #e5e5e5;
        --text-muted: #a8a8b3;
        --link: #a97bff;
        --link-hover: #c4a3ff;
    }

    * {
        box-sizing: border-box;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        padding: 0;
        background: radial-gradient(circle at top, #171722 0, #050509 55%);
        color: var(--text-main);
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    }

    a {
        color: var(--link);
        text-decoration: none;
    }
    a:hover {
        color: var(--link-hover);
    }

    /* Top navigation */
    .top-nav {
        position: sticky;
        top: 0;
        z-index: 50;
        background: rgba(5, 5, 9, 0.92);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(138, 43, 226, 0.4);
    }

    .top-nav-inner {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 16px;
    }

    .brand {
        font-weight: 600;
        letter-spacing: 0.08em;
        font-size: 0.9rem;
        text-transform: uppercase;
        color: var(--text-muted);
    }

    .brand span {
        color: var(--border-accent);
    }

    .nav-links {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
    }

    .nav-links a {
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid transparent;
        color: var(--text-muted);
        transition: all 0.15s ease-out;
        white-space: nowrap;
    }

    .nav-links a:hover {
        border-color: rgba(138, 43, 226, 0.6);
        color: var(--text-main);
        background: rgba(138, 43, 226, 0.1);
    }

    /* Hero header */
    .hero {
        max-width: 1100px;
        margin: 40px auto 10px;
        padding: 0 16px;
        text-align: left;
        animation: fadeIn 0.4s ease-out;
    }

    .hero-kicker {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .hero-title {
        font-size: 2.4rem;
        margin: 0 0 8px;
        color: var(--text-main);
    }

    .hero-sub {
        font-size: 1rem;
        color: var(--text-muted);
        max-width: 640px;
    }

    .hero-tag {
        display: inline-block;
        margin-top: 14px;
        padding: 4px 10px;
        border-radius: 999px;
        border: 1px solid rgba(138, 43, 226, 0.6);
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    /* Layout */
    .page-wrap {
        max-width: 1100px;
        margin: 10px auto 80px;
        padding: 0 16px 40px;
        display: grid;
        grid-template-columns: minmax(0, 3fr);
        gap: 20px;
    }

    @media (min-width: 980px) {
        .page-wrap {
            grid-template-columns: minmax(0, 3fr) minmax(220px, 1fr);
            align-items: flex-start;
        }
    }

    /* Sections */
    .section-card {
        background: var(--bg-section);
        border-radius: 14px;
        border: 1px solid rgba(138, 43, 226, 0.45);
        padding: 20px 20px 18px;
        margin-bottom: 18px;
        box-shadow: 0 0 24px rgba(0,0,0,0.6);
        animation: fadeInUp 0.35s ease-out;
    }

    .section-card h2 {
        margin: 0 0 8px;
        font-size: 1.3rem;
        color: var(--border-accent);
    }

    .section-card h3 {
        margin: 18px 0 8px;
        font-size: 1rem;
        color: #d8d8ff;
    }

    .section-card p {
        margin: 6px 0;
        font-size: 0.98rem;
        color: var(--text-muted);
        line-height: 1.65;
    }

    .section-card ul {
        margin: 8px 0 4px 18px;
        padding-left: 4px;
        color: var(--text-muted);
        font-size: 0.96rem;
    }

    .section-card li + li {
        margin-top: 4px;
    }

    .badge-row {
        margin-top: 6px;
        font-size: 0.9rem;
    }

    code {
        background: #171726;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.82rem;
    }

    pre code {
        display: block;
        background: #05050b;
        border-radius: 10px;
        padding: 14px 14px 16px;
        border: 1px solid rgba(138, 43, 226, 0.5);
        box-shadow: 0 0 20px rgba(0,0,0,0.7);
        font-size: 0.8rem;
        white-space: pre-wrap;
        text-align: left;
    }

    /* Sidebar / TOC */
    .sidebar {
        position: sticky;
        top: 72px;
        display: none;
    }

    @media (min-width: 980px) {
        .sidebar {
            display: block;
        }
    }

    .sidebar-card {
        background: #090910;
        border-radius: 14px;
        border: 1px solid rgba(138, 43, 226, 0.5);
        padding: 14px 16px 16px;
        box-shadow: 0 0 20px rgba(0,0,0,0.7);
    }

    .sidebar-card h3 {
        margin: 0 0 8px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: var(--text-muted);
    }

    .sidebar-card ul {
        list-style: none;
        margin: 0;
        padding: 0;
        font-size: 0.85rem;
    }

    .sidebar-card li + li {
        margin-top: 6px;
    }

    .sidebar-card a {
        color: var(--text-muted);
        display: inline-block;
        padding: 2px 0;
    }

    .sidebar-card a:hover {
        color: var(--text-main);
    }

    .sidebar-footer {
        margin-top: 14px;
        font-size: 0.7rem;
        color: var(--text-muted);
        opacity: 0.7;
    }

    /* Footer */
    .footer {
        text-align: center;
        margin: 30px 0 10px;
        font-size: 0.82rem;
        color: var(--text-muted);
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>
<body>

<div class="top-nav">
    <div class="top-nav-inner">
        <div class="brand">
            DEV • <span>CLIFFORD’S WEBHOLE</span>
        </div>
        <nav class="nav-links">
            <a href="#overview">Overview</a>
            <a href="#what-i-do">What I Do</a>
            <a href="#stack">Tech Stack</a>
            <a href="#projects">Projects</a>
            <a href="#mission">Mission</a>
            <a href="#focus">Focus</a>
            <a href="#commands">Commands</a>
            <a href="#goals">Goals</a>
            <a href="#connect">Connect</a>
        </nav>
    </div>
</div>

<header class="hero" id="overview">
    <div class="hero-kicker">Under Construction • Dev Environment</div>
    <h1 class="hero-title">dev.cliffordswebhole.com</h1>
    <p class="hero-sub">
        Experimental lab for new themes, plugins, automations, and infrastructure powering Clifford’s Webhole.
        Public view is limited while active development is underway.
    </p>
    <div class="hero-tag">You’re seeing the public dev portal. Logged-in admins see the full site.</div>
</header>

<div class="page-wrap">

    <main>

        <!-- ABOUT / INTRO -->
        <section class="section-card" id="about">
            <h2>👋 Hey, I’m <strong>Clifford</strong></h2>
            <p>
                Builder of <strong>Clifford’s Webhole</strong>, architect of self-hosted systems, and creator of
                multi-site WordPress environments, AI automations, and distributed content pipelines.
            </p>
            <p>
                I specialize in blending <strong>web development</strong>, <strong>Linux server engineering</strong>,
                <strong>Docker DevOps</strong>, and <strong>AI automation</strong> into one connected digital ecosystem.
            </p>
        </section>

        <!-- WHAT I DO -->
        <section class="section-card" id="what-i-do">
            <h2>⚡ What I Do</h2>
            <ul>
                <li>🧩 Build &amp; maintain a full VPS infrastructure (Ubuntu 24.04, Docker, Nginx Proxy Manager, UFW)</li>
                <li>🕸️ Develop &amp; manage multiple WordPress platforms (custom themes, backups, migrations, automation)</li>
                <li>🤖 Run a self-hosted AI Lab at <code>labs.cliffordswebhole.com</code> (FastAPI, custom agents, n8n flows)</li>
                <li>☁️ Automate content across my ecosystem with n8n + OpenRouter</li>
                <li>🔐 Implement strong backup &amp; security workflows (Bash, cron, Backblaze B2, rclone)</li>
                <li>💻 Code daily across Linux, Android Termux, and cloud platforms</li>
                <li>🌱 Beekeeper &amp; gardener — the offline part of Clifford’s Webhole</li>
            </ul>
        </section>

        <!-- STACK -->
        <section class="section-card" id="stack">
            <h2>🛠️ Tech Stack &amp; Tools</h2>

            <h3>Languages</h3>
            <p class="badge-row">
                <code>HTML</code> · <code>CSS</code> · <code>JavaScript</code> · <code>PHP</code> · <code>Bash</code> · <code>SQL</code>
            </p>

            <h3>Platforms &amp; Systems</h3>
            <p class="badge-row">
                <code>Ubuntu 24.04</code> · <code>Docker</code> · <code>Nginx Proxy Manager</code> · <code>UFW</code> · <code>Termux</code>
            </p>

            <h3>Web Development</h3>
            <p class="badge-row">
                <code>WordPress</code> · <code>Custom Themes</code> · <code>WP-CLI</code> · <code>Site Migrations</code>
            </p>

            <h3>AI &amp; Automation</h3>
            <p class="badge-row">
                <code>FastAPI</code> · <code>Ollama</code> · <code>OpenRouter</code> · <code>n8n</code> · <code>Custom Agents</code>
            </p>

            <h3>Backup / DevOps</h3>
            <p class="badge-row">
                <code>Backblaze B2</code> · <code>rclone</code> · <code>cron</code> · <code>msmtp</code> · <code>Shell scripting</code> · <code>Cloudflare</code>
            </p>
        </section>

        <!-- PROJECTS -->
        <section class="section-card" id="projects">
            <h2>📌 Featured Projects</h2>

            <h3>🔹 Clifford’s Webhole (Main Site)</h3>
            <p>Your personal hub for coding, AI, security, and everything digital.</p>

            <h3>🔹 BeeBuzzGardens.com</h3>
            <p>A growing beekeeping + homesteading platform with seasonal content.</p>

            <h3>🔹 Webhole Labs (AI Playground)</h3>
            <p>Your custom self-hosted AI stack experimenting with multi-agent systems.</p>

            <h3>🔹 n8n Auto-Poster System</h3>
            <p>Automated RSS → WordPress pipeline with tag + category routing.</p>
        </section>

        <!-- MISSION -->
        <section class="section-card" id="mission">
            <h2>🧭 Mission Statement</h2>
            <p>
                I build reliable, self-hosted systems that connect the digital and real world.  
                My mission is to create a unified ecosystem — servers, AI agents, automations, and websites — 
                all powered by Clifford’s Webhole and always under my control.
            </p>
            <p>I believe in:</p>
            <ul>
                <li>Owning my infrastructure</li>
                <li>Automating everything</li>
                <li>Learning relentlessly</li>
                <li>Sharing what I build</li>
            </ul>
        </section>

        <!-- CURRENT WORK -->
        <section class="section-card" id="current-work">
            <h2>🔥 What I'm Working on Right Now</h2>
            <ul>
                <li>Upgrading <strong>Webhole Labs</strong> with faster agent routing and new model integrations</li>
                <li>Improving multi-site backups with rclone + B2 retention automation</li>
                <li>Expanding my <strong>n8n WordPress Auto-Poster</strong> into a full content automation pipeline</li>
                <li>Refining dark-themed web components for Clifford’s Webhole</li>
                <li>Building stronger GitHub branding and documentation for my projects</li>
            </ul>
        </section>

        <!-- FOCUS -->
        <section class="section-card" id="focus">
            <h2>🧠 Current Focus Areas</h2>
            <ul>
                <li>AI agents &amp; workflow automation</li>
                <li>VPS performance tuning (Ubuntu 24.04 + Docker)</li>
                <li>WordPress architecture &amp; custom theme development</li>
                <li>Network security and server hardening</li>
                <li>Optimizing mobile-Termux Linux workflows</li>
            </ul>
        </section>

        <!-- COMMANDS -->
        <section class="section-card" id="commands">
            <h2>📚 Favorite Commands &amp; Snippets</h2>
            <pre><code># restart docker stack
docker compose down && docker compose up -d

# inspect docker networks
docker network inspect proxy-net

# view logs live
docker logs -f container_name

# quick mysql access
docker exec -it db-container mysql -u root -p

# backup WordPress
wp db export backup.sql && tar -czvf site.tar.gz /var/www/html
            </code></pre>
        </section>

        <!-- GOALS -->
        <section class="section-card" id="goals">
            <h2>🎯 Goals for 2026</h2>
            <ul>
                <li>Expand Webhole Labs with custom multi-agent routing</li>
                <li>Create a unified cross-site content engine powered by n8n</li>
                <li>Build a full set of branded Webhole utilities &amp; scripts</li>
                <li>Develop at least one public developer tool or library</li>
                <li>Grow Clifford’s Webhole into a recognized online identity</li>
            </ul>
        </section>

        <!-- CONNECT -->
        <section class="section-card" id="connect">
            <h2>🌐 Connect With Me</h2>
            <p style="text-align:center;">
                <a href="https://www.linkedin.com/in/clifford-webhole/">🌐 LinkedIn</a> &nbsp;·&nbsp;
                <a href="https://youtube.com/@cliffordswebhole">📺 YouTube</a> &nbsp;·&nbsp;
                <a href="https://www.instagram.com/cliffordswebhole">📸 Instagram</a> &nbsp;·&nbsp;
                <a href="https://www.tiktok.com/@cliffordswebhole">🎵 TikTok</a>
                <a href="https://github.com/cliffordwebhole">🐱 GitHub</a>            </p>
        </section>

    </main>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-card">
            <h3>Quick Nav</h3>
            <ul>
                <li><a href="#overview">Overview</a></li>
                <li><a href="#about">About Clifford</a></li>
                <li><a href="#what-i-do">What I Do</a></li>
                <li><a href="#stack">Tech Stack</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#mission">Mission</a></li>
                <li><a href="#focus">Focus</a></li>
                <li><a href="#commands">Commands</a></li>
                <li><a href="#goals">Goals</a></li>
                <li><a href="#connect">Connect</a></li>
            </ul>
            <div class="sidebar-footer">
                Public view of the dev environment.<br>
                Admins see the live build behind the scenes.
            </div>
        </div>
    </aside>

</div>

<div class="footer">
    ⚡ Powered by Clifford’s Webhole • Built on Linux • Fueled by Coffee &amp; Curiosity<br>
    © <?php echo date('Y'); ?> <a href="https://cliffordswebhole.com">Clifford’s Webhole</a>
</div>

</body>
</html>
