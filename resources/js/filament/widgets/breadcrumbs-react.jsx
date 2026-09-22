import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import Breadcrumbs from '../../../../vendor/filament/support/resources/js/react/Breadcrumbs'

function BreadcrumbsReact() {
    const [alternate, setAlternate] = useState(false)
    const [rtl, setRtl] = useState(false)
    const [accent, setAccent] = useState(false)
    const [spa, setSpa] = useState(false)
    const [customSeparator, setCustomSeparator] = useState(false)

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

    const breadcrumbs = [
        { label: rtl ? 'الرئيسية' : 'Welcome', href: '/', onClick: navigate },
        {
            label: rtl ? 'المنتجات' : 'Products',
            href: '/shop/products',
            onClick: navigate,
        },
        {
            label: alternate
                ? 'A longer selected product with several variations'
                : rtl
                  ? 'تفاصيل المنتج'
                  : 'Product details',
            ...(alternate ? { href: '/shop/products', onClick: navigate } : {}),
        },
    ]

    return (
        <div className="breadcrumbs-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <Breadcrumbs
                breadcrumbs={breadcrumbs}
                dir={rtl ? 'rtl' : 'ltr'}
                aria-label="React breadcrumbs"
                className={accent ? 'breadcrumbs-demo-accent' : undefined}
                separator={customSeparator ? <span>/</span> : undefined}
                separatorRtl={customSeparator ? <span>/</span> : undefined}
            />
            <div className="breadcrumbs-demo-controls">
                <label>
                    <input
                        type="checkbox"
                        checked={alternate}
                        onChange={(event) => setAlternate(event.target.checked)}
                    />{' '}
                    Alternate trail
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={rtl}
                        onChange={(event) => setRtl(event.target.checked)}
                    />{' '}
                    RTL
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={accent}
                        onChange={(event) => setAccent(event.target.checked)}
                    />{' '}
                    Theme accent
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={customSeparator}
                        onChange={(event) =>
                            setCustomSeparator(event.target.checked)
                        }
                    />{' '}
                    Custom separator
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={spa}
                        onChange={(event) => setSpa(event.target.checked)}
                    />{' '}
                    Panel navigation
                </label>
            </div>
            <button
                type="button"
                className="fi-btn fi-size-sm"
                onClick={() => {
                    setAlternate(false)
                    setRtl(false)
                    setAccent(false)
                    setCustomSeparator(false)
                    setSpa(false)
                }}
            >
                Reset
            </button>
            <output>
                {spa
                    ? 'Links use Livewire navigation'
                    : 'Links use browser navigation'}
            </output>
        </div>
    )
}

export default function mountBreadcrumbsReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<BreadcrumbsReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
