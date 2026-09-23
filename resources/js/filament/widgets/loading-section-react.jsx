import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import LoadingSection from '../../../../vendor/filament/support/resources/js/react/LoadingSection'

function LoadingSectionReact() {
    const [state, setState] = useState('loading')
    const [tall, setTall] = useState(false)
    const [visible, setVisible] = useState(true)
    const [gridOffset, setGridOffset] = useState(false)
    return (
        <section className="fi-section">
            <div className="fi-section-content">
                <div className="actions-demo" data-framework="react">
                    <h2 className="fi-section-header-heading">
                        React · Project overview
                    </h2>
                    <p>
                        Load the latest community garden milestones. Finish
                        manually to explore success and failure.
                    </p>
                    <div
                        className="fi-grid"
                        data-testid="preview-grid"
                        style={{
                            '--cols-default': 'repeat(4, minmax(0, 1fr))',
                        }}
                    >
                        {visible &&
                            (state === 'loading' ? (
                                <LoadingSection
                                    data-testid="loading-section"
                                    height={tall ? '12rem' : undefined}
                                    loadingLabel="Loading project overview"
                                    columnSpan={
                                        gridOffset
                                            ? { default: 'full', lg: 2 }
                                            : { default: 'full' }
                                    }
                                    columnStart={
                                        gridOffset ? { lg: 3 } : undefined
                                    }
                                />
                            ) : (
                                <div
                                    className="fi-section fi-grid-col"
                                    style={{ '--col-span-default': '1 / -1' }}
                                    data-testid="content"
                                >
                                    <div className="fi-section-content">
                                        {state === 'ready'
                                            ? 'Community garden · 8 of 12 milestones complete.'
                                            : 'Project overview could not be loaded. Try again.'}
                                    </div>
                                </div>
                            ))}
                    </div>
                    <div className="icon-demo-controls">
                        <button
                            type="button"
                            data-testid="load"
                            disabled={state === 'loading'}
                            onClick={() => setState('loading')}
                        >
                            Load overview
                        </button>
                        <button
                            type="button"
                            data-testid="finish"
                            disabled={state !== 'loading'}
                            onClick={() => setState('ready')}
                        >
                            Finish successfully
                        </button>
                        <button
                            type="button"
                            data-testid="fail"
                            disabled={state !== 'loading'}
                            onClick={() => setState('error')}
                        >
                            Simulate error
                        </button>
                        <button
                            type="button"
                            data-testid="reset"
                            onClick={() => {
                                setState('loading')
                                setTall(false)
                                setVisible(true)
                                setGridOffset(false)
                            }}
                        >
                            Reset
                        </button>
                    </div>
                    <label>
                        <input
                            type="checkbox"
                            data-testid="tall"
                            checked={tall}
                            onChange={(event) => setTall(event.target.checked)}
                        />{' '}
                        Taller placeholder
                    </label>
                    <label>
                        <input
                            type="checkbox"
                            data-testid="visible"
                            checked={visible}
                            onChange={(event) =>
                                setVisible(event.target.checked)
                            }
                        />{' '}
                        Show preview
                    </label>
                    <p role="status" data-testid="result">
                        {state === 'ready'
                            ? 'Project overview is ready.'
                            : state === 'error'
                              ? 'Loading failed. You can retry.'
                              : ''}
                    </p>
                    <label>
                        <input
                            type="checkbox"
                            data-testid="grid-offset"
                            checked={gridOffset}
                            onChange={(event) =>
                                setGridOffset(event.target.checked)
                            }
                        />{' '}
                        Use columns 3–4 on wide screens (1024px+)
                    </label>
                </div>
            </div>
        </section>
    )
}

export default function mountLoadingSectionReact({ host, props }) {
    const root = createRoot(host)
    root.render(<LoadingSectionReact {...props} />)
    return {
        update: (next) => root.render(<LoadingSectionReact {...next} />),
        destroy: () => root.unmount(),
    }
}
