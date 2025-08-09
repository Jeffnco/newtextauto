<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Content Factory' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-white w-64 shadow-lg">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-rocket text-blue-600"></i>
                    Content Factory
                </h1>
            </div>
            
            <nav class="mt-6">
                <div class="px-6 py-3">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Menu Principal</p>
                </div>
                
                <a href="/dashboard" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-chart-line w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                
                <a href="/articles" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-file-alt w-5 h-5 mr-3"></i>
                    Articles
                </a>
                
                <a href="/projects" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-folder w-5 h-5 mr-3"></i>
                    Projets
                </a>
                
                <a href="/personas" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-user-friends w-5 h-5 mr-3"></i>
                    Personas
                </a>
                
                <div class="px-6 py-3 mt-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Outils</p>
                </div>
                
                <a href="/keyword-research" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-search w-5 h-5 mr-3"></i>
                    Recherche Mots-clés
                </a>
                
                <a href="/rss-feeds" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-rss w-5 h-5 mr-3"></i>
                    Flux RSS
                </a>
                
                <a href="/content-ideas" class="flex items-center px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="fas fa-lightbulb w-5 h-5 mr-3"></i>
                    Idées de Contenu
                </a>
            </nav>
            
            <div class="absolute bottom-0 w-64 p-6">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-700"><?= $_SESSION['user_email'] ?? 'Utilisateur' ?></p>
                        <a href="/auth/logout" class="text-xs text-gray-500 hover:text-red-600">Se déconnecter</a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800"><?= $page_title ?? 'Dashboard' ?></h2>
                        
                        <div class="flex items-center space-x-4">
                            <button class="p-2 text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-bell"></i>
                            </button>
                            
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 transition-colors">
                                    <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil</a>
                                    <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Paramètres</a>
                                    <hr class="my-1">
                                    <a href="/auth/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Se déconnecter</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                <div class="container mx-auto px-6 py-8">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span class="block sm:inline">Opération réussie !</span>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span class="block sm:inline">Une erreur s'est produite.</span>
                        </div>
                    <?php endif; ?>
                    
                    <?= $content ?? '' ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>