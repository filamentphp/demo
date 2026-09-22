import React, { useState } from 'react'
import { createRoot } from 'react-dom/client'
import Radio from '../../../../vendor/filament/support/resources/js/react/Radio'

function RadioReact() {
    const [delivery, setDelivery] = useState('standard')
    const [disabled, setDisabled] = useState(false)
    const [invalid, setInvalid] = useState(false)
    const [required, setRequired] = useState(false)
    const [submitted, setSubmitted] = useState('Not previewed')
    return (
        <div className="radio-demo" data-framework="react">
            <h2 className="fi-section-header-heading">React · JavaScript</h2>
            <form
                onSubmit={(event) => {
                    event.preventDefault()
                    setSubmitted(
                        JSON.stringify([...new FormData(event.currentTarget)]),
                    )
                }}
                onReset={() => {
                    setDelivery('standard')
                    setSubmitted('Not previewed')
                }}
            >
                <fieldset>
                    <legend>Delivery method</legend>
                    {['standard', 'express'].map((value) => (
                        <label key={value}>
                            <Radio
                                name="delivery"
                                value={value}
                                checked={delivery === value}
                                onChange={(event) =>
                                    setDelivery(event.currentTarget.value)
                                }
                                disabled={disabled}
                                valid={!invalid}
                                required={required}
                                aria-invalid={invalid}
                            />
                            {value === 'standard'
                                ? 'Standard · 3–5 working days'
                                : 'Express · next working day'}
                        </label>
                    ))}
                </fieldset>
                <div className="radio-demo-controls">
                    <button type="submit" className="fi-btn fi-size-sm">
                        Preview FormData
                    </button>
                    <button type="reset" className="fi-btn fi-size-sm">
                        Reset delivery
                    </button>
                    <button
                        type="button"
                        className="fi-btn fi-size-sm"
                        onClick={() => setDelivery(null)}
                    >
                        Clear selection
                    </button>
                </div>
            </form>
            <div className="radio-demo-controls">
                <label>
                    <input
                        type="checkbox"
                        checked={disabled}
                        onChange={(event) =>
                            setDisabled(event.currentTarget.checked)
                        }
                    />{' '}
                    Disabled
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={invalid}
                        onChange={(event) =>
                            setInvalid(event.currentTarget.checked)
                        }
                    />{' '}
                    Invalid
                </label>
                <label>
                    <input
                        type="checkbox"
                        checked={required}
                        onChange={(event) =>
                            setRequired(event.currentTarget.checked)
                        }
                    />{' '}
                    Required
                </label>
            </div>
            <output>
                {delivery ?? 'No selection'} · {submitted}
            </output>
            <p>Preview stays in this browser. No order is placed.</p>
        </div>
    )
}

export default function mountRadioReact({ host, props: initialProps }) {
    const root = createRoot(host)
    const update = (props) => root.render(<RadioReact {...props} />)
    update(initialProps)

    return { update, destroy: () => root.unmount() }
}
