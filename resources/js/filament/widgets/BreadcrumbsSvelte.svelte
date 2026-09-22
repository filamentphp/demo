<script>
    import Breadcrumbs from '../../../../vendor/filament/support/resources/js/svelte/Breadcrumbs.svelte'

    let alternate = $state(false)
    let rtl = $state(false)
    let accent = $state(false)
    let spa = $state(false)
    let customSeparator = $state(false)

    function navigate(event) {
        if (
            !spa ||
            event.button !== 0 ||
            event.metaKey ||
            event.ctrlKey ||
            event.shiftKey ||
            event.altKey
        )
            return
        event.preventDefault()
        window.Livewire.navigate(event.currentTarget.href)
    }

    const breadcrumbs = $derived([
        { label: rtl ? 'الرئيسية' : 'Welcome', href: '/', onclick: navigate },
        {
            label: rtl ? 'المنتجات' : 'Products',
            href: '/shop/products',
            onclick: navigate,
        },
        {
            label: alternate
                ? 'A longer selected product with several variations'
                : rtl
                  ? 'تفاصيل المنتج'
                  : 'Product details',
            ...(alternate ? { href: '/shop/products', onclick: navigate } : {}),
        },
    ])

    function reset() {
        alternate = false
        rtl = false
        accent = false
        spa = false
        customSeparator = false
    }
</script>

{#snippet separator()}<span>/</span>{/snippet}

<div class="breadcrumbs-demo" data-framework="svelte">
    <h2 class="fi-section-header-heading">Svelte · JavaScript</h2>
    <Breadcrumbs
        {breadcrumbs}
        dir={rtl ? 'rtl' : 'ltr'}
        aria-label="Svelte breadcrumbs"
        class={accent ? 'breadcrumbs-demo-accent' : undefined}
        separator={customSeparator ? separator : undefined}
        separatorRtl={customSeparator ? separator : undefined}
    />
    <div class="breadcrumbs-demo-controls">
        <label
            ><input type="checkbox" bind:checked={alternate} /> Alternate trail</label
        >
        <label><input type="checkbox" bind:checked={rtl} /> RTL</label>
        <label
            ><input type="checkbox" bind:checked={accent} /> Theme accent</label
        >
        <label
            ><input type="checkbox" bind:checked={customSeparator} /> Custom separator</label
        >
        <label
            ><input type="checkbox" bind:checked={spa} /> Panel navigation</label
        >
    </div>
    <button type="button" class="fi-btn fi-size-sm" onclick={reset}
        >Reset</button
    >
    <output
        >{spa
            ? 'Links use Livewire navigation'
            : 'Links use browser navigation'}</output
    >
</div>
