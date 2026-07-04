<!-- Diff Modal -->
<div id="diffModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-opacity opacity-0">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <i class="bi bi-file-diff text-blue-500"></i> Detalles de la Modificación
            </h3>
            <button type="button" onclick="closeDiffModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-6 overflow-y-auto flex-1 bg-gray-50 dark:bg-gray-900">
            <div id="diffContainer" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden text-sm font-mono">
                <!-- Diff content will be injected here by JS -->
            </div>
        </div>
        
        <!-- Footer -->
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 flex justify-end">
            <button type="button" onclick="closeDiffModal()" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium text-sm">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function openDiffModal(oldValuesStr, newValuesStr) {
        const modal = document.getElementById('diffModal');
        const container = document.getElementById('diffContainer');
        
        let oldVals = {};
        let newVals = {};
        
        try { if(oldValuesStr) oldVals = JSON.parse(oldValuesStr); } catch(e) {}
        try { if(newValuesStr) newVals = JSON.parse(newValuesStr); } catch(e) {}
        
        let html = '<div class="divide-y divide-gray-100 dark:divide-gray-700">';
        const allKeys = new Set([...Object.keys(oldVals), ...Object.keys(newVals)]);
        
        if (allKeys.size === 0) {
            html += '<div class="p-4 text-center text-gray-500">No hay detalles disponibles.</div>';
        } else {
            allKeys.forEach(key => {
                const oldV = oldVals[key];
                const newV = newVals[key];
                
                // Formateo para que los nulos o vacíos se vean mejor
                const displayOld = (oldV === null || oldV === undefined || oldV === "") ? '<em>Nulo/Vacío</em>' : (typeof oldV === 'object' ? JSON.stringify(oldV) : oldV);
                const displayNew = (newV === null || newV === undefined || newV === "") ? '<em>Nulo/Vacío</em>' : (typeof newV === 'object' ? JSON.stringify(newV) : newV);

                html += `<div class="grid grid-cols-1 md:grid-cols-12 gap-0 hover:bg-gray-50/50 dark:hover:bg-gray-700/30 transition-colors">`;
                html += `<div class="md:col-span-3 p-3 bg-gray-50 dark:bg-gray-800/80 text-gray-600 dark:text-gray-400 font-bold border-r border-gray-100 dark:border-gray-700 flex items-center"><span class="truncate" title="${key}">${key}</span></div>`;
                
                html += `<div class="md:col-span-9 flex flex-col md:flex-row">`;
                
                if (oldV !== undefined && newV !== undefined && oldV != newV) {
                    // Update
                    html += `<div class="flex-1 p-3 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border-b md:border-b-0 md:border-r border-red-100 dark:border-red-900/30 line-through decoration-red-300 dark:decoration-red-800 break-all">${displayOld}</div>`;
                    html += `<div class="flex-1 p-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 break-all">${displayNew}</div>`;
                } else if (oldV === undefined && newV !== undefined) {
                    // Create
                    html += `<div class="flex-1 p-3 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 break-all col-span-2">+ ${displayNew}</div>`;
                } else if (oldV !== undefined && newV === undefined) {
                    // Delete
                    html += `<div class="flex-1 p-3 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 line-through decoration-red-300 break-all col-span-2">- ${displayOld}</div>`;
                } else {
                    // Unchanged (if requested to show)
                    html += `<div class="flex-1 p-3 text-gray-500 dark:text-gray-400 break-all col-span-2">${displayOld}</div>`;
                }
                
                html += `</div></div>`;
            });
        }
        html += '</div>';
        container.innerHTML = html;
        
        modal.classList.remove('hidden');
        // Pequeño delay para animación
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        }, 10);
    }
    
    function closeDiffModal() {
        const modal = document.getElementById('diffModal');
        modal.classList.add('opacity-0');
        modal.firstElementChild.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
