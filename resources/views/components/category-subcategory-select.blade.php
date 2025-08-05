<div class="flex gap-4 items-end">
    <div>
        <label class="block text-xs font-semibold mb-1">Category</label>
        <select name="{{ $categoryName }}" id="{{ $categoryId }}" class="break-all border rounded px-2 py-1 pr-8 min-w-[7rem] w-full sm:w-44 lg:w-64">
            <option value="">All</option>
            @foreach($categories as $cat)
                @if($cat->subcategories && count($cat->subcategories))
                    <option class="break-all" value="{{ $cat->id }}" title="{{ $cat->name }}" @if((string)$selectedCategory === (string)$cat->id) selected @endif>{{ $cat->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold mb-1">Subcategory</label>
        <select name="{{ $subcategoryName }}" id="{{ $subcategoryId }}" class="break-all border rounded px-2 py-1 pr-8 min-w-[7rem] w-full sm:w-44 lg:w-64">
            <option value="">All</option>
            @foreach($subcategories as $sub)
                <option class="break-all" value="{{ $sub->id }}" data-category="{{ $sub->category_id }}" title="{{ $sub->name }}" @if($selectedSubcategory == $sub->id) selected @endif>{{ $sub->name }}</option>
            @endforeach
        </select>
    </div>
</div>
@once
    <script>
    window.initDependentSubcategories = window.initDependentSubcategories || function(categorySelectId, subcategorySelectId) {
        const catSelect = document.getElementById(categorySelectId);
        const subcatSelect = document.getElementById(subcategorySelectId);
        if (!catSelect || !subcatSelect) return;
        const allOptions = Array.from(subcatSelect.options);
        function filterSubcategories() {
            const catId = catSelect.value;
            subcatSelect.innerHTML = '';
            subcatSelect.appendChild(allOptions[0]);
            let firstValid = null;
            allOptions.slice(1).forEach(opt => {
                if (!catId || opt.getAttribute('data-category') === catId) {
                    subcatSelect.appendChild(opt);
                    if (!firstValid) firstValid = opt;
                }
            });
            // If після фільтрації нічого не вибрано — вибрати першу доступну
            if (subcatSelect.options.length > 1) {
                subcatSelect.selectedIndex = 1;
            } else {
                subcatSelect.selectedIndex = 0;
            }
        }
        catSelect.addEventListener('change', filterSubcategories);
        filterSubcategories();
    };
    </script>
@endonce
<script>
    window.initDependentSubcategories('{{ $categoryId }}', '{{ $subcategoryId }}');
</script>
