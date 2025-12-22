<?php
/**
 * Front Page Plug – Default Front Page / Maintenance Template
 *
 * This template is shown to visitors when:
 * - Maintenance Mode is enabled, OR
 * - Custom Front Page Mode is active on the homepage
 *
 * Safe, neutral, and theme-independent.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?> — Under Maintenance</title>

    <?php wp_head(); ?>

    <style>
        :root {
            --bg: #0f172a;
            --panel: #020617;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --accent: #3b82f6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #020617, #000);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .fpp-container {
            max-width: 640px;
            width: 100%;
            background: rgba(2, 6, 23, 0.85);
            border: 1px solid rgba(148, 163, 184, 0.15);
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.6);
        }

        h1 {
            margin-top: 0;
            font-size: 2rem;
            font-weight: 700;
        }

        p {
            color: var(--muted);
            font-size: 1rem;
            line-height: 1.6;
        }

        .status {
            display: inline-block;
            margin-bottom: 16px;
            padding: 6px 14px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--accent);
            border: 1px solid rgba(59, 130, 246, 0.4);
            border-radius: 999px;
        }

        footer {
            margin-top: 32px;
            font-size: 0.75rem;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <main class="fpp-container">
        <div class="status">Maintenance Mode</div>

        <h1>We&rsquo;ll Be Back Soon</h1>

        <p>
            This site is currently undergoing scheduled maintenance.
            We&rsquo;re working behind the scenes to improve performance,
            stability, and features.
        </p>

        <p>
            Please check back shortly.
        </p>

        <footer>
            &copy; <?php echo esc_html(date('Y')); ?>
            <?php bloginfo('name'); ?>
        </footer>
    </main>

    <?php wp_footer(); ?>
</body>
</html>
