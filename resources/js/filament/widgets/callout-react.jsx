import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import { Info, CircleCheck } from 'lucide-react'
import Callout from '../../../../vendor/filament/support/resources/js/react/Callout'

function CalloutReact() {
    const [color, setColor] = useState('gray')
    const [icon, setIcon] = useState('none')
    const [override, setOverride] = useState(false)
    const [details, setDetails] = useState(true)
    const [actions, setActions] = useState(true)
    const [visible, setVisible] = useState(true)
    const [message, setMessage] = useState('No changes published.')
    return (
        <section className="callout-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <h3 className="fi-sr-only">Release notices</h3>
            {visible && (
                <Callout
                    data-testid="callout"
                    color={color}
                    heading="Your next release"
                    description={
                        details ? (
                            <>
                                Review the <strong>release notes</strong> before
                                publishing.
                            </>
                        ) : undefined
                    }
                    icon={
                        icon === 'none' ? undefined : icon === 'info' ? (
                            <Info />
                        ) : (
                            <CircleCheck />
                        )
                    }
                    iconColor={override ? 'success' : undefined}
                    iconSize={override ? 'sm' : 'lg'}
                    controls={
                        actions ? (
                            <button
                                type="button"
                                data-testid="dismiss"
                                aria-label="Dismiss release notice"
                                onClick={() => {
                                    setVisible(false)
                                    setMessage(
                                        'Release notice dismissed locally.',
                                    )
                                }}
                            >
                                ×
                            </button>
                        ) : undefined
                    }
                >
                    {actions && (
                        <>
                            <button
                                type="button"
                                data-testid="review"
                                onClick={() =>
                                    setMessage(
                                        'Release notes reviewed locally.',
                                    )
                                }
                            >
                                Review notes
                            </button>
                            <a href="#react-release">View release</a>
                        </>
                    )}
                </Callout>
            )}
            <div className="icon-demo-controls">
                <label>
                    Color{' '}
                    <select
                        data-testid="color"
                        value={color}
                        onChange={(event) => setColor(event.target.value)}
                    >
                        {[
                            'gray',
                            'primary',
                            'info',
                            'success',
                            'warning',
                            'danger',
                            'brand',
                        ].map((value) => (
                            <option key={value}>{value}</option>
                        ))}
                    </select>
                </label>
                <label>
                    Icon{' '}
                    <select
                        data-testid="icon"
                        value={icon}
                        onChange={(event) => setIcon(event.target.value)}
                    >
                        {['none', 'info', 'check'].map((value) => (
                            <option key={value}>{value}</option>
                        ))}
                    </select>
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="override"
                        checked={override}
                        onChange={(event) => setOverride(event.target.checked)}
                    />
                    Small success icon
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="details"
                        checked={details}
                        onChange={(event) => setDetails(event.target.checked)}
                    />
                    Description
                </label>
                <label>
                    <input
                        type="checkbox"
                        data-testid="actions"
                        checked={actions}
                        onChange={(event) => setActions(event.target.checked)}
                    />
                    Actions and controls
                </label>
                <button
                    type="button"
                    data-testid="reset"
                    onClick={() => {
                        setColor('gray')
                        setIcon('none')
                        setOverride(false)
                        setDetails(true)
                        setActions(true)
                        setVisible(true)
                        setMessage('No changes published.')
                    }}
                >
                    Reset
                </button>
            </div>
            <p role="status" data-testid="result">
                {message}
            </p>
            <p id="react-release" tabIndex={-1}>
                September release · Ready for review
            </p>
        </section>
    )
}

export default function mountCalloutReact({ host, props }) {
    const root = createRoot(host)
    root.render(<CalloutReact {...props} />)
    return {
        update: (next) => root.render(<CalloutReact {...next} />),
        destroy: () => root.unmount(),
    }
}
