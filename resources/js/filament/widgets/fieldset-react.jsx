import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import Fieldset from '../../../../vendor/filament/support/resources/js/react/Fieldset'

function FieldsetReact() {
    const [options, setOptions] = useState({})
    const [delivery, setDelivery] = useState(false)
    return (
        <div className="fieldset-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <Fieldset
                label={
                    options.noLabel ? null : options.richLabel ? (
                        <em>Delivery preferences</em>
                    ) : (
                        'Delivery preferences'
                    )
                }
                contained={!options.uncontained}
                labelHidden={!!options.labelHidden}
                required={!!options.required}
                disabled={!!options.disabled}
                dir={options.rtl ? 'rtl' : 'ltr'}
                className={options.accent ? 'fieldset-demo-accent' : undefined}
            >
                <label className="fieldset-demo-option">
                    <input
                        type="checkbox"
                        checked={delivery}
                        onChange={(event) => setDelivery(event.target.checked)}
                    />{' '}
                    Leave the parcel at reception
                </label>
            </Fieldset>
            <div className="fieldset-demo-controls">
                {Object.entries({
                    uncontained: 'Uncontained',
                    labelHidden: 'Hide legend',
                    required: 'Required mark',
                    disabled: 'Disabled',
                    noLabel: 'No legend',
                    rtl: 'RTL',
                    accent: 'Theme accent',
                    richLabel: 'Rich legend',
                }).map(([name, label]) => (
                    <label key={name}>
                        <input
                            type="checkbox"
                            checked={!!options[name]}
                            onChange={(event) =>
                                setOptions({
                                    ...options,
                                    [name]: event.target.checked,
                                })
                            }
                        />{' '}
                        {label}
                    </label>
                ))}
            </div>
            <button
                type="button"
                className="fi-btn fi-size-sm"
                onClick={() => {
                    setOptions({})
                    setDelivery(false)
                }}
            >
                Reset
            </button>
            <output>
                {delivery ? 'Leave at reception' : 'Hand to recipient'}
            </output>
        </div>
    )
}

export default function mountFieldsetReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<FieldsetReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
