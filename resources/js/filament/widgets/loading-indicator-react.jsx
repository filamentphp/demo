import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import LoadingIndicator from '../../../../vendor/filament/support/resources/js/react/LoadingIndicator'

function LoadingIndicatorReact() {
    const [size, setSize] = useState('md')
    const [loading, setLoading] = useState(true)
    const [accent, setAccent] = useState(false)
    return (
        <div className="loading-indicator-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <div role="status" className="loading-indicator-demo-status">
                {loading && (
                    <LoadingIndicator
                        size={size}
                        className={
                            accent ? 'loading-indicator-demo-accent' : undefined
                        }
                    />
                )}
                <span>
                    {loading
                        ? 'Preparing your report…'
                        : 'Your report is ready'}
                </span>
            </div>
            <div className="loading-indicator-demo-controls">
                <label>
                    Size{' '}
                    <select
                        value={size}
                        onChange={(event) => setSize(event.target.value)}
                    >
                        {['xs', 'sm', 'md', 'lg', 'xl', '2xl'].map((size) => (
                            <option key={size}>{size}</option>
                        ))}
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={loading}
                        onChange={(event) => setLoading(event.target.checked)}
                    />{' '}
                    Loading
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={accent}
                        onChange={(event) => setAccent(event.target.checked)}
                    />{' '}
                    Theme accent
                </label>
            </div>
            <button
                type="button"
                className="fi-btn fi-size-sm"
                onClick={() => {
                    setSize('md')
                    setLoading(true)
                    setAccent(false)
                }}
            >
                Reset
            </button>
        </div>
    )
}

export default function mountLoadingIndicatorReact({
    host,
    props: initialProps,
}) {
    const root = createRoot(host)
    const update = (props) => root.render(<LoadingIndicatorReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
