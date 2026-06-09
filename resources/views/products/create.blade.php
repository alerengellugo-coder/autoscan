@extends('layouts.app')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Producto')

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('admin.productos.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15,18 9,12 15,6"/>
            </svg>
            Volver a Productos
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Nuevo Producto</h2>
            <p class="text-sm text-gray-500 mt-1">Completa los campos para registrar un nuevo producto en el inventario.</p>
        </div>

        <form method="POST" action="{{ route('admin.productos.store') }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto <span class="text-red-500">*</span></label>
                <input type="text"
                       id="name"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Ej: Aceite de motor sintético">
                @error('name')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- SKU + Category --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text"
                           id="sku"
                           name="sku"
                           value="{{ old('sku') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="Ej: ACE-001">
                    @error('sku')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                    <select id="category"
                            name="category"
                            required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Seleccionar categoría</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['value'] }}" {{ old('category') == $category['value'] ? 'selected' : '' }}>
                                {{ $category['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Price + Cost --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Precio <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="price"
                               name="price"
                               value="{{ old('price') }}"
                               required
                               min="0"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    @error('price')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="cost" class="block text-sm font-medium text-gray-700 mb-1">Costo</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">$</span>
                        <input type="number"
                               id="cost"
                               name="cost"
                               value="{{ old('cost') }}"
                               min="0"
                               step="0.01"
                               class="w-full pl-7 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="0.00">
                    </div>
                    @error('cost')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Stock + Min Stock --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Cantidad en Stock <span class="text-red-500">*</span></label>
                    <input type="number"
                           id="stock_quantity"
                           name="stock_quantity"
                           value="{{ old('stock_quantity') }}"
                           required
                           min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="0">
                    @error('stock_quantity')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label for="min_stock_alert" class="block text-sm font-medium text-gray-700 mb-1">Stock Mínimo de Alerta <span class="text-red-500">*</span></label>
                    <input type="number"
                           id="min_stock_alert"
                           name="min_stock_alert"
                           value="{{ old('min_stock_alert') }}"
                           required
                           min="0"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                           placeholder="5">
                    @error('min_stock_alert')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Unit --}}
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unidad</label>
                <input type="text"
                       id="unit"
                       name="unit"
                       value="{{ old('unit') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                       placeholder="Ej: unidad, litro, pieza">
                @error('unit')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Image --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen del Producto</label>
                <div class="flex items-center gap-4">
                    <label for="image" class="flex-1 flex items-center justify-center gap-2 px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50/50 transition-colors">
                        <svg class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21,15 16,10 5,21"/>
                        </svg>
                        <span class="text-sm text-gray-500">Haz clic para seleccionar una imagen</span>
                    </label>
                </div>
                <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(this)">
                <div id="imagePreview" class="mt-2 hidden">
                    <img id="imagePreviewImg" src="" alt="Vista previa" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                    <button type="button" onclick="clearImagePreview()" class="mt-1 text-xs text-red-500 hover:text-red-700">Eliminar imagen</button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Formatos: JPG, PNG, WebP. Máximo 2MB.</p>
                @error('image')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-y"
                          placeholder="Describe el producto...">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.productos.index') }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Crear Producto
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const img = document.getElementById('imagePreviewImg');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
function clearImagePreview() {
    const input = document.getElementById('image');
    const preview = document.getElementById('imagePreview');
    input.value = '';
    preview.classList.add('hidden');
}
</script>
@endpush
