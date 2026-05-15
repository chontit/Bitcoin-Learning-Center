<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trader Tool Suite</title>
	<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%233b82f6' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='22 12 18 12 15 21 9 3 6 12 2 12'%3E%3C/polyline%3E%3C/svg%3E" type="image/svg+xml">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Prompt', sans-serif; }
        
        /* Animation Utility */
        .card-hover-effect {
            transition: all 0.3s ease;
        }
        .card-hover-effect:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col items-center justify-center p-6 relative overflow-x-hidden">

<a href="/" class="fixed top-6 right-6 z-50 flex items-center gap-2 px-4 py-2 bg-gray-800/80 border border-gray-700 rounded-full text-gray-300 text-sm font-semibold hover:bg-blue-600 hover:text-white hover:border-blue-500 transition-all shadow-xl backdrop-blur-sm">
        <i class="fa-solid fa-house"></i>
        <span>HOME</span>
    </a>
	
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-96 h-96 bg-purple-600/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl text-center my-auto">
        
        <div class="mb-10 md:mb-12 mt-4 md:mt-0">
            <div class="inline-block p-3 rounded-full bg-gray-800 border border-gray-700 mb-4 shadow-lg">
                <i class="fa-solid fa-layer-group text-blue-400 text-2xl"></i>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-2">
                Position Sizing <span class="text-blue-500">Calculator</span>
            </h1>
            <p class="text-gray-400 text-base md:text-lg font-light">
                Professional Risk Management Tool
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 pb-8">
            
            <a href="long_position.php" class="card-hover-effect group block bg-gray-800 rounded-2xl p-8 md:p-12 border border-gray-700 hover:border-green-500/50 shadow-xl hover:shadow-green-900/20 cursor-pointer relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fa-solid fa-arrow-trend-up text-9xl"></i>
                </div>
                
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-gray-700 group-hover:border-green-500">
                        <i class="fa-solid fa-arrow-trend-up text-4xl text-green-500"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 group-hover:text-green-400 transition-colors">Long Position</h2>
                    <p class="text-gray-400 text-sm">
                        คำนวณหน้าเทรดขาขึ้น (Buy)<br>
                        <span class="text-xs text-gray-500">SL ต่ำกว่าทุน / TP สูงกว่าทุน</span>
                    </p>
                    <div class="mt-6 px-4 py-2 rounded-full bg-gray-900 text-green-500 text-xs font-bold border border-gray-700 group-hover:bg-green-500 group-hover:text-white transition-all">
                        Select Long <i class="fa-solid fa-arrow-right ml-1"></i>
                    </div>
                </div>
            </a>

            <a href="short_position.php" class="card-hover-effect group block bg-gray-800 rounded-2xl p-8 md:p-12 border border-gray-700 hover:border-red-500/50 shadow-xl hover:shadow-red-900/20 cursor-pointer relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <i class="fa-solid fa-arrow-trend-down text-9xl"></i>
                </div>
                
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full bg-gray-900 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform border border-gray-700 group-hover:border-red-500">
                        <i class="fa-solid fa-arrow-trend-down text-4xl text-red-500"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-2 group-hover:text-red-400 transition-colors">Short Position</h2>
                    <p class="text-gray-400 text-sm">
                        คำนวณหน้าเทรดขาลง (Sell)<br>
                        <span class="text-xs text-gray-500">SL สูงกว่าทุน / TP ต่ำกว่าทุน</span>
                    </p>
                    <div class="mt-6 px-4 py-2 rounded-full bg-gray-900 text-red-500 text-xs font-bold border border-gray-700 group-hover:bg-red-500 group-hover:text-white transition-all">
                        Select Short <i class="fa-solid fa-arrow-right ml-1"></i>
                    </div>
                </div>
            </a>

        </div>

        <div class="mt-4 text-gray-500 text-xs pb-4">
            <p><i class="fa-solid fa-lock"></i> Secure & Private (Local Storage)</p>
        </div>
    </div>

</body>
</html>