<?php
  // Ação assíncrona: abrir pasta raiz no Windows Explorer
  if (!empty($_GET['action']) && $_GET['action'] === 'open_folder') {
    header('Content-Type: application/json');
    $docRoot = realpath(isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '.');
    if ($docRoot && is_dir($docRoot)) {
      // Abre o Windows Explorer diretamente na pasta
      pclose(popen('start "" explorer ' . escapeshellarg($docRoot), 'r'));
      echo json_encode(['success' => true]);
    } else {
      echo json_encode(['success' => false, 'error' => 'Diretório não encontrado']);
    }
    exit;
  }

  // Visualizador phpinfo()
  if (!empty($_GET['q'])) {
    switch ($_GET['q']) {
      case 'info':
        phpinfo(); 
        exit;
      break;
    }
  }

  // Define o diretório atual (www)
  $dir = ".";
  
  // Escaneia os arquivos e pastas, removendo os pontos (.) e (..)
  $scanned = @scandir($dir);
  $itens = $scanned ? array_diff($scanned, array('..', '.')) : [];

  // Filtra apenas pastas que não sejam ocultas (não começam com ponto)
  $projects = [];
  foreach ($itens as $item) {
    if (is_dir($item) && $item[0] !== '.') {
      $projects[] = $item;
    }
  }
  natcasesort($projects); // Ordenação alfabética natural

  // Detecção de dados do servidor
  $serverSoftware = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : 'Servidor Web';
  $phpVersion = PHP_VERSION;
  $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : realpath($dir);
  $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
  $serverPort = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : '80';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laragon - Meus Projetos</title>
    
    <!-- Favicon Oficial Colorido do Laragon (Zero arquivos locais na pasta) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg viewBox='-58.57 -59.93 908.27 797.36' xmlns='http://www.w3.org/2000/svg'%3E%3ClinearGradient id='a' gradientUnits='userSpaceOnUse' x1='400.117' x2='400.117' y1='22.293' y2='715'%3E%3Cstop offset='.018' stop-color='%233bb6ff'/%3E%3Cstop offset='.302' stop-color='%2339afff'/%3E%3Cstop offset='.552' stop-color='%2336a3ff'/%3E%3Cstop offset='.717' stop-color='%23359fff'/%3E%3Cstop offset='.832' stop-color='%233398ff'/%3E%3Cstop offset='.964' stop-color='%233297ff'/%3E%3C/linearGradient%3E%3Cpath d='M25.27 252.7c1.36-4.1 58.96-201.67 287.1-204.43 0 0 95.66-108.2 226 0 0 0 35.36 29.06 54.76 89.4 0 0 171.14 25.96 198.84 167.4 0 0 57.73 232.9-137.77 396.53 0 0-27.53 22.03-45.87 32.27 0 0-40.66.06-49.06 0-17.9-.14-29.2 0-45.47 0 0 0-25-8.94-26.03-37.5 0 0-2.1-99.34-1.54-116.5 0 0 .5-16.07-22.9-15.07 0 0-22.33-2.57-25.5 16.63 0 0-.53 102.47-1.03 120.64 0 0-1.57 30.23-35.37 31.7 0 0-121.16 3.66-137.26-2.07 0 0-28.07-5.2-30.17-31.73 0 0-22.9-135.2-27.03-177.27 0 0-76.97-42.67-92.57-54.1 0 0 5.2 137.77 53.03 196.03 0 0 8.34 7.3-8.33 17.67 0 0-6.23 5.2-12.5 2.13 0 0-205.17-114.6-129.6-407.1' fill='url(%23a)'/%3E%3Cpath d='M254.93 441.17s179 102.03 287.3-61.77c0 0 87.7-114.53 52.77-236.7 0 0 61.5 102.67-57.5 261.97.03.03-100.9 142.03-282.57 36.5z' fill='%23006699'/%3E%3Cpath d='M184.1 417.1s12.77 59.1-26.5 77.7c0 0-89.33-36.8-80.3-104.77 0 0 2.8-16.96 19.43-6.56 0 0 39.54 21.5 70.74 27.7-.04 0 15.83 1.46 16.63 5.93z' fill='%23cee6ff'/%3E%3Cpath d='M159.3 317.2s13.47-57.57 64.3-53.93c0 0 43.2 1.16 44.73 60.56 0 .04-34.03-88.83-109.03-6.63z' fill='%23006699'/%3E%3C/svg%3E">
    <link rel="alternate icon" type="image/svg+xml" href="https://laragon.org/logo.svg">
    
    <!-- Script antiflicker para Tema Claro/Escuro (Escuro por padrão) -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('laragon_theme');
            // Preferência padrão: Tema Escuro (Dark Mode)
            const theme = savedTheme ? savedTheme : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Variáveis de Tema Claro */
        :root {
            --bg-color: #f8fafc;
            --surface-color: #ffffff;
            --border-color: #e2e8f0;
            --border-hover: #cbd5e1;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --primary: #0284c7;
            --primary-hover: #0369a1;
            --primary-light: #e0f2fe;
            --badge-bg: #f1f5f9;
            --radius-md: 10px;
            --radius-lg: 14px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.04);
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Variáveis de Tema Escuro */
        [data-theme="dark"] {
            --bg-color: #0b1120;
            --surface-color: #1e293b;
            --border-color: #334155;
            --border-hover: #475569;
            --text-main: #f1f5f9;
            --text-muted: #94a3b8;
            --primary: #38bdf8;
            --primary-hover: #7dd3fc;
            --primary-light: rgba(56, 189, 248, 0.14);
            --badge-bg: #0f172a;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.4);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.5);
            --shadow-hover: 0 10px 20px -3px rgba(0, 0, 0, 0.6);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 30px 20px;
            line-height: 1.5;
            transition: background-color 0.25s ease, color 0.25s ease;
        }

        .container {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            flex: 1;
            position: relative;
        }

        /* Top Header */
        .header {
            margin-bottom: 25px;
            position: relative;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            min-height: 48px;
        }

        .logo-title {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 38px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
            text-decoration: none;
        }

        .logo-title span {
            color: var(--primary);
        }

        /* Theme Toggle Button */
        .theme-toggle-btn {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .theme-toggle-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .sun-icon {
            display: none;
        }
        .moon-icon {
            display: block;
        }

        [data-theme="dark"] .sun-icon {
            display: block;
            color: #fbbf24; /* Tom amarelo dourado no dark mode */
        }
        [data-theme="dark"] .moon-icon {
            display: none;
        }

        /* Server Status Badges */
        .system-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 14px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            padding: 5px 12px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-muted);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .badge strong {
            color: var(--text-main);
            font-weight: 600;
        }

        .badge a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .badge a:hover {
            text-decoration: underline;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
        }

        /* Controls Section: Search & Counter */
        .controls-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px 20px;
            margin: 25px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 480px;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            pointer-events: none;
            display: flex;
            align-items: center;
        }

        .search-input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            font-size: 14px;
            font-family: inherit;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            background: var(--bg-color);
            color: var(--text-main);
            outline: none;
            transition: var(--transition);
        }

        .search-input:focus {
            background: var(--surface-color);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-light);
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        /* Card Unificado: Contador + Pasta Amarela Interativa */
        .counter-card-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--badge-bg);
            border: 1px solid var(--border-color);
            padding: 8px 16px;
            border-radius: var(--radius-md);
            color: var(--text-muted);
            cursor: pointer;
            font-family: inherit;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            white-space: nowrap;
        }

        .counter-card-btn:hover {
            border-color: #f59e0b; /* Amarelo pasta Windows */
            background: var(--surface-color);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .counter-card-btn:active {
            transform: translateY(0);
        }

        .folder-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .counter-card-btn:hover .folder-icon-wrapper {
            transform: scale(1.18);
        }

        .count-number {
            font-size: 18px; /* Destaque maior conforme solicitado */
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .count-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* Projects Grid */
        .projects-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        .project-card {
            background: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 16px 18px;
            text-decoration: none;
            color: var(--text-main);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            min-height: 74px;
        }

        .project-card:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            box-shadow: var(--shadow-hover);
        }

        .project-icon {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-md);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
            transition: var(--transition);
        }

        .project-card:hover .project-icon {
            background: var(--primary);
            color: #ffffff;
        }

        [data-theme="dark"] .project-card:hover .project-icon {
            color: #0b1120;
        }

        .project-info {
            flex: 1;
            min-width: 0;
        }

        .project-name {
            font-weight: 600;
            font-size: 15px;
            line-height: 1.4;
            color: var(--text-main);
            word-break: break-word;
            overflow-wrap: anywhere;
            transition: var(--transition);
        }

        .project-card:hover .project-name {
            color: var(--primary);
        }

        .project-arrow {
            color: var(--text-muted);
            opacity: 0.35;
            transition: var(--transition);
            flex-shrink: 0;
            margin-top: 4px;
            display: flex;
            align-items: center;
        }

        .project-card:hover .project-arrow {
            opacity: 1;
            color: var(--primary);
            transform: translate(2px, -2px);
        }

        /* Empty State */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            background: var(--surface-color);
            border: 1px dashed var(--border-color);
            border-radius: var(--radius-lg);
            color: var(--text-muted);
            transition: var(--transition);
        }

        .empty-state svg {
            margin-bottom: 12px;
            opacity: 0.6;
        }

        .empty-state h4 {
            font-size: 16px;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        /* Footer Links */
        .footer {
            margin-top: auto;
            text-align: center;
            padding-top: 25px;
            border-top: 1px solid var(--border-color);
            color: var(--text-muted);
            font-size: 13px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
        }

        .footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: var(--transition);
        }

        .footer a:hover {
            color: var(--primary);
        }

        .footer .dot {
            opacity: 0.4;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding: 20px 14px;
            }

            .logo-title {
                font-size: 30px;
            }

            .theme-toggle-btn {
                position: static;
                transform: none;
            }

            .header-top {
                justify-content: space-between;
            }

            .controls-card {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                padding: 14px;
            }

            .search-wrapper {
                max-width: 100%;
            }

            .counter-pill {
                justify-content: center;
            }

            .projects-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }

            .project-card {
                padding: 14px 16px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <div class="header-top">
                <h1 class="logo-title">
                    Laragon <span>.</span>
                </h1>

                <!-- Botão de Alternância de Tema -->
                <button id="themeToggle" class="theme-toggle-btn" aria-label="Alternar tema claro/escuro" title="Alternar tema">
                    <!-- Ícone Sol (Modo Escuro ativo) -->
                    <svg class="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"></circle>
                        <line x1="12" y1="1" x2="12" y2="3"></line>
                        <line x1="12" y1="21" x2="12" y2="23"></line>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                        <line x1="1" y1="12" x2="3" y2="12"></line>
                        <line x1="21" y1="12" x2="23" y2="12"></line>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                    </svg>
                    <!-- Ícone Lua (Modo Claro ativo) -->
                    <svg class="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                    </svg>
                </button>
            </div>

            <!-- Informações do Servidor em Badges -->
            <div class="system-badges">
                <span class="badge" title="Status do Servidor">
                    <span class="status-dot"></span>
                    <strong><?php echo htmlspecialchars($serverSoftware); ?></strong>
                </span>

                <span class="badge" title="Versão do PHP ativa">
                    <span>🐘 PHP:</span>
                    <strong><?php echo htmlspecialchars($phpVersion); ?></strong>
                    <a href="/?q=info" title="Ver phpinfo()">(info)</a>
                </span>

                <span class="badge" title="Porta e Protocolo">
                    <span>🔒 Porta:</span>
                    <strong><?php echo htmlspecialchars($serverPort) . ($isHttps ? ' (SSL)' : ''); ?></strong>
                </span>

                <span class="badge" title="Diretório Raiz">
                    <span>📂 Root:</span>
                    <strong style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <?php echo htmlspecialchars($docRoot); ?>
                    </strong>
                </span>
            </div>
        </header>

        <!-- Controles: Busca e Contador -->
        <div class="controls-card">
            <div class="search-wrapper">
                <div class="search-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </div>
                <input 
                    type="text" 
                    id="projectSearch" 
                    class="search-input" 
                    placeholder="Filtrar projetos por nome... (Ex: api, site, app)"
                    autocomplete="off"
                >
            </div>

            <!-- Card Único Unificado: Pasta Amarela do Windows + Contador com número em destaque -->
            <button type="button" id="openFolderBtn" class="counter-card-btn" title="Abrir pasta no Windows Explorer">
                <span class="folder-icon-wrapper" aria-hidden="true">
                    <!-- Ícone de Pasta Amarela estilo Windows -->
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="#f59e0b" stroke="#d97706" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </span>
                <span class="count-number" id="projectsCount"><?php echo count($projects); ?></span>
                <span class="count-label">projetos</span>
            </button>
        </div>

        <!-- Grade de Projetos -->
        <main class="projects-grid" id="projectsGrid">
            <?php if (empty($projects)): ?>
                <div class="empty-state">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <h4>Nenhum projeto encontrado</h4>
                    <p>Crie uma nova pasta dentro de <code>www/</code> para que ela apareça aqui.</p>
                </div>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <a href="/<?php echo rawurlencode($project); ?>" class="project-card" data-name="<?php echo htmlspecialchars(mb_strtolower($project)); ?>">
                        <!-- Ícone elegante de Website/Projeto -->
                        <div class="project-icon" aria-hidden="true">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                        </div>
                        <div class="project-info">
                            <div class="project-name" title="<?php echo htmlspecialchars($project); ?>">
                                <?php echo htmlspecialchars($project); ?>
                            </div>
                        </div>
                        <div class="project-arrow" aria-hidden="true">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="7" y1="17" x2="17" y2="7"></line>
                                <polyline points="7 7 17 7 17 17"></polyline>
                            </svg>
                        </div>
                    </a>
                <?php endforeach; ?>

                <!-- Estado Vazio Dinâmico da Busca -->
                <div class="empty-state" id="searchEmptyState" style="display: none;">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <h4>Nenhum resultado para a busca</h4>
                    <p>Tente digitar outro termo para localizar sua pasta.</p>
                </div>
            <?php endif; ?>
        </main>

        <!-- Rodapé -->
        <footer class="footer">
            <a href="https://laragon.org/docs" target="_blank" rel="noopener">Documentação Laragon</a>
            <span class="dot">•</span>
            <a href="/phpmyadmin" target="_blank" rel="noopener">phpMyAdmin</a>
            <span class="dot">•</span>
            <a href="/?q=info">phpinfo()</a>
            <span class="dot">•</span>
            <span>Backup e Otimização para Laragon 6</span>
        </footer>
    </div>

    <!-- Scripts de Interatividade -->
    <script>
        // 1. Alternador de Tema Claro / Escuro
        const themeToggleBtn = document.getElementById('themeToggle');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('laragon_theme', theme);
        }

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme') || 'dark';
                const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(nextTheme);
            });
        }

        // 2. Busca Rápida em Tempo Real
        const searchInput = document.getElementById('projectSearch');
        const cards = document.querySelectorAll('.project-card');
        const countSpan = document.getElementById('projectsCount');
        const searchEmptyState = document.getElementById('searchEmptyState');
        const totalProjects = cards.length;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();
                let visibleCount = 0;

                cards.forEach(card => {
                    const name = card.getAttribute('data-name');
                    if (name.includes(query)) {
                        card.style.display = 'flex';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Atualiza contador dinamicamente
                countSpan.textContent = visibleCount;

                // Mostra/esconde empty state da busca
                if (searchEmptyState) {
                    searchEmptyState.style.display = (visibleCount === 0 && totalProjects > 0) ? 'block' : 'none';
                }
            });

            // Atalho de teclado '/' para focar na busca rapidamente
            window.addEventListener('keydown', function(e) {
                if (e.key === '/' && document.activeElement !== searchInput) {
                    e.preventDefault();
                    searchInput.focus();
                }
            });
        }

        // 3. Abertura da Pasta Raiz no Windows Explorer pelo Card Unificado
        const openFolderBtn = document.getElementById('openFolderBtn');
        const folderIconWrapper = openFolderBtn ? openFolderBtn.querySelector('.folder-icon-wrapper') : null;

        if (openFolderBtn) {
            openFolderBtn.addEventListener('click', function() {
                // Efeito visual sutil de clique na pastinha
                openFolderBtn.style.transform = 'scale(0.96)';
                if (folderIconWrapper) folderIconWrapper.style.transform = 'scale(1.3)';

                fetch('/?action=open_folder')
                    .then(res => res.json())
                    .finally(() => {
                        setTimeout(() => {
                            openFolderBtn.style.transform = '';
                            if (folderIconWrapper) folderIconWrapper.style.transform = '';
                        }, 250);
                    });
            });
        }
    </script>
</body>
</html>