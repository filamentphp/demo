<x-filament-widgets::widget>
    <x-filament::section>
        <div class="breadcrumbs-demo" data-framework="blade">
            <h2 class="fi-section-header-heading">Blade · reference</h2>
            <x-filament::breadcrumbs
                :breadcrumbs="['/' => 'Welcome', '/shop/products' => 'Products', 'Product details']"
            />
            <p>Follow the links to real pages, or use the interactive examples to change the trail and direction.</p>
            <div dir="rtl">
                <x-filament::breadcrumbs
                    :breadcrumbs="['/' => 'الرئيسية', '/shop/products' => 'المنتجات', 'تفاصيل المنتج']"
                    aria-label="مسار التنقل"
                />
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
