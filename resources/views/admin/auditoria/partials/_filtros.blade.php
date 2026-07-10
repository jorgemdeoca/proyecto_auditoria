{{-- Componente reutilizable de filtros para el módulo de Auditoría --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
    <form id="ajaxFilterForm" method="GET" action="{{ $actionUrl }}" class="flex flex-wrap gap-3 items-end">

        {{-- ID o Referencia --}}
        <div class="flex-1 min-w-[100px]">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                ID / Ref.
            </label>
            <input type="text" name="registro_id" value="{{ request('registro_id') }}" placeholder="Ej: 5"
                class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
        </div>

        {{-- Rango de fechas --}}
        <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                Desde
            </label>
            <input type="date" name="desde" value="{{ request('desde') }}"
                class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
        </div>

        <div class="flex-1 min-w-[140px]">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                Hasta
            </label>
            <input type="date" name="hasta" value="{{ request('hasta') }}"
                class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
        </div>

        {{-- Selector de evento (si se pasa la variable $eventos) --}}
        @if(isset($eventos))
        <div class="flex-1 min-w-[160px]">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                Tipo de Evento
            </label>
            <select name="{{ $eventoParam ?? 'evento' }}"
                class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 transition">
                <option value="">— Todos —</option>
                @foreach($eventos as $key => $label)
                    <option value="{{ $key }}" @selected(request($eventoParam ?? 'evento') === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @endif

        {{-- Campo libre de búsqueda (correo, etc.) --}}
        @if(isset($searchField))
        <div class="flex-1 min-w-[200px]">
            <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                {{ $searchLabel ?? 'Buscar' }}
            </label>
            <input type="text" name="{{ $searchField }}" value="{{ request($searchField) }}"
                placeholder="{{ $searchPlaceholder ?? '' }}"
                class="w-full rounded-lg border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-sm text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-emerald-500 transition">
        </div>
        @endif

        {{-- Botones --}}
        <div class="flex gap-2">
            <button type="submit"
                class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-all shadow-sm flex items-center gap-2">
                <i class="bi bi-search"></i> Filtrar
            </button>
            <a href="{{ $actionUrl }}"
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 text-sm font-semibold rounded-lg transition-all flex items-center gap-2">
                <i class="bi bi-x-circle"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('ajaxFilterForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = new URL(form.action);
        const formData = new FormData(form);
        const searchParams = new URLSearchParams(formData);
        
        // Remove empty params to keep URL clean
        for(let [key, val] of formData.entries()) {
            if(!val) searchParams.delete(key);
        }

        url.search = searchParams.toString();
        
        const btn = form.querySelector('button[type="submit"]');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Cargando...';
        btn.disabled = true;

        // Container of the table
        const tableContainer = document.getElementById('table-container');
        if (tableContainer) {
            tableContainer.style.opacity = '0.5';
        }

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.getElementById('table-container');
            
            if (newTable && tableContainer) {
                tableContainer.innerHTML = newTable.innerHTML;
                tableContainer.style.opacity = '1';
                
                // Update URL without reloading
                window.history.pushState({}, '', url.toString());
            }
        })
        .catch(err => {
            console.error('Error filtering:', err);
            window.location.href = url.toString(); // Fallback
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    });
});
</script>
